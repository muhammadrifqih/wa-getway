import { Router } from 'express';
import { sessionManager } from '../services/SessionManager';

const router = Router();

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
  if (!sessionId) return res.status(400).json({ error: 'sessionId required' });
  
  await sessionManager.createSession(sessionId);
  res.json({ success: true, message: 'Session created/initializing' });
});

router.post('/sessions/:sessionId/logout', async (req, res) => {
  const { sessionId } = req.params;
  await sessionManager.logoutSession(sessionId);
  res.json({ success: true, message: 'Session logged out' });
});

router.delete('/sessions/:sessionId', async (req, res) => {
  const { sessionId } = req.params;
  await sessionManager.removeSession(sessionId);
  res.json({ success: true, message: 'Session removed' });
});

router.get('/sessions/:sessionId/status', (req, res) => {
  const { sessionId } = req.params;
  const status = sessionManager.getStatus(sessionId);
  res.json(status);
});

router.get('/sessions/:sessionId/qr', (req, res) => {
  const { sessionId } = req.params;
  const qr = sessionManager.getQr(sessionId);
  res.json(qr);
});

router.post('/sessions/:sessionId/send', async (req, res) => {
  const { sessionId } = req.params;
  const { target, message, media_url, media_name, media_mimetype, metadata } = req.body;
  
  if (!target || (!message && !metadata)) {
    return res.status(400).json({ error: 'target and message/metadata required' });
  }

  try {
    const result = await sessionManager.sendMessage(sessionId, target, message || '', {
      url: media_url,
      name: media_name,
      mimetype: media_mimetype
    }, metadata);
    res.json({ success: true, result });
  } catch (error: any) {
    res.status(500).json({ success: false, error: error.message });
  }
});

export default router;
