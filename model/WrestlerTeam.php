<?php
class WrestlerTeam extends DatabaseModel {
    public function __construct() {
        parent::__construct();
        $this->conn = $this->getConnection();
    }

    public function getTeamMembersFromTeamID (int $teamID) {
        $sql = "SELECT w.id, w.name, w.brand, w.status, w.sex FROM wrestler_team wt INNER JOIN wrestler w ON w.id = wt.wrestler_id WHERE wt.team_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $teamID);
        
        $totalMembers = $this->getStmtAssocArrayOrFalse($stmt);

        return $totalMembers;
    }
}