<?php
        
class NewsReport extends DatabaseModel {

    public function __construct() {
        parent::__construct();
        $this->conn = $this->getConnection();
    }
}