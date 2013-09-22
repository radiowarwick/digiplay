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
		if($this->id) DigiplayDB::update("showplans", get_object_vars($this), "id = ".$this->id);
		else {
			if(!$this->creationdate) $this->creationdate = date();
			$this->id = DigiplayDB::insert("showplans", get_object_vars($this), "id");
		}
		return $this->id;
	}

	public function get_items() { return ShowplanItems::get_by_showplan($this); }
	public function add_item($item) { $item->set_showplan($this); }

	public function get_end_position() { 
		$result = DigiplayDB::select("MAX(position) AS max FROM showitems WHERE showplanid = ".$this->id);
		if($result) return $result + 1;
		else return 1;
	}
}

?>