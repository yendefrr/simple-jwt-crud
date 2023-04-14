<?php

namespace App\Controllers;

class Controller
{
    public array $params;
    public string|null $token;

    protected $config; 

    public function __construct()
    {
        $this->config = require_once __DIR__ . '/../../configs/main.php';
        $this->params = array_merge($_POST, $_GET);

        $authHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;

        if (isset($authHeader) && !empty($authHeader)) {
            $authParts = explode(' ', $authHeader);

            if (count($authParts) == 2 && $authParts[0] == 'Bearer') {
                $this->token = $authParts[1];
            } else {
                $this->response([
                    'error' => 'Invalid authorization header',
                ], 401);
            }
        } else {
            $this->token = null;
        }
    }

    public function response(array|string $data, $code = 200, array $headers = ['Content-Type' => 'application/json']): never
    {
        foreach ($headers as $header => $value) {
            header("$header: $value");
        }
        http_response_code($code);

        echo is_array($data) ? json_encode($data) : $data;

        exit;
    }

    public function render(string $template, array $data = [])
    {
        $templatePath = __DIR__ . '/../public/templates/' . $template . '.php';

        if (file_exists($templatePath)) {
            extract($data);

            require_once $templatePath;
        } else {
            $this->response([
                'error' => 'Template not found',
            ], 404);
        }
    }
}