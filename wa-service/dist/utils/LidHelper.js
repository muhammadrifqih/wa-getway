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
Object.defineProperty(exports, "__esModule", { value: true });
exports.lidStorage = exports.LidStorage = void 0;
exports.resolvePhoneNumber = resolvePhoneNumber;
const fs = __importStar(require("fs"));
const path = __importStar(require("path"));
const LID_FILE = path.join(__dirname, '../../../sessions/lid_mapping.json');
class LidStorage {
    map = new Map();
    constructor() {
        this.load();
    }
    load() {
        try {
            if (fs.existsSync(LID_FILE)) {
                const data = JSON.parse(fs.readFileSync(LID_FILE, 'utf-8'));
                for (const key of Object.keys(data)) {
                    this.map.set(key, data[key]);
                }
            }
        }
        catch (e) {
            console.error('Failed to load LID mapping storage', e);
        }
    }
    save() {
        try {
            const dir = path.dirname(LID_FILE);
            if (!fs.existsSync(dir))
                fs.mkdirSync(dir, { recursive: true });
            fs.writeFileSync(LID_FILE, JSON.stringify(Object.fromEntries(this.map), null, 2));
        }
        catch (e) {
            console.error('Failed to save LID mapping storage', e);
        }
    }
    storeMapping(lid, pn) {
        if (!lid || !pn)
            return;
        if (!lid.includes('@lid'))
            return;
        if (!pn.includes('@s.whatsapp.net'))
            pn += '@s.whatsapp.net';
        if (this.map.get(lid) !== pn) {
            this.map.set(lid, pn);
            this.save();
        }
    }
    getPn(lid) {
        return this.map.get(lid) || null;
    }
}
exports.LidStorage = LidStorage;
exports.lidStorage = new LidStorage();
/**
 * Resolves a remoteJid to its actual WhatsApp Number (PN) and LID components.
 */
async function resolvePhoneNumber(jid, sock) {
    if (!jid)
        return { jid: '', lid: null, phoneNumber: null };
    // Group or Broadcast JID (does not have a phone number in the prefix)
    if (jid.endsWith('@g.us') || jid.endsWith('@broadcast') || jid === 'status@broadcast') {
        return {
            jid: jid,
            lid: null,
            phoneNumber: null
        };
    }
    // Standard User JID
    if (jid.endsWith('@s.whatsapp.net') || jid.endsWith('@c.us')) {
        return {
            jid: jid,
            lid: null,
            phoneNumber: jid.split('@')[0]
        };
    }
    // LID User
    if (jid.includes('@lid') || jid.includes('@hosted.lid')) {
        const lid = jid;
        let pn = exports.lidStorage.getPn(lid);
        // Fallback to Baileys native signalRepository / multiFileAuthState
        if (!pn && sock?.signalRepository?.lidMapping?.getPNForLID) {
            try {
                const resolvedPn = await sock.signalRepository.lidMapping.getPNForLID(lid);
                if (resolvedPn) {
                    pn = resolvedPn;
                    exports.lidStorage.storeMapping(lid, resolvedPn); // cache it
                }
            }
            catch (err) {
                console.error('Error resolving LID from Baileys repository:', err);
            }
        }
        if (pn) {
            return {
                jid: pn,
                lid: lid,
                phoneNumber: pn.split('@')[0]
            };
        }
        else {
            // Unresolved LID
            return {
                jid: lid, // Treat the JID as the LID if unresolved
                lid: lid,
                phoneNumber: null
            };
        }
    }
    // Unknown formats
    return {
        jid: jid,
        lid: null,
        phoneNumber: jid.split('@')[0] || null
    };
}
