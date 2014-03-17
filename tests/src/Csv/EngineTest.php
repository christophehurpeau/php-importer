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
          '\Importer\WrongHeaderException', 'Missing header: header2 ; required headers: header1, header2'
        );
        $engine->validateHeader($headerValidator2, array('header1'));
    }


    public function testProcessLineWrongLineElementsCountException() {
        $lineProcessor = $this->getMock('\Importer\LineProcessor', array('processLine'));

        $this->setExpectedException(
          '\Importer\WrongLineElementsCountException', 'There is 3 elements, headers have 2'
                            ."\n line = value1;value2;value3"
        );

        $engine = new \Importer\Csv\Engine();
        $engine->processLine(array('value1', 'value2', 'value3'), array('header1','header2'), $lineProcessor);
    }


    public function testProcessLineWhenLineIsEmpty() {
        $lineProcessor = $this->getMock('\Importer\LineProcessor', array('processLine'));

        $engine = new \Importer\Csv\Engine();
        $result = $engine->processLine(array(), array('header1','header2'), $lineProcessor);
        $this->assertEquals(null, $result);
    }

    public function testProcessLineWhenProcessIsOkay() {
        $lineProcessorMock = $this->getMock('\Importer\LineProcessor', array('processLine'));

        $lineProcessorMock
            ->expects($this->once())
            ->method('processLine')
            ->will($this->returnValue(true));

        $engine = new \Importer\Csv\Engine();
        $result = $engine->processLine(array('value1', 'value2'), array('header1','header2'), $lineProcessorMock);
        $this->assertEquals(null, $result);
    }

    public function testProcessLineWhenProcessFailed() {
        $lineProcessorMock = $this->getMock('\Importer\LineProcessor', array('processLine'));

        $lineProcessorMock
            ->expects($this->once())
            ->method('processLine')
            ->will($this->returnValue(false));

        $engine = new \Importer\Csv\Engine();
        $result = $engine->processLine(array('value1', 'value2'), array('header1','header2'), $lineProcessorMock);
        $this->assertEquals(array('header1'=> 'value1', 'header2'=> 'value2'), $result);
    }


    public function testProcessFailed() {
        $headerValidatorMock =
            $this->getMock('\Importer\HeaderValidator', array('getRequiredHeaders'));
        $headerValidatorMock
            ->expects($this->once())
            ->method('getRequiredHeaders')
            ->will($this->returnValue(array(
                'header1',
                'header2'
            )));

        $parserMock = $this->getMockBuilder('\Importer\Csv\Parser')
            ->setMethods(array('rewind', 'current', 'fetchNextLine'))
            ->disableOriginalConstructor()
            ->getMock();
        $parserMock
            ->expects($this->once())
            ->method('current')
            ->will($this->returnValue(
                array('header1', 'header2')
            ));

        $parserMock
            ->expects($this->any())
            ->method('fetchNextLine')
            ->will($this->onConsecutiveCalls(
                array('value1', 'value2'),
                null
            ));
        $lineProcessor = $this->getMock('\Importer\LineProcessor', array('processLine'));

        $lineProcessorReturnValue = array('header1'=> 'value1', 'header2'=> 'value2');
        $engineMock = $this->getMock('\Importer\Csv\Engine', array('processLine'));
        $engineMock
            ->expects($this->once())
            ->method('processLine')
            ->will($this->returnValue($lineProcessorReturnValue));

        $result = $engineMock->process($parserMock, $lineProcessor, $headerValidatorMock);

        $this->assertEquals(array($lineProcessorReturnValue), $result);
    }

    public function testProcessSuccess() {
        $headerValidatorMock =
            $this->getMock('\Importer\HeaderValidator', array('getRequiredHeaders'));
        $headerValidatorMock
            ->expects($this->once())
            ->method('getRequiredHeaders')
            ->will($this->returnValue(array(
                'header1',
                'header2'
            )));

        $parserMock = $this->getMockBuilder('\Importer\Csv\Parser')
            ->setMethods(array('rewind', 'current', 'fetchNextLine'))
            ->disableOriginalConstructor()
            ->getMock();
        $parserMock
            ->expects($this->once())
            ->method('current')
            ->will($this->returnValue(
                array('header1', 'header2')
            ));

        $parserMock
            ->expects($this->any())
            ->method('fetchNextLine')
            ->will($this->onConsecutiveCalls(
                array('value1', 'value2'),
                null
            ));
        $lineProcessor = $this->getMock('\Importer\LineProcessor', array('processLine'));


        $engineMock = $this->getMock('\Importer\Csv\Engine', array('processLine'));
        $engineMock
            ->expects($this->once())
            ->method('processLine')
            ->will($this->returnValue(false));

        $result = $engineMock->process($parserMock, $lineProcessor, $headerValidatorMock);

        $this->assertEquals(true, $result);
    }
}