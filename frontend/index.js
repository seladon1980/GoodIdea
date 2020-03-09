import express from 'express'
import template from './src/template'

let app = express();

app.use('/dist', express.static('../dist'));

app.use('/static', express.static('src'));

app.get("*", (req, res) => {
    res.send(template("Blog"));
});

app.listen(process.env.APP_FRONTEND_PORT);