<?php
class Search {
    public function title($query,$limit=0,$offset=0) {
        $type = AudioTypes::get("music");
        $sql = "SELECT id FROM audio WHERE type = ".$type->get_id()." AND to_tsvector('english', title) @@ plainto_tsquery('".pg_escape_string($query)."') ".(($limit > 0)? "LIMIT ".$limit : "")." OFFSET ".$offset.";";
        $results = DigiplayDB::query($sql);
        $return = array();

        while ($result = pg_fetch_array($results))
            $return[] = $result[0];
        
        return ((count($return) > 0)? $return : NULL);
    }
}

?>