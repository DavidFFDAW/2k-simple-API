<?php
class Reigns extends DatabaseModel {
    public function __construct() {
        parent::__construct();
        $this->conn = $this->getConnection();
        $this->json = new ResponseJSON();
    }

    public function getTagTeamsChampions (Request $request) {
        $tagTeamReigns = $this->conn->query("SELECT  c.name as championship_name, c.image as ch_img, c.tag as is_tag, r.id as reign_id, r.days as reign_days, w.brand as brand, t.name as team_name, w.name as name, w.image_name as image, w.overall as overall
        FROM championship_reigns r INNER JOIN championship c ON r.championship_id = c.id INNER JOIN teams t ON r.wrestler_id = t.id INNER JOIN wrestler_team wt ON wt.team_id = t.id INNER JOIN wrestler w ON w.id = wt.wrestler_id
        WHERE c.tag = 1 AND c.active = 1 AND r.current = 1");
        
        $finalTeamInfo = [];
        foreach ($tagTeamReigns as $reign) {
            $finalTeamInfo['championship_name'] = $reign['championship_name'];
            $finalTeamInfo['ch_img'] = $reign['ch_img'];
            $finalTeamInfo['reign_id'] = $reign['reign_id'];
            $finalTeamInfo['reign_days'] = $reign['reign_days'];
            $finalTeamInfo['brand'] = $reign['brand'];
            $finalTeamInfo['name'] = $reign['team_name'];
            $finalTeamInfo['is_tag'] = $reign['is_tag'];
            
            $finalTeamInfo['wrestlers'][] = [
                'name' => $reign['name'],
                'image' => $reign['image'],
                'overall' => $reign['overall']
            ];
        }
       
        return $finalTeamInfo;      
        // return $tagTeamReigns->fetch_all(MYSQLI_ASSOC);
    }

    public function getCurrentReigns (Request $request) {
        $sql = "SELECT c.name as championship, c.id as championshipId, w.id as wrestlerId, c.image as championshipImage, r.id as reignId, r.days as reignDays, c.brand as brand, w.name as wrestlerName, w.image_name as wrestlerImage, w.overall as overall
        FROM championship_reigns r INNER JOIN wrestler w 
        ON r.wrestler_id = w.id INNER JOIN championship c ON r.championship_id = c.id
        WHERE r.current = 1 AND w.status != 'released' AND c.active = 1 AND c.tag = 0 ORDER BY days DESC";

        $row = $this->conn->query($sql);
        return $row->fetch_all(MYSQLI_ASSOC);
    }

    public function getCurrentTagTeamReigns (Request $request) {
        $sql = "SELECT c.name as championship, c.id as championshipId, w.id as wrestlerId, c.image as championshipImage, r.id as reignId, r.days as reignDays, c.brand as brand, w.name as wrestlerName, w.average as overall
        FROM championship_reigns r INNER JOIN teams w 
        ON r.wrestler_id = w.id INNER JOIN championship c ON r.championship_id = c.id
        WHERE r.current = 1 AND c.active = 1 AND c.tag = 1 ORDER BY days DESC";

        $row = $this->conn->query($sql);
        return $row->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalDaysAndReignsNumbers (int $wrestlerID, int $championshipID) {
        $sql = "SELECT COUNT(*) as total_reigns, SUM(days) as total_days FROM championship_reigns WHERE wrestler_id = $wrestlerID AND championship_id = $championshipID LIMIT 1";
        $row = $this->conn->query($sql);
        return $row->fetch_assoc();
    }
}