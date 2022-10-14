<?php 

class ReignsController {
    public function getTotalCurrentReigns (Request $req) {
        $reigns = new Reigns();
        $currentReigns = $reigns->getCurrentReigns($req);
        // $currentTagTeamReigns = $reigns->getCurrentTagTeamReigns($req);

        // $finalReigns = array();
        foreach ($currentReigns as &$reign) {
            $totalCounters = $reigns->getTotalDaysAndReignsNumbers(
                $reign['wrestlerId'], 
                $reign['championshipId']
            );
            $reign['total_days'] = $totalCounters['total_days'];
            $reign['total_reigns'] = $totalCounters['total_reigns'];
        }

        return $currentReigns;
    }
}