<?php
class Search {
    public function tracks($query,$limit=0,$offset=0) {
        $cl = new SphinxClient();
        $cl->SetServer(SPHINX_HOST,SPHINX_PORT);
        $cl->SetMatchMode(SPH_MATCH_BOOLEAN);
        $cl->SetSortMode(SPH_SORT_RELEVANCE);
        if($limit) $cl->SetLimits($offset,$limit);
        $result = $cl->Query($query, 'dps-www');
        if ($result === false) throw new UserError("Query failed: " . $cl->GetLastError());
        else if ($cl->GetLastWarning()) throw new UserError("WARNING: " . $cl->GetLastWarning());

        $return = array();
        if (!empty($result["matches"])) {
            foreach ($result["matches"] as $id => $info) {
                $return[]= $id;
            }
        }
        
        return ((count($return) > 0)? $return : NULL);
    }
}

?>