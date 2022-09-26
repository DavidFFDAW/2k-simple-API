<?php

class TeamController {

    public function getTeamNames (Request $rq) {
        $teams = new Team();
        $teamNames = $teams->getTeams();

        return ResponseJSON::success($teamNames, 'teams');
    }

    public function getAllTeams (Request $request) {
        $teams = new Team();
        $wrestlerTeam = new WrestlerTeam();
        $totalTeams = $teams->getTeams();
        
        foreach ($totalTeams as &$team) {
            $team['members'] = $wrestlerTeam->getTeamMembersFromTeamID($team['id']);         
        }

        return ResponseJSON::success($totalTeams, 'teams');
    }
}