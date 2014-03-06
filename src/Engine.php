<?php
namespace Importer;

interface Engine
{
    /**
     * Process the file and return failed lines or true if success
     * @param  \Importer\Parser $parser source parser
     * @param  \Importer\LineProcessor $processor
     * @param  \Importer\HeaderValidator $validator
     * @return array|true
     */
    function process(Parser $parser, LineProcessor $processor, HeaderValidator $validator = null);
}
