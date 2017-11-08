<?php
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

require_once './vendor/autoload.php';
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
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('exampledir'));

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
        $this->assertContainsOnly('string', $this->obj->getItems());
        $this->assertArrayHasKey(2, $this->obj->getItems());
        $this->assertArrayNotHasKey(3, $this->obj->getItems());
        $this->assertCount(3, $this->obj->getItems());

        $mock1 = $this->getMockForAbstractClass('ISPager\View');
        $this->assertInstanceOf('ISPager\View', $mock1);
        $mock2 = $this->getMockForAbstractClass('ISPager\Pager', [$mock1]);
        $this->assertInstanceOf('ISPager\Pager', $mock2);

        $this->assertTrue(is_int($mock2->getCurrentPage()));

        $mock11 = $this->createMock('ISPager\Pager'::class);
        $mock11->method('getVisibleLinkCount')
               ->willReturn('foo');
        $this->assertEquals('foo', $mock11->getVisibleLinkCount());

        $mock11->method('getPagesCount')
               ->will($this->returnArgument(0));
        $this->assertEquals('foo', $mock11->getPagesCount('foo'));

        $mock11->method('getPagesCount')
               ->will($this->returnArgument(0));
        $this->assertEquals(26, $mock11->getPagesCount(26));

        $mock12 = $this->createMock('ISPager\Pager'::class);
        $mock12->method('getPagesCount')
               ->will($this->returnSelf());
        $this->assertInstanceOf('ISPager\Pager', $mock12->getPagesCount());

        $mock13 = $this->createMock('ISPager\Pager'::class);
        $map = [
            ['q', 'w', 't'],
            [34, 1.4, 26]
        ];
        $mock13->method('getItemsCount')
               ->will($this->returnValueMap($map));
        var_dump($mock13->getItemsCount('q', 'w', 't'));
        //$this->assertEquals('e', $mock13->getPagesCount('q', 'w', 't'));
        //$this->assertEquals(23, $mock13->getPagesCount(34, 1.4, 26));

        $mock14 = $this->createMock('ISPager\Pager'::class);
        $mock14->method('getPagesCount')
               ->will($this->returnCallback('strrev'));
        $this->assertEquals('iuytrewq', $mock14->getPagesCount('qwertyui'));

        $mock15 = $this->createMock('ISPager\Pager'::class);
        $mock15->method('getPagesCount')
               ->will($this->onConsecutiveCalls(4.4, 'str', 26, true));
        $this->assertEquals(4.4, $mock15->getPagesCount());
        $this->assertEquals('str', $mock15->getPagesCount());
        $this->assertEquals(26, $mock15->getPagesCount());
        $this->assertEquals(true, $mock15->getPagesCount());

        $mock16 = $this->createMock('ISPager\Pager'::class);
        $mock16->method('getPagesCount')
               ->will($this->throwException(new Exception));


        $this->assertEquals(null, $mock11->getParameters());

        $this->assertInstanceOf('ISPager\Pager', $mock11);
        $stub = $this->getMockBuilder('ISPager\Pager', [$mock1])
                     ->setMethods(array(
                             'getPagesCount',
                             'getCurrentPage',
                             'getItemsCount',
                             'getItems'
                        ))
                     ->disableOriginalClone ()
                     ->disableArgumentCloning ()
                     ->disallowMockingUnknownTypes ()
                     ->disableAutoload()
                     ->disableOriginalConstructor()
                     ->getMock();

        $stub->expects($this->any())
             ->method('getPagesCount')
             ->will($this->returnValue(-26));
        $this->assertEquals(-26, $stub->getPagesCount());

        $this->assertEquals(null, $stub->getItemsCount());

        $stub->expects($this->any())
             ->method('getCurrentPage')
             ->will($this->returnValue(-78));

        $this->assertEquals(-78, $stub->getCurrentPage());
        $this->assertTrue(is_array($this->obj->getItems()));

      //Тестирование того, что метод вызывается два раза с конкретными аргументами.
        $mock21 = $this->getMockBuilder(ISPager\View::class)
                       ->getMock();
        $this->assertInstanceOf('ISPager\View', $mock21);
        $mock2 = $this->getMockBuilder('ISPager\Pager')
                      ->setMethods(array('getItemsPerPage',
                                         'getItemsCount',
                                         'getItems'))
                      ->enableArgumentCloning()
                      ->disableOriginalConstructor()
                      ->getMock();
        $this->assertInstanceOf('ISPager\Pager', $mock2);
      $mock2->expects($this->exactly(2))
             ->method('getItemsCount')
             ->withConsecutive(
               [$this->equalTo('foo'), $this->greaterThan(0)],
               array($this->equalTo('boo'), $this->greaterThan(0))
             );
             $mock2->getItemsCount('foo', 21);
             $mock2->getItemsCount('boo', 42);

        $mock23 = $this->getMockBuilder('ISPager\Pager')
                       ->disableOriginalConstructor()
                       ->getMock();
        $mock23->expects($this->any())
               ->method('getItemsCount')
               ->with($this->greaterThan(0),
                      $this->stringContains('какаето строка'),
                      $this->callback(function($object) {
                              return is_callback([$object, 'getName']) &&
                              $object->getName() == 'какаето строка';

                          }
                      )

                 );
                // sleep(3);
              //var_dump($mock23->getItemsCount('какаето строка'));
        //$this->expectException(InvalidArgumentException::class);
        $this->assertFalse(vfsStreamWrapper::getRoot()->hasChild('id'));

        print_r($this->obj->getItems());
    }

    public function tearDown()
    {
        $this->obj = null;
    }
}
