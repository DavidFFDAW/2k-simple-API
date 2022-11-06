<?php
        
class Brand extends DatabaseModel implements ModelInterface {

    private static $tableN = "brands";
    private static $neededField = [
        "name",
        // ...,
    ];

    public function __construct($brand) {
        $this->id = $brand["id"];

    }

    public static function getRequiredFields() {
        return self::$neededField;
    }

    public static function findAll() { }

    public static function find(int $id) { 
        $sql = "SELECT * FROM ".self::$tableN." WHERE id = ? LIMIT 1";
        $conn = DatabaseModel::getInstance();
        $stmt = $conn->getConnection()->prepare($sql);
        $stmt->bind_param('i', $id);
        return $conn->getStmtAssocOrFalse($stmt);
    }

    public static function create($data): bool {return true; }

    public function update($data): bool { return true; }

    public function delete(): bool { return true; }  

}              
        