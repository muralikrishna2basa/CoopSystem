<?php 
require_once ("administratorProcess.php");
require_once('../../auth.php');
require_once('../common/connectDb.php');
require_once('../../convertCsvFileToArray.php');
class administratorTest extends PHPUnit_Framework_TestCase{
     /**
    * @test
    */
     function testMonthSelectionAndOrderCreation()
     {
       // monthSelectionAndOrderCreation(4);
     }
 /**
    * @test
    */
 function testStockListTemporaryCreating()
 {
   // var_dump(stockListTemporaryCreating());
 }
 /**
    * @test
    */
 function testMonthlyIdGeneration()
 {
    $date=20170920;
 //monthlyIdGeneration($date);
}

/**
    * @test
    */
function testOrderListDisplay(){
   // var_dump(orderListDisplay(2));

}
/**
    * @test
    */
function testFixOrder(){
   // fixOrder();
}
/**
    * @test
    */
function testAdministratorReturnStockList(){
  //  var_dump(administratorReturnStockList());
}
/**
    * @test
    */
function testProductListOneDeleting(){
    //productListOneDeleting(67);
}
/**
    * @test
    */
function testProductListAllDeleting(){
    //productListAllDeleting(26);
}
/**
    * @test
    */
function aa(){
   var_dump(orderAggregate(1));
}
}
?>