<?
# Original base code (c) 2002 Stephen Bartholomew (http://code.activestate.com/recipes/125901-php-mysql-search-class/)
class Search {
    function find($keywords) {
        $keywords = trim($keywords);
        $keywords_array = preg_split("/[\s,!.\?]+/",$keywords);

        if(!$this->search_columns) {
            $this->search_columns = "*";
            $search_data_sql = "SELECT ".$this->search_columns." FROM ".$this->table;
        } else {
            $search_data_sql = "SELECT ".$this->entry_identifier.",".$this->search_columns." FROM ".$this->table;
        }

        $search_data_ref = DigiplayDB::query($search_data_sql);
        $search_results_array = array();

        if($search_data_ref) {
            while($all_data_array = pg_fetch_array($search_data_ref)) {
                $my_ident = $all_data_array[$this->entry_identifier];

                foreach($all_data_array as $entry_key=>$entry_value) {
                    foreach($keywords_array as $keyword) {
                        if($keyword) {
                            if(stristr($entry_value,$keyword)) {
                                $keywords_found_array[$keyword]++;
                            }
                        } else {
                            # This is a fix for when a user enters a keyword with a space
                            # after it.  The trailing space will cause a NULL value to
                            # be entered into the array and will not be found.  If there
                            # is a NULL value, we increment the keywords_found value anyway.
                            $keywords_found_array[$keyword]++;
                        }
                        unset($keyword);
                    }
    
                    if(sizeof($keywords_found_array) == sizeof($keywords_array)) {
                        array_push($search_results_array,"$my_ident");
                        break;
                    }
                }
                unset($keywords_found_array);
                unset($entry_key);
                unset($entry_value);
            }
        }

        $this->num_results = sizeof($search_results_array);
        return $search_results_array;
    }
    
    function set_identifier($entry_identifier) {
        $this->entry_identifier = $entry_identifier;
    }

    function set_table($table) {
        # Set which table we are searching
        $this->table = $table;
    }
    
    function set_search_columns($columns) {
        $this->search_columns = $columns;
    }
}

?>