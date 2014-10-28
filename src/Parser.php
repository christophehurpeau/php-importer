<?php
namespace Importer;

interface Parser extends \Iterator
{
    public function fetchNextLine();
}
