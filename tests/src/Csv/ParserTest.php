<?php
namespace Importer\Tests\Csv;

use Importer\Csv\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var string
     */
    private $tmpfname;

    protected function setUp() {
        $this->tmpfname = tempnam("/tmp", "csv");
        file_put_contents($this->tmpfname, "header1;header2\nvalue1.1;value1.2\nvalue2.1;value2.2\nvalue3.1;value3.2\n");
    }

    protected function tearDown() {
        unlink($this->tmpfname);
    }

    public function testFetchNextLine() {
        $parser = new Parser($this->tmpfname, ';');
        $line = $parser->fetchNextLine();
        $this->assertEquals(array('header1', 'header2'), $line);
        $line = $parser->fetchNextLine();
        $this->assertEquals(array('value1.1', 'value1.2'), $line);
        $line = $parser->fetchNextLine();
        $this->assertEquals(array('value2.1', 'value2.2'), $line);
        $line = $parser->fetchNextLine();
        $this->assertEquals(array('value3.1', 'value3.2'), $line);
        $line = $parser->fetchNextLine();
        $this->assertEquals(null, $line);
    }

    public function testFetchNextLine2() {
        $parser =
            $this->getMock('\Importer\Csv\Parser',
                        array('valid', 'next', 'current'),
                        array($this->tmpfname));
        $parser
            ->expects($this->once())
            ->method('valid')
            ->will($this->returnValue(true));
        $parser
            ->expects($this->once())
            ->method('next');
        $parser
            ->expects($this->once())
            ->method('current')
            ->will($this->returnValue('ok'));

        $result = $parser->fetchNextLine();
        $this->assertEquals('ok', $result);
    }

    public function testFetchNextLineEndOfFile() {
        $parser =
            $this->getMock('\Importer\Csv\Parser', array('valid'), array($this->tmpfname));
        $parser
            ->expects($this->once())
            ->method('valid')
            ->will($this->returnValue(false));
        $result = $parser->fetchNextLine();
        $this->assertEquals(null, $result);
    }

    public function testNext() {
        $parser = new Parser($this->tmpfname, ';');
        $parser->next();
        $this->assertEquals(array('header1', 'header2'), $parser->current());
    }

    public function testCurrent() {
        $parser = new Parser($this->tmpfname, ';');
        $this->assertEquals(null, $parser->current());
        $parser->next();
        $this->assertEquals(array('header1', 'header2'), $parser->current());
    }

    public function testRewind() {
        $parser = new Parser($this->tmpfname, ';');
        $parser->next();
        $parser->next();
        $parser->rewind();
        $this->assertEquals(array('header1', 'header2'), $parser->current());
    }


    public function testKey() {
        $parser = new Parser($this->tmpfname, ';');
        $parser->next();
        $this->assertEquals(0, $parser->key());
        $parser->next();
        $this->assertEquals(1, $parser->key());
    }

    public function testValid() {
        $parser = new Parser($this->tmpfname, ';');
        $this->assertEquals(true, $parser->valid());
        $parser->next();
        $this->assertEquals(true, $parser->valid());
        $parser->next();
        $this->assertEquals(true, $parser->valid());
        $parser->next();
        $this->assertEquals(true, $parser->valid());
        $parser->next();
        $this->assertEquals(true, $parser->valid());
        $parser->next();
        $this->assertEquals(false, $parser->valid());
    }
}