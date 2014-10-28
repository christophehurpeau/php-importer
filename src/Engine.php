<?php
namespace Importer;

interface Engine
{
    /**
     * Process the file and return failed lines or true if success
     * @param Parser $parser source parser
     * @param LineProcessor $processor
     * @param HeaderValidator $validator
     * @return array|true
     */
    public function process(Parser $parser, LineProcessor $processor, HeaderValidator $validator = null);
}
