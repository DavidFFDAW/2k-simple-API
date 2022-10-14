<?php

class wrestler extends DatabaseModel {

    public function __construct() {
        parent::__construct();
        $this->conn = $this->getConnection();
    }


    // public static function getWrestlerById (Request $request, $id) {
    //     $sql = "SELECT * FROM wrestler WHERE id = $id LIMIT 1";
    //     $row = $this->conn->query($sql);
    //     return $row->fetch_all(MYSQLI_ASSOC);
    // }


    public function getNotReleasedWrestlers (Request $request) {
        $sql = "SELECT w.* FROM wrestler w WHERE w.status != 'released'";
        $row = $this->conn->query($sql);
        $wrestlers = $row->fetch_all(MYSQLI_ASSOC);

        return ResponseJSON::success($wrestlers);
    }
}