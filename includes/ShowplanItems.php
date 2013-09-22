<?php

class ShowplanItems {
	public function get_by_id($id) { return DigiplayDB::select("* FROM showitems WHERE id = ".$id, "ShowplanItem"); }

	public function get_by_showplan($showplan) { return DigiplayDB::select("* FROM showitems WHERE showplanid = ".$showplan->get_id()." ORDER BY position ASC;", "ShowplanItem", true); }
}

?>