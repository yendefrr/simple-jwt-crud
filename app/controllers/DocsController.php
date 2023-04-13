<?php
// Swagger controller

namespace App\Controllers;

use OpenApi\Generator;

/**
 * @OA\Info(title="JWT CRUD Service", version="1.0")
 */
class DocsController extends Controller
{
    public function index()
    {
        $this->render('swagger');
    }

    public function json()
    {
        $openapi = Generator::scan([$_SERVER['DOCUMENT_ROOT'] . '/../controllers']);
        
        $this->response($openapi->toJson());
    }
}