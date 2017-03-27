<?php
require_once ("../php/auth.php");
class testCalc extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function 正常系_単一ユーザー情報取得()
    {
        try {
            $res = getSingleUser(163);
        } catch (Exception $e) {
            $this->assertFalse(true);
            return;
        }
        $this->assertEquals($res['userid'],  163);
        $this->assertEquals($res['loginid'], 'n-teshima');
        $this->assertEquals($res['userName'],'手島尚人');


        try {
            $res = getSingleUser(null, 'n-teshima');
        } catch (Exception $e) {
            $this->assertFalse(true);
            return;
        }

        $this->assertEquals($res['userid'],  163);
        $this->assertEquals($res['loginid'], 'n-teshima');
        $this->assertEquals($res['userName'],'手島尚人');
    }

    /**
     * @test
     */
    public function 異常系_単一ユーザー情報取得()
    {
        try {
            $res = getSingleUser(99999);
        } catch (Exception $e) {
            $this->assertFalse(false);
            return;
        }
        $this->assertFalse(true);
    }

    /**
     * @test
     */
    public function 正常系_複数ユーザー情報取得()
    {
        try {
            $res = getAllUsers();
        } catch (Exception $e) {
            echo $e;
            $this->assertFalse(true);
            return;
        }
        $this->assertEquals($res[0]['userid'],  9);
        $this->assertEquals($res[0]['loginid'], 't-kubota');
        $this->assertEquals($res[0]['userName'],'久保田正');
    }

    /**
     * @test
     */
    public function 正常系_ログイン成功()
    {
        try {
            $res = authentificateUser('n-teshima','user');
        } catch (Exception $e) {
            echo $e;
            $this->assertFalse(true);
            return;
        }
//        var_dump($res);
        $this->assertEquals('', $res['loginid']);
        $this->assertEquals('', $res['password']);
    }

    /**
     * @test
     */
    public function 異常系_ユーザーが存在しない()
    {
        try {
            $res = authentificateUser('teshima','user');
        } catch (Exception $e) {
            echo $e;
            $this->assertFalse(true);
            return;
        }
//        var_dump($res);
        $this->assertEquals('ユーザーが見つかりませんでした。', $res['loginid']);
        $this->assertEquals('', $res['password']);
    }

    /**
     * @test
     */
    public function 異常系_パスワードが誤っている()
    {
        try {
            $res = authentificateUser('n-teshima','wrong');
        } catch (Exception $e) {
            echo $e;
            $this->assertFalse(true);
            return;
        }
//        var_dump($res);
        $this->assertEquals('', $res['loginid']);
        $this->assertEquals('パスワードが間違っているようです。', $res['password']);
    }
}