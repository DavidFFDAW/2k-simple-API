<?php

class wrestler extends DatabaseModel {
    public function __construct() {
        parent::__construct();
        $this->conn = $this->getConnection();
    }

    public function getNotReleasedWrestlers (Request $request) {
        $sql = "SELECT w.* FROM wrestler w WHERE w.status != 'released'";
        $row = $this->conn->query($sql);
        $wrestlers = $row->fetch_all(MYSQLI_ASSOC);

        return $wrestlers;
    }
}