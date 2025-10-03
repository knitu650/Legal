<?php
namespace App\Core;

class Response {
    protected $statusCode = 200;
    protected $headers = [];
    protected $content = '';

    public function setStatusCode($code) {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function json($data) {
        $this->setHeader('Content-Type', 'application/json');
        $this->content = json_encode($data);
        return $this;
    }

    public function redirect($url) {
        $this->setHeader('Location', $url);
        $this->setStatusCode(302);
        return $this;
    }

    public function view($view, $data = []) {
        ob_start();
        extract($data);
        require_once __DIR__ . '/../../resources/views/' . str_replace('.', '/', $view) . '.php';
        $this->content = ob_get_clean();
        return $this;
    }

    public function send($content = null) {
        if ($content !== null) {
            $this->setContent($content);
        }

        // Send status code
        http_response_code($this->statusCode);

        // Send headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Send content
        echo $this->content;
    }

    public function download($file, $name = null) {
        if (!file_exists($file)) {
            throw new \Exception("File not found");
        }

        $name = $name ?? basename($file);

        $this->setHeader('Content-Type', mime_content_type($file));
        $this->setHeader('Content-Disposition', 'attachment; filename="' . $name . '"');
        $this->setHeader('Content-Length', filesize($file));

        readfile($file);
        exit;
    }

    public function notFound($message = 'Page not found') {
        return $this->setStatusCode(404)->view('errors.404', ['message' => $message]);
    }

    public function forbidden($message = 'Forbidden') {
        return $this->setStatusCode(403)->view('errors.403', ['message' => $message]);
    }

    public function error($message = 'Internal server error') {
        return $this->setStatusCode(500)->view('errors.500', ['message' => $message]);
    }

    public function back() {
        return $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    public function withSuccess($message) {
        $_SESSION['success'] = $message;
        return $this;
    }

    public function withError($message) {
        $_SESSION['error'] = $message;
        return $this;
    }
}