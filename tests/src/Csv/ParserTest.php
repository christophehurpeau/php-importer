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
        $parser = new Parser($this->tmpfname);
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

    public function testNext() {
        $parser = new Parser($this->tmpfname);
        $parser->next();
        $this->assertEquals(array('header1', 'header2'), $parser->current());
    }

    public function testCurrent() {
        $parser = new Parser($this->tmpfname);
        $this->assertEquals(null, $parser->current());
        $parser->next();
        $this->assertEquals(array('header1', 'header2'), $parser->current());
    }

    public function testRewind() {
        $parser = new Parser($this->tmpfname);
        $parser->next();
        $parser->next();
        $parser->rewind();
        $this->assertEquals(array('header1', 'header2'), $parser->current());
    }


    public function testKey() {
        $parser = new Parser($this->tmpfname);
        $parser->next();
        $this->assertEquals(0, $parser->key());
        $parser->next();
        $this->assertEquals(1, $parser->key());
    }

    public function testValid() {
        $parser = new Parser($this->tmpfname);
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