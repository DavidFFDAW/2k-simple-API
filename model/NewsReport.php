<?php
        
class NewsReport extends DatabaseModel implements ModelInterface {

    // private $table = 'news';
    private static $tableN = 'news';
    private static $neededField = [
        'title',
        'content',
        'admin_id',
        'image',
        'visible',
        'category',
    ];

    public function __construct($report) {
        $this->id = $report['id'];
        $this->title = $report['title'];
        $this->content = $report['content'];
        $this->image = $report['image'];
        $this->created = $report['created_at'];
        $this->updated = $report['updated_at'];
        $this->exceptr = $report['exceptr'];
        $this->visible = $report['visible'];
        $this->category = $report['category'];
        $this->admin_id = $report['admin_id']; // TODO: add relation to Admin
    }

    public static function getRequiredFields() {
        return self::$neededField;
    }

    public static function findAll() {
        $sql = "SELECT * FROM ".self::$tableN." ORDER BY created_at DESC";
        $conn = DatabaseModel::getInstance();
        $stmt = $conn->getConnection()->prepare($sql);
        return $conn->getStmtAssocArrayOrFalse($stmt);
    }

    public static function find(int $id) {
        $sql = "SELECT * FROM ".self::$tableN." WHERE id = ? LIMIT 1";
        $conn = DatabaseModel::getInstance();
        $stmt = $conn->getConnection()->prepare($sql);
        $stmt->bind_param('i', $id);
        return $conn->getStmtAssocOrFalse($stmt);
    }

    public static function create($data): bool {return true; }

    public function update($data): bool { return true;}
    public function delete(): bool { return true;}
}