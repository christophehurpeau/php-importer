<?php
namespace Importer;

interface LineProcessor
{
    /**
     * @param string[] $line
     * @return boolean
     */
    public function processLine(array $line);
}
