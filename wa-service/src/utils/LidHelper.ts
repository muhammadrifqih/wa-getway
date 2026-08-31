import * as fs from 'fs';
import * as path from 'path';

const LID_FILE = path.join(__dirname, '../../../sessions/lid_mapping.json');

export class LidStorage {
    private map: Map<string, string> = new Map();

    constructor() {
        this.load();
    }

    private load() {
        try {
            if (fs.existsSync(LID_FILE)) {
                const data = JSON.parse(fs.readFileSync(LID_FILE, 'utf-8'));
                for (const key of Object.keys(data)) {
                    this.map.set(key, data[key]);
                }
            }
        } catch (e) {
            console.error('Failed to load LID mapping storage', e);
        }
    }

    public save() {
        try {
            const dir = path.dirname(LID_FILE);
            if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
            
            fs.writeFileSync(LID_FILE, JSON.stringify(Object.fromEntries(this.map), null, 2));
        } catch (e) {
            console.error('Failed to save LID mapping storage', e);
        }
    }

    public storeMapping(lid: string, pn: string) {
        if (!lid || !pn) return;
        if (!lid.includes('@lid')) return;
        if (!pn.includes('@s.whatsapp.net')) pn += '@s.whatsapp.net';

        if (this.map.get(lid) !== pn) {
            this.map.set(lid, pn);
            this.save();
        }
    }

    public getPn(lid: string): string | null {
        return this.map.get(lid) || null;
    }
}

export const lidStorage = new LidStorage();

/**
 * Resolves a remoteJid to its actual WhatsApp Number (PN) and LID components.
 */
export async function resolvePhoneNumber(jid: string, sock: any): Promise<{ jid: string, lid: string | null, phoneNumber: string | null }> {
    if (!jid) return { jid: '', lid: null, phoneNumber: null };

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
        let pn = lidStorage.getPn(lid);

        // Fallback to Baileys native signalRepository / multiFileAuthState
        if (!pn && sock?.signalRepository?.lidMapping?.getPNForLID) {
            try {
                const resolvedPn = await sock.signalRepository.lidMapping.getPNForLID(lid);
                if (resolvedPn) {
                    pn = resolvedPn;
                    lidStorage.storeMapping(lid, resolvedPn); // cache it
                }
            } catch (err) {
                console.error('Error resolving LID from Baileys repository:', err);
            }
        }

        if (pn) {
            return {
                jid: pn,
                lid: lid,
                phoneNumber: pn.split('@')[0]
            };
        } else {
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
