<?php
class Audiowall {
	protected $id;
	protected $name;
	protected $set_id;
	protected $page;
	protected $description;
	protected $items = NULL;
	
	public function get_id(){ return $this->id; }
	public function get_name(){ return $this->name; }
	public function get_set_id(){ return $this->set_id; }
	public function get_page(){ return $this->page; }
	public function get_description(){ return $this->description; }

	public function set_id($id){ $this->id = $id; }
	public function set_name($name){ $this->name = $name; }
	public function set_set_id($set_id){ $this->set_id = $set_id; }
	public function set_page($page){ $this->page = $page; }
	public function set_description($description){ $this->description = $description; }
	
	/* Extended Functions */
	public function get_items(){
		if ($this->items == NULL) {
			$this->items = DigiplayDB::select("* FROM aw_items WHERE wall_id = :wall_id ORDER BY item ASC;", "AudiowallItem", true, array(":wall_id" => $this->id));
		}	
    		return $this->items;
	}
	
	public function empty_wall() {
		$this->items = array();
	}

	public function add_item($AudiowallItem){
		$this->items[] = $AudiowallItem;
	}

	public function save(){
		if (is_null($this->id)){
			$query = "INSERT INTO aw_walls (name, set_id, page, description) VALUES ('".pg_escape_string($this->name)."','".pg_escape_string($this->set_id)."','".pg_escape_string($this->page)."','".pg_escape_string($this->description)."') RETURNING id";
			$result = DigiplayDB::query($query);
			$this->set_id(pg_fetch_result($result,0));
		} else {
			$query = "UPDATE aw_walls SET name='".$this->name."', set_id='".$this->set_id."', page='".$this->page."', description='".$this->description."' WHERE id=".$this->id."";
			$result = DigiplayDB::query($query);
		}
		$query = "DELETE FROM aw_items WHERE wall_id = '".$this->id."'";
		$result = DigiplayDB::query($query);
		foreach ($this->items as $item) {
			$item->set_wall_id($this->get_id());
			$item->save();
		}
	}
	
	public function delete(){
		$query = "DELETE FROM aw_items WHERE wall_id = '".$this->id."'";
		$result = DigiplayDB::query($query);
		$query = "DELETE FROM aw_walls WHERE id = '".$this->id."'";
		$result = DigiplayDB::query($query);
	}
}
?>