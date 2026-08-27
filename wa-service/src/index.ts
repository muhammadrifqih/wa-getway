import express from 'express';
import dotenv from 'dotenv';
import pino from 'pino';
import internalRoutes from './routes/internal';

dotenv.config();

const app = express();
const port = process.env.PORT || 3000;
const logger = pino();

app.use(express.json());

app.use('/internal', internalRoutes);



app.get('/', (req, res) => {
  res.send({ status: 'WA Service Engine Running' });
});

app.listen(port, () => {
  logger.info(`WA Service listening on port ${port}`);
});
