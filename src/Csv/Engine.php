<?php
namespace Importer\Csv;

class Engine implements \Importer\Engine
{

    /**
     * Validate headers
     * @param \Importer\HeaderValidator $validator
     * @param array $headers
     * @throws \Importer\WrongHeaderException
     * @return true
     */
    public function validateHeader(\Importer\HeaderValidator $validator, array $headers) {
        $requiredHeaders = $validator->getRequiredHeaders();
        if (!empty($requiredHeaders)) {
            foreach($requiredHeaders as $requiredHeader) {
                if (!in_array($requiredHeader, $headers)) {
                    throw new \Importer\WrongHeaderException('Required headers: '.implode(', ', $requiredHeaders));
                }
            }
        }
        return true;
    }

    /**
     * Process the file and return failed lines or true if success
     * @param  \Importer\Parser $parser csv parser
     * @param  \Importer\LineProcessor $processor
     * @param  \Importer\HeaderValidator $validator
     * @throws \Importer\WrongHeaderException
     * @throws \Importer\WrongLineElementsCountException
     * @return array|true
     */
    public function process(\Importer\Parser $parser, \Importer\LineProcessor $processor, \Importer\HeaderValidator $validator = null) {
        $failedLines = array();

        $parser->rewind();
        $headers = $parser->current();
        if ($validator !== null) {
            $this->validateHeader($validator, $headers);
        }

        while ($line = $parser->fetchNextLine()){
            $failedLine = $this->processLine($line, $headers, $processor);
            if ($failedLine) {
                $failedLines[] = $failedLine;
            }
        }
        return empty($failedLines) ? true : $failedLines;
    }

    /**
     * Process one line, returns null if line is empty, false if success, the line if failure
     * @param array $line
     * @param array $headers
     * @param \Importer\LineProcessor $processor
     * @throws \Importer\WrongLineElementsCountException
     * @return null|false|array
     */
    public function processLine(array $line, array $headers, \Importer\LineProcessor $processor) {
        if (empty($line)) {
            return null;
        }

        if (count($headers) !== count($line)) {
            throw new \Importer\WrongLineElementsCountException(
                    'There is '.count($line).' elements, headers have ' .count($headers)
                    ."\n line = " . implode(';', $line));
        }

        $line = array_combine($headers, $line);
        if ($processor->processLine($line)) {
            return false;
        }
        return $line;
    }
}
