<?php
class WrestlerTeam extends DatabaseModel {
    public function __construct() {
        parent::__construct();
        $this->conn = $this->getConnection();
    }

    public function getTeamMembersFromTeamID (int $teamID) {
        $sql = "SELECT w.id, w.name, w.image_name, w.brand, w.status, w.sex FROM wrestler_team wt INNER JOIN wrestler w ON w.id = wt.wrestler_id WHERE wt.team_id = ? ORDER BY w.name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $teamID);
        
        $totalMembers = $this->getStmtAssocArrayOrFalse($stmt);

        return $totalMembers;
    }

    public function createTeamMembers (int $teamID, array $members): bool {
        $tmp = [];
        $sql = "INSERT INTO wrestler_team (team_id, wrestler_id) VALUES ";
        
        foreach ($members as $member) {
            $tmp[] = "($teamID, $member)";
        }

        $sql .= implode(', ', $tmp);
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $result->fetch_assoc();
        $inserted = $this->conn->insert_id;
        
        return $inserted;
    }
}