<?php 
require_once ("administratorProcess.php");
require_once('../../auth.php');
require_once('../../connectDb.php');
require_once('../../convertCsvFileToArray.php');
class administratorTest extends PHPUnit_Framework_TestCase{
     /**
    * @test
    */public function testMonthSelectionAndOrderCreation()
    {
       // monthSelectionAndOrderCreation(4);
    }
 /**
    * @test
    */function testStockListTemporaryCreating()
 {
    //var_dump(stockListTemporaryCreating());
 }
 /**
    * @test
    */function tesMonthlyIdGeneration()
 {
    $date=20170920;
    echo monthlyIdGeneration($date);
 }
}

 ?>