<?php

use LDAP\Result;

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
            $team['count'] = count($team['members']);         
        }

        return ResponseJSON::success($totalTeams, 'teams');
    }

    public function getTeamDetailsByID(Request $req) {
        $teamID = $req->params->id;
        if (!isset($teamID)) {
            return ResponseJSON::error(401, 'No team ID provided');
        }

        // $team = new Team();
        $wrestlerTeam = new WrestlerTeam();
        // $teamDetails = $team->getTeamDetailsByID($teamID);
        $teamDetails['members'] = $wrestlerTeam->getTeamMembersFromTeamID($teamID);
        $teamDetails['count'] = count($teamDetails['members']);

        return ResponseJSON::success($teamDetails, 'team');
    }
}