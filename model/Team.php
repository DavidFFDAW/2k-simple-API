<?php
class Team extends DatabaseModel {
    public function __construct() {
        parent::__construct();
        $this->conn = $this->getConnection();
    }

    public function getTeams () {
        $sql = "SELECT * FROM teams ORDER BY name ASC";

        $row = $this->conn->query($sql);
        $teams = $row->fetch_all(MYSQLI_ASSOC);
        
        return $teams;
    }

    public function getTeamDetailsByID ($teamID) {
        $sql = "SELECT * FROM teams WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $teamID);
        $stmt->execute();
        $result = $stmt->get_result();
        $team = $result->fetch_assoc();

        return $team;
    }

    public function createTeam ($teamName, $brandID, $average): int {
        $sql = "INSERT INTO teams (name, brand, average) VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sii', $teamName, $brandID, $average);
        $stmt->execute();
        $result = $stmt->get_result();
        $result->fetch_assoc();
        
        return $this->conn->insert_id;
    }
}