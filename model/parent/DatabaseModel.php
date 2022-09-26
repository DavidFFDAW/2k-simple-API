<?php

class DatabaseModel {

    private $db_host = '';
    private $db_user = '';
    private $db_pwsd = '';
    private $db_name = '';
    private $conn = null;
    
    public function __construct() {
        $env = EnvReader::getInstance(MAIN_PATH);
        $this->db_host = $env->get('DB_HOST');
        $this->db_user = $env->get('DB_USER');
        $this->db_pwsd = $env->get('DB_PWSD');
        $this->db_name = $env->get('DB_NAME');
        
        $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_pwsd, $this->db_name)
            or die("Connection failed");     
        $this->conn->set_charset('utf8');
    }

    protected function getConnection () {
        return $this->conn;
    }

    protected function getStmtAssocArrayOrFalse(mysqli_stmt $stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return false;
    }

    protected function getStmtAssocOrFalse(mysqli_stmt $stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }
}