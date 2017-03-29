<?php 
require_once ("administratorProcess.php");

class administratorTest extends PHPUnit_Framework_TestCase{
     /**
    * @test
    */public function testMonthSelectionAndOrderCreation()
    {
        monthSelectionAndOrderCreation(4);
    }
}

 ?>