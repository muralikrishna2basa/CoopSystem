<?php 
require_once ("user/userProcess.php");
require_once('./common/connectDb.php');
/**
* 
*/
class userTest extends PHPUnit_Framework_TestCase
{
    /**
    * @test
    */
    function testReturnCurrentMonthProductList()
    {

       $a = returnCurrentMonthProductList(12);
    // var_dump($a);

   }
 /**
    * @test
    */
 function testReturnStockList()
 {
    $b =returnStockList(9,0);
    var_dump($b);
}
/**
    * @test
    */
function testDoOrder()
{
    $arr = array(1 =>100,
        2 =>200,
        3 =>300,
        4 =>400,
        );
   // $c =doOrder(1,$arr);
}
/**
    * @test
    */
function testDisplayHistory()
{
    $d =displayHistory(1,3);
    //var_dump($d);
}

}
?>