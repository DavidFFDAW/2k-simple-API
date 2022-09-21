<?php
class Reigns extends ModelModule {
    public function __construct() {
        parent::__construct();
        $this->conn = $this->getConnection();
        $this->json = new ResponseJSON();
    }

    public function getReigns (Request $request) {
        $sql = "SELECT c.name as championship_name, c.image as ch_img, r.id as reign_id, r.days as reign_days, w.brand as brand, w.name as name, w.image_name as image, w.overall as overall
        FROM championship_reigns r INNER JOIN wrestler w 
        ON r.wrestler_id = w.id INNER JOIN championship c ON r.championship_id = c.id
        WHERE r.current = 1 AND w.status != 'released'";

        $row = $this->conn->query($sql);
        $reigns = $row->fetch_all(MYSQLI_ASSOC);
        
        return $this->json->setResponseAndReturn(200, 'Succesful', 'OK', $reigns);
    }
}