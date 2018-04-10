<?php

require "includes/Fault.php";

use PHPUnit\Framework\TestCase;
 
class FaultTests extends TestCase
{
    private $fault;

    protected function setUp()
    {
        $this->fault = new Fault();
    }
 
    protected function tearDown()
    {
        $this->fault = NULL;
    }
 
    public function testStatus()
    {
        $this->fault->set_status(1);
        $this->assertEquals("Not yet read", $this->fault->get_real_status());
        $this->fault->set_status(2);
        $this->assertEquals("On hold", $this->fault->get_real_status());
        $this->fault->set_status(3);
        $this->assertEquals("Work in progress", $this->fault->get_real_status());
        $this->fault->set_status(4);
        $this->assertEquals("Fault complete", $this->fault->get_real_status());
    }
 
}