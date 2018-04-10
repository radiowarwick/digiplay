<?php

require "../includes/Fault.php";
 
class FaultTests extends PHPUnit_Framework_TestCase
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
        $result = $this->fault->setStatus(2);
        $this->assertEquals("On hold", $result);
    }
 
}