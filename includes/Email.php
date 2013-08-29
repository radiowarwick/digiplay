<?php
class Email {
	protected $id;
	protected $new_flag;
	protected $datetime;
    protected $sender;
    protected $subject;
    protected $body;
    
    public function __construct() {
       if ($this->new_flag == 't') {
           $this->new_flag = TRUE;
        } else {
           $this->new_flag = FALSE;
        }
    }
	public function get_sender(){
		return $this->sender;
	}
	public function get_id(){
		return $this->id;
	}
	public function get_datetime(){
		return $this->datetime;
	}
    public function get_new_flag(){
        return $this->new_flag;
    }
    public function get_subject(){
        return $this->subject;
    }
    public function get_body(){
        return $this->body;
    }


    public function get_body_formatted(){
        // Strip links, format plaintext/HTML appropriately
        $body = $this->body;
        if(substr($body, 0, 1) != "<") $body = nl2br($body);
        $body = strip_tags($body, "<div><p><br><hr><img><span><style><table><thead><tbody><tr><td>");
        return $body;
    }

    public function mark_as_read() {
        $query = DigiplayDB::query("UPDATE email SET new_flag = 'f' WHERE id = ".$this->id);
        $this->new_flag = FALSE;
        return (bool) $query;
    }
}
?>
