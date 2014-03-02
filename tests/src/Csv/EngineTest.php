<?php
namespace Importer\Tests\Csv;

use Importer\Csv\Engine;

class EngineTest extends \PHPUnit_Framework_TestCase {
    public function testValidateHeader() {
        $headerValidator =
            $this->getMock('\Importer\HeaderValidator', array('getRequiredHeaders'));
        $headerValidator
            ->expects($this->once())
            ->method('getRequiredHeaders')
            ->will($this->returnValue(array(
                'header1',
                'header2'
            )));

        $engine = new \Importer\Csv\Engine();
        $this->assertEquals(true, $engine->validateHeader($headerValidator, array('header1', 'header2')));

        $headerValidator2 =
            $this->getMock('\Importer\HeaderValidator', array('getRequiredHeaders'));
        $headerValidator2
            ->expects($this->once())
            ->method('getRequiredHeaders')
            ->will($this->returnValue(array(
                'header1',
                'header2'
            )));

        $engine = new \Importer\Csv\Engine();
        $this->assertEquals(false, $engine->validateHeader($headerValidator2, array('header1')));
    }
}