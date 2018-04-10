<?php

require "includes/Session.php";

use PHPUnit\Framework\TestCase;
 
class SessionTests extends TestCase
{

    protected function setUp()
    {
        $_SESSION["user"] = serialize(array("user" => false));
    }
 
    protected function tearDown()
    {
        //
    }
 
    public function testStatus()
    {
        $this->assertFalse(Session::is_user());
    }
 
}