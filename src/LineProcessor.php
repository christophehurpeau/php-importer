<?php
namespace Importer;

interface LineProcessor
{
    /**
     * @param string[] $line
     * @return boolean
     */
    function processLine(array $line);
}
