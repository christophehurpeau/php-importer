<?php
namespace Importer\Csv;

/**
 * CSV file parser
 */
class Parser implements \Importer\Parser
{

    /**
     * @var resource
     */
    private $resource;

    /**
     * @var string[]
     */
    private $currentElement;
    
    /**
     * @var string
     */
    private $separator;

    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var int
     */
    private $key = -1;

    /**
     * Create a new CSV Parser
     * @param string $file path of the csv file
     * @param string $separator csv separator
     * @param string $delimiter csv delimiter
     */
    public function __construct($file, $separator = null, $delimiter = '"')
    {
        $this->separator = $separator;
        $this->delimiter = $delimiter;
        $this->resource = fopen($file, 'r');

        if ($separator === null) {
            $this->separator = ';';
            $this->rewind();
            $count1 = $this->currentElement;

            $this->separator = ',';
            $this->rewind();
            $count2 = $this->currentElement;

            $this->separator = $count1 > $count2 ? ';' : ',';
            rewind($this->resource);
            $this->key = -1;
        }
    }

    /**
     * Return at the top of the CSV file
     * 
     * @return void
     */
    public function rewind()
    {
        rewind($this->resource);
        $this->next();
        $this->key = 0;
    }

    /**
     * Return the current element
     * 
     * @return string[]
     */
    public function current()
    {
        return $this->currentElement;
    }

    /**
     * Return the current key
     * 
     * @return int
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Get the next CSV line
     *
     * @return void
     */
    public function next()
    {
        $this->key++;
        $this->currentElement = fgetcsv($this->resource, 0, $this->separator, $this->delimiter);
    }

    /**
     * Check if we are at the end of the file
     * 
     * @return bool
     */
    public function valid()
    {
        return !feof($this->resource);
    }


    /**
     * Close the file handle
     */
    public function __destruct()
    {
        if ($this->resource !== null) {
            fclose($this->resource);
        }
    }

    /**
     * Fetch and return the next line, or null
     * @return string[]
     */
    public function fetchNextLine() {
        if (!$this->valid()) {
            return null;
        }
        $this->next();
        return $this->current();
    }
}
