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
}
?>
