<?php
class Search {
    public function tracks($query,$fields="title,artist,album,keyword",$limit=0,$offset=0,$group=NULL) {
        $cl = new SphinxClient();
        $cl->SetMatchMode(SPH_MATCH_EXTENDED);
        $cl->SetSortMode(SPH_SORT_RELEVANCE);
        if($limit) $cl->SetLimits($offset,$limit);
        if($group) $cl->SetGroupBy("attr_".$group, SPH_GROUPBY_ATTR);

        $fields = str_replace(" ",",",$fields);
        $query_str = "@@relaxed @(".$fields.") ".$query;

        $result = $cl->Query($query_str);
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