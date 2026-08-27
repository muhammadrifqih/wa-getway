import makeWASocket, { DisconnectReason, useMultiFileAuthState, Browsers } from '@whiskeysockets/baileys';
import { Boom } from '@hapi/boom';
import * as path from 'path';
import * as fs from 'fs';
import QRCode from 'qrcode';
import pino from 'pino';

const logger = pino({ level: 'info' });
const SESSIONS_DIR = path.join(__dirname, '../../sessions');

if (!fs.existsSync(SESSIONS_DIR)) {
  fs.mkdirSync(SESSIONS_DIR, { recursive: true });
}

interface SessionData {
  socket: any;
  qr: string | null;
  status: 'initializing' | 'waiting_qr' | 'connecting' | 'connected' | 'disconnected' | 'error';
  phone?: string;
}

class SessionManager {
  private sessions: Map<string, SessionData> = new Map();

  constructor() {
    this.restoreSessions();
  }

  private getSessionDir(sessionId: string) {
    return path.join(SESSIONS_DIR, sessionId);
  }

  private async restoreSessions() {
    if (!fs.existsSync(SESSIONS_DIR)) return;
    const dirs = fs.readdirSync(SESSIONS_DIR);
    for (const dir of dirs) {
      if (fs.statSync(path.join(SESSIONS_DIR, dir)).isDirectory()) {
        console.log(`Restoring session: ${dir}`);
        await this.createSession(dir);
      }
    }
  }

  public async createSession(sessionId: string, isReconnect = false) {
    let sessionData = this.sessions.get(sessionId);

    if (sessionData && !isReconnect) {
      return sessionData;
    }

    const sessionDir = this.getSessionDir(sessionId);
    const { state, saveCreds } = await useMultiFileAuthState(sessionDir);

    if (!sessionData) {
      sessionData = {
        socket: null,
        qr: null,
        status: 'initializing'
      };
      this.sessions.set(sessionId, sessionData);
    } else {
       sessionData.status = 'connecting';
    }

    const sock = makeWASocket({
      auth: state,
      printQRInTerminal: false,
      logger: logger as any,
      browser: ['Ubuntu', 'Chrome', '20.0.04']
    });

    sessionData.socket = sock;

    sock.ev.on('connection.update', async (update) => {
      const { connection, lastDisconnect, qr } = update;

      if (qr) {
        try {
          sessionData.qr = await QRCode.toDataURL(qr);
          sessionData.status = 'waiting_qr';
        } catch (err) {
          console.error('Failed to generate QR for', sessionId);
        }
      }

      if (connection === 'close') {
        const shouldReconnect = (lastDisconnect?.error as Boom)?.output?.statusCode !== DisconnectReason.loggedOut;
        if (shouldReconnect) {
          sessionData!.status = 'disconnected';
          console.log(`Connection closed for ${sessionId}, reconnecting...`);
          setTimeout(() => this.createSession(sessionId, true), 5000);
        } else {
          sessionData.status = 'error';
          console.log(`Connection closed for ${sessionId}. Logged out.`);
          this.removeSession(sessionId);
        }
      } else if (connection === 'open') {
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
      if (!msg.message || msg.key.fromMe) return;

      const sender = msg.key.remoteJid;
      let textMessage = msg.message.conversation || msg.message.extendedTextMessage?.text;
      
      let mediaBase64 = null;
      let messageType = 'message';
      
      const { downloadMediaMessage } = await import('@whiskeysockets/baileys');

      // Check if it's a media message
      const isMedia = msg.message.imageMessage || msg.message.videoMessage || msg.message.documentMessage || msg.message.audioMessage;
      const isLocation = msg.message.locationMessage || msg.message.liveLocationMessage;
      
      let locationData = null;

      if (isLocation) {
        const locMsg = msg.message.locationMessage || msg.message.liveLocationMessage;
        messageType = 'location';
        locationData = {
            latitude: locMsg?.degreesLatitude,
            longitude: locMsg?.degreesLongitude,
            name: locMsg?.name || '',
            address: locMsg?.address || ''
        };
        textMessage = `Shared Location: ${locMsg?.degreesLatitude}, ${locMsg?.degreesLongitude}`;
      } else if (isMedia) {
        try {
          messageType = msg.message.imageMessage ? 'image' : 
                        msg.message.videoMessage ? 'video' : 
                        msg.message.audioMessage ? 'audio' : 'document';
                        
          if (!textMessage) {
              textMessage = msg.message.imageMessage?.caption || msg.message.videoMessage?.caption || msg.message.documentMessage?.caption || '';
          }

          const buffer = await downloadMediaMessage(
              msg,
              'buffer',
              { },
              { logger: logger as any, reuploadRequest: sock.updateMediaMessage }
          );
          
          const mime = msg.message.imageMessage?.mimetype || 
                       msg.message.videoMessage?.mimetype || 
                       msg.message.audioMessage?.mimetype || 
                       msg.message.documentMessage?.mimetype || 'application/octet-stream';
          
          // Convert buffer to Base64 directly in RAM
          mediaBase64 = `data:${mime};base64,${buffer.toString('base64')}`;
          console.log(`[${sessionId}] Media downloaded and converted to Base64`);

        } catch (err) {
            console.error(`[${sessionId}] Failed to download media`, err);
        }
      }

      if (textMessage || mediaBase64 || locationData) {
        console.log(`[${sessionId}] Incoming ${messageType} from ${sender}: ${textMessage}`);
        
        // Forward to Laravel Webhook Receiver
        const webhookReceiverUrl = process.env.PANEL_WEBHOOK_URL || 'http://localhost:8000/api/internal/webhook/receive';
        const serviceKey = process.env.WA_SERVICE_KEY || 'secret_service_key';
        
        try {
          const axios = (await import('axios')).default;
          await axios.post(webhookReceiverUrl, {
            session_id: sessionId,
            sender: sender,
            message: textMessage,
            timestamp: msg.messageTimestamp,
            type: messageType,
            media_base64: mediaBase64,
            location_data: locationData
          }, {
            headers: {
              'Content-Type': 'application/json',
              'X-SERVICE-KEY': serviceKey
            },
            maxBodyLength: Infinity,
            maxContentLength: Infinity
          });
        } catch (err) {
          console.error(`[${sessionId}] Failed to forward message to Panel Webhook`, err);
        }
      }
    });

    return sessionData;
  }

  public getSession(sessionId: string) {
    return this.sessions.get(sessionId);
  }

  public async removeSession(sessionId: string) {
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

  public async logoutSession(sessionId: string) {
    const session = this.sessions.get(sessionId);
    if (session?.socket) {
      await session.socket.logout();
    }
    await this.removeSession(sessionId);
  }

  public getStatus(sessionId: string) {
    const session = this.sessions.get(sessionId);
    if (!session) return { status: 'not_found' };
    return {
      status: session.status,
      phone: session.phone
    };
  }

  public getQr(sessionId: string) {
    const session = this.sessions.get(sessionId);
    if (!session) return { qr: null };
    return { qr: session.qr };
  }

  public async sendMessage(sessionId: string, target: string, message: string, media?: { url?: string, name?: string, mimetype?: string }) {
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

    let messagePayload: any = { text: message };

    if (media && media.url) {
      if (media.mimetype && media.mimetype.startsWith('image/')) {
        messagePayload = {
          image: { url: media.url },
          caption: message,
          mimetype: media.mimetype
        };
      } else if (media.mimetype && media.mimetype.startsWith('video/')) {
        messagePayload = {
          video: { url: media.url },
          caption: message,
          mimetype: media.mimetype
        };
      } else if (media.mimetype && media.mimetype.startsWith('audio/')) {
        messagePayload = {
          audio: { url: media.url },
          mimetype: media.mimetype,
          ptt: false // Voice note if true
        };
      } else {
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

export const sessionManager = new SessionManager();
