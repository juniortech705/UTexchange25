<?php
require_once __DIR__ . '/BaseController.php';

class ErrorController extends BaseController
{
    public function notFound()
    {
        http_response_code(404);
        $this->render('errors/404');
    }

    public function forbidden()
    {
        http_response_code(403);
        $this->render('errors/403');
    }

    public function internalError()
    {
        http_response_code(500);
        $this->render('errors/500');
    }
}