var express = require('express');
var app = express();
const WebSocket = require('ws');
var upload = require("multer")({ dest: '/home/shishcat1/uploadgram/express/uploads/' })
var uploaa2 = require("./wsw");
var useragent = require('express-useragent');
var { promisify } = require('util')
var fs = require("fs");
var unlinkAsync = promisify(fs.unlink)

app.use(useragent.express());
app.post('/api/upload',upload.single('upload'), (req, res) => {
    uploaa2(res,req,"upload",WebSocket,unlinkAsync);
});
app.get('/api/search', (req, res) => {
    uploaa2(res,req,"search",WebSocket);
});
app.use(express.urlencoded());
app.post('/api/search', (req, res) => {
    uploaa2(res,req,"search",WebSocket);
});
app.post('/f/:file', (req, res) => {
    uploaa2(res,req,"fileh",WebSocket);
});
app.get('/f/:file', (req, res) => {
    uploaa2(res,req,"fileh",WebSocket);
});
app.post('/api/fnr/:file', (req, res) => {
    uploaa2(res,req,"fnr",WebSocket);
});
app.get('/api/fnr/:file', (req, res) => {
    uploaa2(res,req,"fnr",WebSocket);
});
app.get('/api/delete/:deletes', (req, res) => {
    uploaa2(res,req,"del",WebSocket);
});
app.get('/api/ERGASTOLATORAPI', (req, res) => {
    uploaa2(res,req,"ERGASTOLATORAPI",WebSocket);
});
var server = app.listen(5432, '0.0.0.0')
server.setTimeout(3600000);
