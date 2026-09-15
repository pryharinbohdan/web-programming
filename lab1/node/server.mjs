import http from 'node:http';
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const PUBLIC_DIR = path.join(__dirname, 'public');
const PORT = 3001;

const server = http.createServer((req, res) => {
  const parsedUrl = new URL(req.url, `http://${req.headers.host || 'localhost'}`);
  let pathname = parsedUrl.pathname;
  if (pathname.endsWith('/') && pathname.length > 1) {
    pathname = pathname.slice(0, -1);
  }

  let filePath = '';
  let contentType = 'text/html; charset=utf-8';
  let statusCode = 200;

  if (pathname === '/' || pathname === '/index.html') {
    filePath = path.join(PUBLIC_DIR, 'index.html');
  } else if (pathname === '/about' || pathname === '/about.html') {
    filePath = path.join(PUBLIC_DIR, 'about.html');
  } else if (pathname === '/styles.css') {
    filePath = path.join(PUBLIC_DIR, 'styles.css');
    contentType = 'text/css; charset=utf-8';
  } else {
    filePath = path.join(PUBLIC_DIR, '404.html');
    statusCode = 404;
  }

  fs.readFile(filePath, (err, data) => {
    if (err) {
      fs.readFile(path.join(PUBLIC_DIR, '404.html'), (err404, data404) => {
        res.writeHead(404, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end(err404 ? '404 Not Found' : data404);
      });
      return;
    }
    res.writeHead(statusCode, { 'Content-Type': contentType });
    res.end(data);
  });
});

server.listen(PORT, () => {
  console.log(`Node.js server running at http://localhost:${PORT}/`);
});
