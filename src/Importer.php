<?php
namespace Importer;

interface Importer
{
    /**
     * Process with the engine and return failed lines or true if success
     * @param  \Importer\Engine $engine
     * @param  \Importer\LineProcessor $processor
     * @param  \Importer\HeaderValidator $validator
     * @return array|true
     */
    function process(\Importer\Engine $engine, LineProcessor $processor, HeaderValidator $validator = null);
}
