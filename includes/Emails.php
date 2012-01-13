<?php
class Emails {
    public static function get($starttime = NULL, $endtime = NULL, $sort
            = NULL, $limit = NULL, $offset = NULL) {
        $sql = "SELECT * FROM email";
        
        if ( !is_null($starttime) && !is_null($endtime) &&
                is_numeric($starttime) && is_numeric($endtime) ) {
           $sql .= " WHERE datetime BETWEEN ". $starttime ." AND ". $endtime;
        }
		
		$sql .= " ORDER BY datetime ";
        if ( !is_null($sort) ) {
            $sql .= $sort;
        } else {
            $sql .= "DESC";
        }

        if ( !is_null($limit) && is_numeric($limit) ) {
            $sql .= " LIMIT ". $limit;
            if ( !is_null($offset) && is_numeric($offset) ) {
                $sql .= " OFFSET ". $offset;
            }
        }

        $result = DigiplayDB::query($sql);
        $return = array();
        while ($email = pg_fetch_object($result, NULL, 'Email')) {
            $return[] = $email;
        }

        return $return;
    }
	
    public static function get_by_id($id) {
	$sql = "SELECT * FROM email WHERE id = ". $id;
	$result = DigiplayDB::query($sql);
	return pg_fetch_object($result, NULL, 'Email');
    }
}
?>
    