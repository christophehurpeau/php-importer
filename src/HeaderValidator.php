<?php
namespace Importer;

interface HeaderValidator
{
    /**
     * @return string[]
     */
    public function getRequiredHeaders();
}
