<?php
namespace Importer;

/**
 * A simple implementation of a LineProcessor
 */
class LineProcessorCallback implements LineProcessor
{
    private $callback;

    /**
     * @param callback $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param string[] $line
     * @return boolean
     */
    public function processLine(array $line)
    {
        return call_user_func($this->callback, $line);
    }

}
