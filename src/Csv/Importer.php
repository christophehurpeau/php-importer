<?php
namespace Importer\Csv;

class Importer implements \Importer\Importer
{
    /**
     * @var string path of the file
     */
    private $file;

    /**
     * @param string
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return \Importer\Parser
     */
    public function createParser()
    {
        return new Parser($this->file);
    }

    /**
     * Process with the engine and return failed lines or true if success
     *
     * @param  \Importer\Engine          $engine
     * @param  \Importer\LineProcessor   $processor
     * @param  \Importer\HeaderValidator $validator
     * @return array|true
     */
    public function process(
        \Importer\Engine $engine,
        \Importer\LineProcessor $processor,
        \Importer\HeaderValidator $validator = null
    ) {
        $parser = $this->createParser();
        return $engine->process($parser, $processor, $validator);
    }
}
