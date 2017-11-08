<?php
use PHPUnit\Framework\TestCase;

require_once '/var/www/example.info/www/MyClass2.php';

class CalculatorTests extends TestCase
{
    private $calculator;

    protected function setUp()
    {
        $this->calculator = new Calculator();
    }

    protected function tearDown()
    {
        $this->calculator = NULL;
    }

    public function testAdd()
    {
        $result = $this->calculator->add(2, 2);
        $this->assertEquals(4, $result);
    }

}
