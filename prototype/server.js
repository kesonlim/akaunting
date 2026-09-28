const http = require('http');
const fs = require('fs');
const path = require('path');

const PORT = 3000;

const server = http.createServer((req, res) => {
    let filePath = path.join(__dirname, 'public', req.url === '/' ? 'index.html' : req.url);
    
    fs.readFile(filePath, (err, content) => {
        if (err) {
            res.writeHead(404);
            res.end('Not Found');
        } else {
            let contentType = 'text/html';
            if (filePath.endsWith('.js')) contentType = 'text/javascript';
            if (filePath.endsWith('.css')) contentType = 'text/css';
            if (filePath.endsWith('.json')) contentType = 'application/json';
            
            res.writeHead(200, { 'Content-Type': contentType });
            res.end(content, 'utf-8');
        }
    });
});

server.listen(PORT, () => {
    console.log(`StraitsLedger Prototype running at http://localhost:${PORT}`);
});
