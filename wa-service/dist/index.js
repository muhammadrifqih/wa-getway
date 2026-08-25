"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = __importDefault(require("express"));
const dotenv_1 = __importDefault(require("dotenv"));
const pino_1 = __importDefault(require("pino"));
const internal_1 = __importDefault(require("./routes/internal"));
dotenv_1.default.config();
const app = (0, express_1.default)();
const port = process.env.PORT || 3000;
const logger = (0, pino_1.default)();
app.use(express_1.default.json());
app.use('/internal', internal_1.default);
app.get('/', (req, res) => {
    res.send({ status: 'WA Service Engine Running' });
});
app.listen(port, () => {
    logger.info(`WA Service listening on port ${port}`);
});
