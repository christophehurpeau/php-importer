<?php
namespace Importer;

interface Parser extends Iterator
{
    function fetchNextLine();
}
