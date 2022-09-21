<?php
class ResponseJSON {
    private $response = [
        'status' => 'error',
        'message' => 'Error desconocido',
        'code' => 500,
    ];

    public function __construct() {
    
    }

    private function setAPIResponseStatus($code, $message) {
        http_response_code($code);
        $protocol = $_SERVER['SERVER_PROTOCOL'];
        header("$protocol $code $message");
    }

    public function setCode(int $code) {
        $this->response['code'] = $code;
        http_response_code($code);
    }

    public function setMessage(string $message) {
        $this->response['message'] = $message;
    }

    public function setStatus(string $status) {
        $this->response['status'] = $status;
    }

    public function setData($data) {
        $this->response['data'] = $data;
    }

    public function setResponseAndReturn ($code, $message, $status, $data, $customDataName = false) {
        if ($customDataName) $this->response[$customDataName] = $data;
        else $this->response['data'] = $data;
        
        $this->response['code'] = $code;
        $this->response['message'] = $message;
        $this->response['status'] = $status;
        
        $this->setAPIResponseStatus($code, $message);
        return $this->getJSONResponse();
    }

    // public function overrideDataField(array $newField) {
    //     unset($this->response['data']);

    //     $key = isset($newField['key']) ? $newField['key'] : $newField[0];
    //     $value = isset($newField['value']) ? $newField['value'] : $newField[1];

    //     $this->response[$key] = $value;
    // }

    public static function error(int $code, string $message) {
        return json_encode([
            'status' => 'error',
            'message' => $message,
            'code' => $code,
        ], JSON_PRETTY_PRINT);
        self::setAPIResponseStatus($code, $message);
    }

    public function getResponse() {
        return $this->response;
    }

    public function getJSONResponse() {
        header('Content-Type: application/json');
        return json_encode($this->response);
    }
}