<?php
namespace Importer;

interface Importer
{
    /**
     * Process with the engine and return failed lines or true if success
     *
     * @param Engine          $engine
     * @param LineProcessor   $processor
     * @param HeaderValidator $validator
     * @return array|boolean
     */
    public function process(Engine $engine, LineProcessor $processor, HeaderValidator $validator = null);
}
