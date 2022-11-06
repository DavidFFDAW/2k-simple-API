<?php

class TeamController {

    public function getTeam (Request $rq) {
        if (isset($rq->params->id)) {
            return $this->getTeamDetailsByID($rq);
        }
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

        // dd(Brand::find((int) 1));
        $team = new Team();
        $wrestlerTeam = new WrestlerTeam();
        $teamDetails = $team->getTeamDetailsByID($teamID);
        $teamDetails['members'] = $wrestlerTeam->getTeamMembersFromTeamID($teamID);
        $teamDetails['count'] = count($teamDetails['members']);
        $teamDetails['brand'] = Brand::find((int) $teamDetails['brand']);

        return ResponseJSON::success($teamDetails, 'team');
    }
}