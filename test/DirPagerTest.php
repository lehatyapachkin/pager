<?php
use PHPUnit\Framework\TestCase;
## Постраничная навигация по папке
  // Временная автозагрузка классов
  spl_autoload_register(function($class){
    require_once(str_replace('\\', '/', "/var/www/example.info/www/classs/src/{$class}.php"));
  });

class DirPagerTest extends TestCase
{
    protected $obj;

    public function setUp()
    {
        $this->obj  = new ISPager\DirPager(
                  new ISPager\PagesList(),
                  '/var/www/example.info/www/photos/',
                  3,
                  2);
    }
    public function testgetItemsCount()
    {
        $this->assertTrue(is_int($this->obj->getItemsCount()));
    }

    public function testgetItems()
    {
        $this->assertTrue(is_array($this->obj->getItems()));
        $this->assertContainsOnly('string', $this->obj->getItems());
        $this->assertArrayHasKey(2, $this->obj->getItems());
        $mock = TestCase::getMockClass('ISPager\Pager');
        $this-assertInstanceOf('ISPager\Pager', $mock);
    }

    public function tearDown()
    {
        $this->obj = null;
    }
}
