<?php
class Search {
    public function tracks($query,$indexes="title artist album keyword",$limit=0,$offset=0) {
        $cl = new SphinxClient();
        $cl->SetMatchMode(SPH_MATCH_BOOLEAN);
        $cl->SetSortMode(SPH_SORT_RELEVANCE);
        if($limit) $cl->SetLimits($offset,$limit);

        foreach(explode(" ", $indexes) as $index) {
            $index_str .= $index." ".$index."-delta ";
        }

        $result = $cl->Query($query, $index_str);
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
        $return = shell_exec("/usr/bin/indexer --quiet --rotate title-delta artist-delta album-delta keyword-delta");
        return ($return)? false:true;
    }
}

?>