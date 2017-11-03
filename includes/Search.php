<?php
class Search {
    public static function tracks($query,$indexes=array("title","artist","album"),$limit=0,$offset=0) {
        // form a query string for v_audio_music
        $query_str = "id, count(*) OVER() AS full_count FROM v_audio_music WHERE dir = 2";
        
        // add the bit that actually searches
        $query_str .= " AND to_tsvector(".implode(" || ' ' || ", $indexes).")::tsvector @@ plainto_tsquery(:query)::tsquery ORDER BY id DESC";

        // don't forget to limit/offset for pagination
        if($limit > 0) $query_str .= " LIMIT ".$limit;
        if($offset > 0) $query_str .= " OFFSET ".$offset;

        // QUERY MOFOS
        $result = DigiplayDB::select($query_str, NULL, true, array(":query" => $query));
        if ($result === false) throw new UserError("Query failed: $query_str");

        $results = array();
        $total = 0;
        if(count($result) > 0) {
            foreach($result as $res) {
                $results[] = $res["id"];
                $total = $res["full_count"];
            }
        }

        $return = array(
            "results" => $results,
            "total" => $total);

        return ((count($results) > 0)? $return : NULL);
    }

    public static function artists($query, $limit=0, $offset=0) {
        $query_str = "id, count(*) OVER() AS full_count FROM artists WHERE to_tsvector(name)::tsvector @@ plainto_tsquery(:query)::tsquery ORDER BY id DESC";

        if($limit > 0) $query_str .= " LIMIT ".$limit;
        if($offset > 0) $query_str .= " OFFSET ".$offset;

        $result = DigiplayDB::select($query_str, NULL, true, array(":query" => $query));
        if ($result === false) throw new UserError("Query failed");

        $results = array();
        $total = 0;
        if(count($result) > 0) {
            foreach($result as $res) {
                $results[] = $res["id"];
                $total = $res["full_count"];
            }
        }

        $return = array("results" => $results, "total" => $total);
        return ((count($results) > 0)? $return : NULL);
    }

    public static function albums($query, $limit=0, $offset=0) {
        $query_str = "id, count(*) OVER() AS full_count FROM albums WHERE to_tsvector(name)::tsvector @@ plainto_tsquery(:query)::tsquery ORDER BY id DESC";

        if($limit > 0) $query_str .= " LIMIT ".$limit;
        if($offset > 0) $query_str .= " OFFSET ".$offset;

        $result = DigiplayDB::select($query_str, NULL, true, array(":query" => $query));
        if ($result === false) throw new UserError("Query failed");

        $results = array();
        $total = 0;
        if(count($result) > 0) {
            foreach($result as $res) {
                $results[] = $res["id"];
                $total = $res["full_count"];
            }
        }

        $return = array("results" => $results, "total" => $total);
        return ((count($results) > 0)? $return : NULL);
    }

    public static function jingles($query, $limit=0, $offset=0) {
        $query_str = "id, count(*) OVER() AS full_count FROM v_audio_jingles_new WHERE to_tsvector(title)::tsvector @@ plainto_tsquery(:query)::tsquery ORDER BY id DESC";

        if($limit > 0) $query_str .= " LIMIT ".$limit;
        if($offset > 0) $query_str .= " OFFSET ".$offset;

        $result = DigiplayDB::select($query_str, NULL, true, array(":query" => $query));
        if ($result === false) throw new UserError("Query failed: $query_str");

        $results = array();
        $total = 0;
        if(count($result) > 0) {
            foreach($result as $res) {
                $results[] = $res["id"];
                $total = $res["full_count"];
            }
        }

        $return = array(
            "results" => $results,
            "total" => $total);

        return ((count($results) > 0)? $return : NULL);
    }


    public static function prerecords($query, $limit=0, $offset=0) {
        $query_str = "id, count(*) OVER() AS full_count FROM v_audio_prerec WHERE to_tsvector(title)::tsvector @@ plainto_tsquery(:query)::tsquery ORDER BY id DESC";

        if($limit > 0) $query_str .= " LIMIT ".$limit;
        if($offset > 0) $query_str .= " OFFSET ".$offset;

        $result = DigiplayDB::select($query_str, NULL, true, array(":query" => $query));
        if ($result === false) throw new UserError("Query failed: $query_str");

        $results = array();
        $total = 0;
        if(count($result) > 0) {
            foreach($result as $res) {
                $results[] = $res["id"];
                $total = $res["full_count"];
            }
        }

        $return = array(
            "results" => $results,
            "total" => $total);

        return ((count($results) > 0)? $return : NULL);
    }

}

?>
