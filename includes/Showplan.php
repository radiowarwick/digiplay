<?php

class Showplan {
	protected $id;
	protected $name;
	protected $userid;
	protected $creationdate;
	protected $showdate;
	protected $completed;

	public function __construct() {
		if ($this->completed == "t") $this->completed = TRUE;
		else $this->completed = FALSE;
    }

    public function get_id() { return $this->id; }
    public function get_name() { return $this->name; }
    public function get_user() { return Users::get_by_id($this->userid); }
    public function get_creationdate() { return $this->creationdate; }
    public function get_showdate() { return $this->showdate; }
    public function is_completed() { return $this->completed; }

    public function set_name($name) { $this->name = $name; }
    public function set_user($user) { $this->userid = $user->get_id(); }
    public function set_creationdate($date) { $this->creationdate = $date; }
    public function set_showdate($date) { $this->showdate = $date; }
    public function set_completed($completed) { $this->completed = (bool) $completed; }

    public function save() {
		if(!$this->name) return false;
		if($this->id) DigiplayDB::query("UPDATE showplans SET name = '".pg_escape_string($this->name)."', userid = ".$this->userid.", creationdate = ".$this->creationdate.", showdate = ".$this->showdate.", completed = '".($this->completed? "t":"f")."' WHERE id = ".$this->id.";");
		else {
			$return = pg_fetch_array(DigiplayDB::query("INSERT INTO showplans (name, userid, creationdate, showdate, completed) VALUES ('".pg_escape_string($this->name)."',".$this->creationdate.",".$this->showdate.",'".($this->completed? "t":"f")."' RETURNING id;"));
			$this->id = $return["id"];
		}
		return $this->id;
	}

	public function get_items() { return ShowplanItems::get_by_showplan($this); }
	public function add_item($item) { $item->set_showplan($this); }

	public function get_end_position() { 
		$result = DigiplayDB::query("SELECT MAX(position) AS max FROM showitems WHERE showplanid = ".$this->id);
		if(pg_num_rows($result)) return pg_fetch_result($result,NULL,"max")+1;
		else return 1;
	}
}

?>