<?php 
require_once ("user/userProcess.php");
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

       $a = returnCurrentMonthProductList();
    // var_dump($a);

   }
 /**
    * @test
    */
 function testReturnStockList()
 {
    $b =returnStockList(2);
   // var_dump($b);
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
    var_dump($d);
}
/**
    * @test
    */
    function testFixedFlagChange()
    { $arr = array(1 =>100,
        2 =>200,
        3 =>300,
        4 =>400,
        );
    $e=  fixedFlagChange(1,$arr)
    }
}
?>