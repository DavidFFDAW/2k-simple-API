<?php 

class ReignsController {

    private function addTotalsToReign(Reigns $reigns, &$currentReigns) {
        foreach ($currentReigns as &$reign) {
            $totalCounters = $reigns->getTotalDaysAndReignsNumbers(
                $reign['wrestlerId'], 
                $reign['championshipId']
            );
            $reign['totalDays'] = $totalCounters['total_days'];
            $reign['totalReigns'] = $totalCounters['total_reigns'];
        }
    }


    public function getTotalCurrentReigns (Request $req) {
        $reigns = new Reigns();
        $currentReigns = $reigns->getCurrentReigns($req);
        $currentTagTeamReigns = $reigns->getCurrentTagTeamReigns($req);

        $this->addTotalsToReign($reigns, $currentReigns);
        $this->addTotalsToReign($reigns, $currentTagTeamReigns);

        $singlesAndTagReigns = array(
            'currentSingles' => $currentReigns,
            'currentTagTeams' => $currentTagTeamReigns
        );

        return ResponseJSON::success($singlesAndTagReigns, 'reigns');
    }


    public function getSeparatedReignsForWrestlerAndChampionship(Request $request) {
        $wrestlerID = $request->params->wrestler;
        $championshipID = $request->params->championship;

        if (!isset($wrestlerID) || !isset($championshipID)) {
            return ResponseJSON::error(400, '`wrestler` or `championship` params are required');
        }

        $reigns = new Reigns();
        $wrestlerChampionshipReigns = $reigns->getSeparatedReignsForWrestlerAndChampionship($wrestlerID, $championshipID);

        $wrestlerReign = array();
        $finalReigns = array();
        
        foreach ($wrestlerChampionshipReigns as $reign) {
            $finalReigns[] = array(
                'isCurrent' => $reign['isCurrent'],
                'days' => $reign['reignDays'],
                'start' => $reign['wonDate'],
                'end' => $reign['lostDate'],
            );
        }
        
        $wrestlerReign['championship'] = $wrestlerChampionshipReigns[0]['championship'];
        $wrestlerReign['championshipId'] = $wrestlerChampionshipReigns[0]['championshipId'];
        $wrestlerReign['wrestlerId'] = $wrestlerChampionshipReigns[0]['wrestlerId'];
        $wrestlerReign['championshipImage'] = $wrestlerChampionshipReigns[0]['championshipImage'];
        $wrestlerReign['brand'] = $wrestlerChampionshipReigns[0]['brand'];
        $wrestlerReign['wrestlerName'] = $wrestlerChampionshipReigns[0]['wrestlerName'];
        $wrestlerReign['wrestlerImage'] = $wrestlerChampionshipReigns[0]['wrestlerImage'];
        $wrestlerReign['reigns'] = $finalReigns;
        
        return ResponseJSON::success($wrestlerReign, 'data');
    }


    public function getAllChampionshipReigns(Request $req) {
        if (!isset($req->params->championship)) {
            return ResponseJSON::error(400, '`championship` param is required');
        }

        $reigns = new Reigns();
        $championshipReigns = $reigns->getAllChampionshipReigns($req->params->championship);

        return ResponseJSON::success($championshipReigns, 'reigns');
    }


    public function getAllWrestlerReigns (Request $r) {
        if (!isset($r->params->wrestler)) {
            return ResponseJSON::error(400, '`wrestler` param is required');
        }

        $wrestlerReign = array();
        $reigns = new Reigns();
        $wrestlerReigns = $reigns->getAllWrestlerReigns($r->params->wrestler);

        
        foreach ($wrestlerReigns as $reign) {
            $finalReigns[] = array(
                'championship' => $reign['championship'],
                'championshipId' => $reign['championshipId'],
                'championshipImage' => $reign['championshipImage'],
                'isCurrent' => $reign['isCurrent'],
                'brand' => $reign['brand'],
                'days' => $reign['reignDays'],
                'start' => $reign['wonDate'],
                'end' => $reign['lostDate'],
            );
        }
        
        $wrestlerReign['wrestlerId'] = $wrestlerReigns[0]['wrestlerId'];
        $wrestlerReign['wrestlerName'] = $wrestlerReigns[0]['wrestlerName'];
        $wrestlerReign['wrestlerImage'] = $wrestlerReigns[0]['wrestlerImage'];
        $wrestlerReign['reigns'] = $finalReigns;

        return ResponseJSON::success($wrestlerReign, 'data');
    }
}