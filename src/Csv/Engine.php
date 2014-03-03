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

    public function process($file, \Importer\LineProcessor $processor, \Importer\HeaderValidator $validator = null) {
        $parser = new Parser($file);
        $success = true;
        $missings = array();

        $parser->rewind();
        $headers = $parser->current();
        if ($validator !== null) {
            $this->validateHeader($validator, $headers);
        }

        while ($line = $parser->fetchNextLine()){
            if (!empty($line)) {
                $line = array_combine($headers, $line);
                $successLine = $processor->processLine($line);
                if (!$successLine) {
                    $missings[] = $line;
                }
            }
        }
        return array('success' => $success, 'missings' => $missings);
    }

}
