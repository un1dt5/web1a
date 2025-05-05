<?php

class Router
{
    private array $handlers;

    public function get(string $path, $handler): void
    {
        $this->addHandler('GET', $path, $handler);
    }
    public function post(string $path, $handler): void
    {
        $this->addHandler('POST', $path, $handler);
    }
    private function addHandler(string $method, string $path, $handler): void
    {
        $this->handlers[$method . $path] = [
            'path' => $path,
            'method' => $method,
            'handler' => $handler
        ];
    }
    public function run()
    {
        $callback = null;
        foreach ($this->handlers as $handler) {
            if ($handler['path'] === parse_url($_SERVER['REQUEST_URI'])['path'] && $handler['method'] === $_SERVER['REQUEST_METHOD']) {
                http_response_code(200);
                $callback = $handler['handler'];
            }
        }
        if (!$callback) {
            $callback = function () {
                http_response_code(404);
                header('Content-Type: text/plain');
                die('Cannot ' . $_SERVER['REQUEST_METHOD'] . ' ' . parse_url($_SERVER['REQUEST_URI'])['path']);
            };
        }
        $params =  new \StdClass;
        $params->query = $_GET;
        $params->body = $_POST;
        $params->headers = apache_request_headers();
        call_user_func_array($callback, array($params));
    }
}
