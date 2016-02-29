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

    public function testGetFileList()
    {
        $object = new Auth_Kitten();
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('getFileList');
        $method->setAccessible(true);

        $file_list = $method->invoke($object, dirname(__DIR__) . '/src/Auth/Kitten/images/kitten/');
        $this->assertNotEquals(count($file_list), 0);

        $file_list = $method->invoke($object, dirname(__DIR__) . '/src/Auth/Kitten/images/other/');
        $this->assertNotEquals(count($file_list), 0);
    }
}
