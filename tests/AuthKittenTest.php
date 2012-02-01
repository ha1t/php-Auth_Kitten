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

    public function testFail()
    {
        $kitten = new Auth_Kitten();
        $this->assertFalse($kitten->verify('test'));
    }
}
