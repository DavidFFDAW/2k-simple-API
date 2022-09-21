<?php
class FatherMiddleware {
    protected $hasError = false;

    public function __construct() { }

    protected function setError( $code, $message ) {
        $this->hasError = true;
        return ResponseJSON::error($code, $message);
    }
    
    public function hasError() {
        return $this->hasError;
    }
}