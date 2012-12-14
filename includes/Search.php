<?php
class Search {
    public function tracks($query,$fields="title,artist,album",$limit=0,$offset=0) {
        // make sure the caller hasn't used spaces
        $fields = str_replace(" ",",",$fields);

        // form a query string for v_audio_music
        $query_str = "SELECT id, count(*) OVER() AS full_count FROM v_audio_music WHERE dir = 2";

        // modge the field specifiers into tsquery syntax
        $fields_query = str_replace(","," || ' ' || ", $fields);
        
        // add the bit that actually searches
        $query_str .= " AND to_tsvector(".$fields_query.")::tsvector @@ plainto_tsquery('".$query."')::tsquery ORDER BY id DESC";

        // don't forget to limit/offset for pagination
        if($limit > 0) $query_str .= " LIMIT ".$limit;
        if($offset > 0) $query_str .= " OFFSET ".$offset;

        // QUERY MOFOS
        $result = DigiplayDB::query($query_str);
        if ($result === false) throw new UserError("Query failed");

        $results = array();
        if(pg_num_rows($result) > 0) {
            while($item = pg_fetch_assoc($result,NULL)) {
                $results[] = $item["id"];
                $total = $item["full_count"];
            }
            
        }

        $return = array(
            "results" => $results,
            "total" => $total);

        return ((count($results) > 0)? $return : NULL);
    }
}

?>