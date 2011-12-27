<?php
class UserError extends Exception
{
    public function __construct($message = null) {
        if(is_null($message)) $message = "Unknown error";
        parent::__construct($message);
    }

    public function __toString() {
        return $this->message;
    }
}
?>