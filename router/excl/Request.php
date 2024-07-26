<?php
class Request
{
    public $request;
    public $method;
    public $endpoint;
    public $params;
    public $body;
    public $headers;
    public $files;
    public $cookies;
    public $request_uri;

    public function __construct()
    {
        $getContent = file_get_contents('php://input');
        $hasPOST = isset($_POST) && !empty($_POST);

        $this->request = $_REQUEST;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->request_uri = $_SERVER['REQUEST_URI'];
        $this->params = (object) $_GET;
        $this->body = $hasPOST ? (object) $_POST : (object) json_decode($getContent);
        $this->headers = getallheaders();
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
    }

    public function bearerToken()
    {
        $token = $this->headers['Authorization'];
        $token = str_replace('Bearer', '', $token);
        return trim($token);
    }
}
