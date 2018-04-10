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
        $this->fault->set_status(2);
        $this->assertEquals("On hold", $this->fault->get_real_status());
    }
 
}