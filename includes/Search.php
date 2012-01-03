<?php
class Search {
    public function tracks($query,$limit=0,$offset=0) {
        $cl = new SphinxClient();
        $cl->SetMatchMode(SPH_MATCH_BOOLEAN);
        $cl->SetSortMode(SPH_SORT_RELEVANCE);
        if($limit) $cl->SetLimits($offset,$limit);
        $result = $cl->Query($query);
        if ($result === false) throw new UserError("Query failed: " . $cl->GetLastError());
        else if ($cl->GetLastWarning()) throw new UserError("WARNING: " . $cl->GetLastWarning());

        $results = array();
        if (!empty($result["matches"])) {
            foreach ($result["matches"] as $id => $info) {
                $results[]= $id;
            }
        }
        $return = array(
            "results" => $results,
            "total" => $result["total"],
            "time" => $result["time"]);
        return ((count($results) > 0)? $return : NULL);
    }

    public function update_index() {
        $return = shell_exec("/usr/bin/indexer --quiet --rotate delta");
        return ($return)? false:true;
    }
}

?>