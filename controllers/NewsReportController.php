<?php
class NewsReportController {
    
    public function getReports (Request $req) {
        if (isset($req->params['id'])) {
            return $this->getReportByID($req);
        }

        $reports = NewsReport::findAll();
        return ResponseJSON::success($reports, 'news');
    }

    public function getReportByID (Request $req) {
        $report = NewsReport::find($req->params['id']);
        if (!$report) return ResponseJSON::error(404, 'Report not found');

        return ResponseJSON::success($report, 'news');
    }

    public function createNewReport(Request $req) {
        $requiredData = NewsReport::getRequiredFields();
        $data = array_keys((array) $req->body);
        $diff = array_diff($requiredData, $data);
        if (count($diff) > 0) return ResponseJSON::error(400, 'Missing required fields: ' . implode(', ', $diff));
        return ResponseJSON::success(NewsReport::create($req->body), 'news');
    }
}