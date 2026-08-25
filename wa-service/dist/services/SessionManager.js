"use strict";
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    var desc = Object.getOwnPropertyDescriptor(m, k);
    if (!desc || ("get" in desc ? !m.__esModule : desc.writable || desc.configurable)) {
      desc = { enumerable: true, get: function() { return m[k]; } };
    }
    Object.defineProperty(o, k2, desc);
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || (function () {
    var ownKeys = function(o) {
        ownKeys = Object.getOwnPropertyNames || function (o) {
            var ar = [];
            for (var k in o) if (Object.prototype.hasOwnProperty.call(o, k)) ar[ar.length] = k;
            return ar;
        };
        return ownKeys(o);
    };
    return function (mod) {
        if (mod && mod.__esModule) return mod;
        var result = {};
        if (mod != null) for (var k = ownKeys(mod), i = 0; i < k.length; i++) if (k[i] !== "default") __createBinding(result, mod, k[i]);
        __setModuleDefault(result, mod);
        return result;
    };
})();
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.sessionManager = void 0;
const baileys_1 = __importStar(require("@whiskeysockets/baileys"));
const path = __importStar(require("path"));
const fs = __importStar(require("fs"));
const qrcode_1 = __importDefault(require("qrcode"));
const pino_1 = __importDefault(require("pino"));
const logger = (0, pino_1.default)({ level: 'info' });
const SESSIONS_DIR = path.join(__dirname, '../../sessions');
if (!fs.existsSync(SESSIONS_DIR)) {
    fs.mkdirSync(SESSIONS_DIR, { recursive: true });
}
class SessionManager {
    sessions = new Map();
    constructor() {
        this.restoreSessions();
    }
    getSessionDir(sessionId) {
        return path.join(SESSIONS_DIR, sessionId);
    }
    async restoreSessions() {
        if (!fs.existsSync(SESSIONS_DIR))
            return;
        const dirs = fs.readdirSync(SESSIONS_DIR);
        for (const dir of dirs) {
            if (fs.statSync(path.join(SESSIONS_DIR, dir)).isDirectory()) {
                console.log(`Restoring session: ${dir}`);
                await this.createSession(dir);
            }
        }
    }
    async createSession(sessionId, isReconnect = false) {
        let sessionData = this.sessions.get(sessionId);
        if (sessionData && !isReconnect) {
            return sessionData;
        }
        const sessionDir = this.getSessionDir(sessionId);
        const { state, saveCreds } = await (0, baileys_1.useMultiFileAuthState)(sessionDir);
        if (!sessionData) {
            sessionData = {
                socket: null,
                qr: null,
                status: 'initializing'
            };
            this.sessions.set(sessionId, sessionData);
        }
        else {
            sessionData.status = 'connecting';
        }
        const sock = (0, baileys_1.default)({
            auth: state,
            printQRInTerminal: false,
            logger: logger,
            browser: ['Ubuntu', 'Chrome', '20.0.04']
        });
        sessionData.socket = sock;
        sock.ev.on('connection.update', async (update) => {
            const { connection, lastDisconnect, qr } = update;
            if (qr) {
                try {
                    sessionData.qr = await qrcode_1.default.toDataURL(qr);
                    sessionData.status = 'waiting_qr';
                }
                catch (err) {
                    console.error('Failed to generate QR for', sessionId);
                }
            }
            if (connection === 'close') {
                const shouldReconnect = lastDisconnect?.error?.output?.statusCode !== baileys_1.DisconnectReason.loggedOut;
                if (shouldReconnect) {
                    sessionData.status = 'disconnected';
                    console.log(`Connection closed for ${sessionId}, reconnecting...`);
                    setTimeout(() => this.createSession(sessionId, true), 5000);
                }
                else {
                    sessionData.status = 'error';
                    console.log(`Connection closed for ${sessionId}. Logged out.`);
                    this.removeSession(sessionId);
                }
            }
            else if (connection === 'open') {
                sessionData.status = 'connected';
                sessionData.qr = null;
                if (sock.user) {
                    sessionData.phone = sock.user.id.split(':')[0];
                }
                console.log(`Connection opened for ${sessionId}`);
            }
        });
        sock.ev.on('creds.update', saveCreds);
        sock.ev.on('messages.upsert', async (m) => {
            const msg = m.messages[0];
            if (!msg.message || msg.key.fromMe)
                return;
            const sender = msg.key.remoteJid;
            const textMessage = msg.message.conversation || msg.message.extendedTextMessage?.text;
            if (textMessage) {
                console.log(`[${sessionId}] Incoming message from ${sender}: ${textMessage}`);
                console.log(`[${sessionId}] FULL MSG:`, JSON.stringify(msg, null, 2));
                // Forward to Laravel Webhook Receiver
                const webhookReceiverUrl = process.env.PANEL_WEBHOOK_URL || 'http://localhost:8000/api/internal/webhook/receive';
                const serviceKey = process.env.WA_SERVICE_KEY || 'secret_service_key';
                try {
                    const axios = (await Promise.resolve().then(() => __importStar(require('axios')))).default;
                    await axios.post(webhookReceiverUrl, {
                        session_id: sessionId,
                        sender: sender,
                        message: textMessage,
                        timestamp: msg.messageTimestamp,
                        type: 'message'
                    }, {
                        headers: {
                            'Content-Type': 'application/json',
                            'X-SERVICE-KEY': serviceKey
                        }
                    });
                }
                catch (err) {
                    console.error(`[${sessionId}] Failed to forward message to Panel Webhook`, err);
                }
            }
        });
        return sessionData;
    }
    getSession(sessionId) {
        return this.sessions.get(sessionId);
    }
    async removeSession(sessionId) {
        const session = this.sessions.get(sessionId);
        if (session?.socket) {
            session.socket.end(undefined);
        }
        this.sessions.delete(sessionId);
        const sessionDir = this.getSessionDir(sessionId);
        if (fs.existsSync(sessionDir)) {
            fs.rmSync(sessionDir, { recursive: true, force: true });
        }
    }
    async logoutSession(sessionId) {
        const session = this.sessions.get(sessionId);
        if (session?.socket) {
            await session.socket.logout();
        }
        await this.removeSession(sessionId);
    }
    getStatus(sessionId) {
        const session = this.sessions.get(sessionId);
        if (!session)
            return { status: 'not_found' };
        return {
            status: session.status,
            phone: session.phone
        };
    }
    getQr(sessionId) {
        const session = this.sessions.get(sessionId);
        if (!session)
            return { qr: null };
        return { qr: session.qr };
    }
    async sendMessage(sessionId, target, message, media) {
        const session = this.sessions.get(sessionId);
        if (!session || session.status !== 'connected' || !session.socket) {
            throw new Error('Session is not connected');
        }
        // Normalize target
        let formattedTarget = target;
        if (formattedTarget.startsWith('0')) {
            formattedTarget = '62' + formattedTarget.slice(1);
        }
        if (!formattedTarget.includes('@')) {
            formattedTarget = formattedTarget + '@s.whatsapp.net';
        }
        let messagePayload = { text: message };
        if (media && media.url) {
            if (media.mimetype && media.mimetype.startsWith('image/')) {
                messagePayload = {
                    image: { url: media.url },
                    caption: message,
                    mimetype: media.mimetype
                };
            }
            else if (media.mimetype && media.mimetype.startsWith('video/')) {
                messagePayload = {
                    video: { url: media.url },
                    caption: message,
                    mimetype: media.mimetype
                };
            }
            else if (media.mimetype && media.mimetype.startsWith('audio/')) {
                messagePayload = {
                    audio: { url: media.url },
                    mimetype: media.mimetype,
                    ptt: false // Voice note if true
                };
            }
            else {
                messagePayload = {
                    document: { url: media.url },
                    caption: message,
                    fileName: media.name || 'document',
                    mimetype: media.mimetype || 'application/pdf'
                };
            }
        }
        const sendPromise = session.socket.sendMessage(formattedTarget, messagePayload);
        return await Promise.race([
            sendPromise,
            new Promise((_, reject) => setTimeout(() => reject(new Error('Send message timeout after 10s. Target might be invalid or unreachable.')), 10000))
        ]);
    }
}
exports.sessionManager = new SessionManager();
