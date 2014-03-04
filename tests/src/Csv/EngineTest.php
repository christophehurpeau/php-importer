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
        $this->setExpectedException(
          '\Importer\WrongHeaderException', 'Required headers: header1, header2'
        );
        $engine->validateHeader($headerValidator2, array('header1'));
    }

    public function testProcess() {
        $headerValidator =
            $this->getMock('\Importer\HeaderValidator', array('getRequiredHeaders'));
        $headerValidator
            ->expects($this->once())
            ->method('getRequiredHeaders')
            ->will($this->returnValue(array(
                'header1',
                'header2'
            )));

        $tmpfname = tempnam("/tmp", "csv");
        file_put_contents($tmpfname, "header1;header2\nvalue1;value2\n");

        $processor = $this->getMock('\Importer\LineProcessor', array('processLine'));
        /*$processor
            ->expects($this->once())
            ->method('getRequiredHeaders')
            ->will($this->returnValue(array(
                'header1',
                'header2'
            )));*/

        $engine = new \Importer\Csv\Engine();
        $result = $engine->process($tmpfname, $processor, $headerValidator);

        $this->assertEquals(array(
            array('header1'=> 'value1', 'header2'=> 'value2'),
        ), $result);


        unlink($tmpfname);
    }


    public function testProcessWrongLineElementsCountException() {
        $headerValidator =
            $this->getMock('\Importer\HeaderValidator', array('getRequiredHeaders'));
        $headerValidator
            ->expects($this->once())
            ->method('getRequiredHeaders')
            ->will($this->returnValue(array(
                'header1',
                'header2'
            )));

        $tmpfname = tempnam("/tmp", "csv");
        file_put_contents($tmpfname, "header1;header2\nvalue1;value2;value3\n");

        $processor = $this->getMock('\Importer\LineProcessor', array('processLine'));
        $engine = new \Importer\Csv\Engine();
        try {
            $result = $engine->process($tmpfname, $processor, $headerValidator);
            $this->fail('Engine should throw WrongLineElementsCountException');
        } catch (\Importer\WrongLineElementsCountException $e) {
            $this->assertEquals($e->getMessage(), 'There is 3 elements, headers have 2'
                            ."\n line = value1;value2;value3");
        }
        unlink($tmpfname);
    }
}