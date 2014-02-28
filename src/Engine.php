<?php
namespace Importer;

interface Engine
{
    function process($file, LineProcessor $processor, HeaderValidator $validator = null);
}
