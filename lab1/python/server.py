from http.server import HTTPServer, BaseHTTPRequestHandler
from pathlib import Path
from urllib.parse import urlparse

PORT = 3002
PUBLIC_DIR = Path(__file__).parent / "public"

class LabHandler(BaseHTTPRequestHandler):
    def do_GET(self):
        # розбір рядка запиту на компоненти url
        parsed = urlparse(self.path)
        # зі всього url запиту отримуємо саме шлях
        path = parsed.path

        # умова len(path) > 1, щоб не обрізати корінь
        if path.endswith("/") and len(path) > 1:
            # обрізання всіх "/" з кінця
            path = path.rstrip("/")

        file_path = None
        content_type = "text/html; charset=utf-8"
        status_code = 200

        if path in ("/", "/index.html"):
            file_path = PUBLIC_DIR / "index.html"
        elif path in ("/about", "/about.html"):
            file_path = PUBLIC_DIR / "about.html"
        elif path == "/styles.css":
            file_path = PUBLIC_DIR / "styles.css"
            content_type = "text/css; charset=utf-8"
        else:
            file_path = PUBLIC_DIR / "404.html"
            status_code = 404

        if not file_path.exists():
            file_path = PUBLIC_DIR / "404.html"
            status_code = 404

        try:
            content = file_path.read_bytes()
            self.send_response(status_code)
            self.send_header("Content-Type", content_type)
            self.end_headers()
            self.wfile.write(content)
        except Exception as e:
            self.send_response(500)
            self.send_header("Content-Type", "text/plain; charset=utf-8")
            self.end_headers()
            self.wfile.write(f"Server error: {e}".encode("utf-8"))

    # перевизначаємо дефолтний логер класу BaseHTTPRequestHandler
    def log_message(self, format, *args):
        print(f"[{self.address_string()}] {format % args}")
        # [127.0.0.1] "GET /about HTTP/1.1" 200 -

# виконуємо якщо файл запущено напряму, а не як модуль
if __name__ == "__main__":
    server = HTTPServer(("127.0.0.1", PORT), LabHandler)
    print(f"Python server running at http://localhost:{PORT}/")
    try:
        server.serve_forever()
    except KeyboardInterrupt:
        pass
