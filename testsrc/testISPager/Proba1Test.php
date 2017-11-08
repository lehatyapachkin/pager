<?php
use PHPUnit\Framework\TestCase;

spl_autoload_register(function($class) {
    require_once (str_replace('\\', '/', "/var/www/example.info/www/classs/src/{$class}.php"));
                      });

class Proba1Test extends TestCase
{
    public $obj;

    public function setUp()
    {
        $this->obj = new ISPager\Proba1();
    }

    /**
     * @dataProvider providergetY
     */
    public function testgetY($a)
    {
        $class = new ReflectionClass('ISPager\Proba1');
        $method = $class->getMethod('setY');
        $method->setAccessible(true);
        $method->invoke($this->obj, $a);
        $this->assertEquals($a, $this->obj->getY());
    }

    public function providergetY()
    {
        return [[26], [7.8], [-9], [14], ['string'], [false]];
    }


     public function tearDown()
     {
         $this->obj = null;
     }
}
