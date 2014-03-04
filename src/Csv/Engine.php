<?php
namespace Importer\Csv;

class Engine implements \Importer\Engine
{

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
     * @param  string $file file path
     * @param  \Importer\LineProcessor $processor
     * @param  \Importer\HeaderValidator $validator
     * @return array|true
     */
    public function process($file, \Importer\LineProcessor $processor, \Importer\HeaderValidator $validator = null) {
        $parser = new Parser($file);
        $failedLines = array();

        $parser->rewind();
        $headers = $parser->current();
        if ($validator !== null) {
            $this->validateHeader($validator, $headers);
        }

        while ($line = $parser->fetchNextLine()){
            if (empty($line)) {
                continue;
            }
            if (count($headers) !== count($line)) {
                throw new \Importer\WrongLineElementsCountException(
                        'There is '.count($line).' elements, headers have ' .count($headers)
                        ."\n line = " . implode(';', $line));
            }
            $line = array_combine($headers, $line);
            $successLine = $processor->processLine($line);
            if (!$successLine) {
                $failedLines[] = $line;
            }
        }
        return empty($failedLines) ? true : $failedLines;
    }
}
