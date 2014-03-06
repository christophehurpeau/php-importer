<?php
namespace Importer\Tests\Csv;

use Importer\Csv\Importer;

class ImporterTest extends \PHPUnit_Framework_TestCase {
    public function testCreateParser() {
        $tmpfname = tempnam("/tmp", "csv");
        $importer = new \Importer\Csv\Importer($tmpfname);
        $this->assertEquals($tmpfname, $importer->getFile());

        $createdParser = $importer->createParser();
        $this->assertEquals(true, $createdParser instanceof \Importer\Csv\Parser);
    }


    public function testProcess() {
        $engineMock = $this->getMock('\Importer\Engine', array('process'));
        $engineMock
            ->expects($this->once())
            ->method('process')
            ->will($this->returnValue('ok'));

        $mockParser = $this->getMockBuilder('\Importer\Csv\Parser')
            ->disableOriginalConstructor()
            ->getMock();

        $importerMock = $this->getMockBuilder('\Importer\Csv\Importer')
            ->setMethods(array('createParser'))
            ->disableOriginalConstructor()
            ->getMock();
        $importerMock
            ->expects($this->once())
            ->method('createParser')
            ->will($this->returnValue($mockParser));

        $lineProcessor = $this->getMock('\Importer\LineProcessor');

        $result = $importerMock->process($engineMock, $lineProcessor, null);

        $this->assertEquals('ok', $result);
    }

}