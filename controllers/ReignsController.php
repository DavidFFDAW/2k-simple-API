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
}