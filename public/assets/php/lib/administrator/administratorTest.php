<?php 
require_once ("administratorProcess.php");

class administratorTest extends PHPUnit_Framework_TestCase{
     /**
    * @test
    */public function testMonthSelectionAndOrderCreation()
    {
        monthSelectionAndOrderCreation(4);
    }
 /**
    * @test
    */function testStockListCreation()
 {
    var_dump(stockListCreation(1));
 }

}

 ?>