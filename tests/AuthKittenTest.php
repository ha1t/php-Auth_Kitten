<?php
/**
 *
 *
 */
class Auth_KittenTest extends PHPUnit_Framework_TestCase
{
    public function testMakeInstance()
    {
        $kitten = new Auth_Kitten();
        $this->assertEquals('Auth_Kitten', get_class($kitten));
    }

    public function testInvalidVerifyString()
    {
        $kitten = new Auth_Kitten();
        $this->assertFalse($kitten->verify('test'));
    }

    public function testInvalidVerifyArray()
    {
        $kitten = new Auth_Kitten();
        $this->assertFalse($kitten->verify(array()));
    }
}
