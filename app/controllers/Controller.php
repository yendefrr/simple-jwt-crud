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

    public function response(array $data, $code = 200)
    {
        header('Content-Type: application/json');
        http_response_code($code);

        echo json_encode($data);

        exit;
    }
}