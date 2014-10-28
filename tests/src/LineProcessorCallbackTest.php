<?php
namespace Importer\Tests\Csv;

use Importer\LineProcessorCallback;

class LineProcessorCallbackTest extends \PHPUnit_Framework_TestCase
{

    public function testProcessLine()
    {
        $mock = $this->getMock('stdClass', array('myCallback'));
        $mock->expects($this->once())
            ->method('myCallback')
            ->will($this->returnValue(true));

        $lineProcessorCallback = new LineProcessorCallback(array($mock, 'myCallback'));
        $result = $lineProcessorCallback->processLine(array());
        $this->assertEquals(true, $result);
    }
}
