"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = require("express");
const SessionManager_1 = require("../services/SessionManager");
const router = (0, express_1.Router)();
// Middleware for internal authentication
router.use((req, res, next) => {
    const serviceKey = req.header('X-SERVICE-KEY');
    if (serviceKey !== process.env.WA_SERVICE_KEY) {
        return res.status(401).json({ error: 'Unauthorized' });
    }
    next();
});
router.post('/sessions/create', async (req, res) => {
    const { sessionId } = req.body;
    if (!sessionId)
        return res.status(400).json({ error: 'sessionId required' });
    await SessionManager_1.sessionManager.createSession(sessionId);
    res.json({ success: true, message: 'Session created/initializing' });
});
router.post('/sessions/:sessionId/logout', async (req, res) => {
    const { sessionId } = req.params;
    await SessionManager_1.sessionManager.logoutSession(sessionId);
    res.json({ success: true, message: 'Session logged out' });
});
router.delete('/sessions/:sessionId', async (req, res) => {
    const { sessionId } = req.params;
    await SessionManager_1.sessionManager.removeSession(sessionId);
    res.json({ success: true, message: 'Session removed' });
});
router.get('/sessions/:sessionId/status', (req, res) => {
    const { sessionId } = req.params;
    const status = SessionManager_1.sessionManager.getStatus(sessionId);
    res.json(status);
});
router.get('/sessions/:sessionId/qr', (req, res) => {
    const { sessionId } = req.params;
    const qr = SessionManager_1.sessionManager.getQr(sessionId);
    res.json(qr);
});
router.post('/sessions/:sessionId/send', async (req, res) => {
    const { sessionId } = req.params;
    const { target, message, media_url, media_name, media_mimetype } = req.body;
    if (!target || !message) {
        return res.status(400).json({ error: 'target and message required' });
    }
    try {
        const result = await SessionManager_1.sessionManager.sendMessage(sessionId, target, message, {
            url: media_url,
            name: media_name,
            mimetype: media_mimetype
        });
        res.json({ success: true, result });
    }
    catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});
exports.default = router;
