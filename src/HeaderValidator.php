<?php
namespace Importer;

interface HeaderValidator
{

    /**
     * @return string[]
     */
    function getRequiredHeaders();
}
