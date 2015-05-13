[![Build Status](https://travis-ci.org/christophehurpeau/php-importer.png?branch=master)](https://travis-ci.org/christophehurpeau/php-importer)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/christophehurpeau/php-importer/badges/quality-score.png?s=a32909ae9b0b21dcb8ac8c65c2ea4c5d7e85e520)](https://scrutinizer-ci.com/g/christophehurpeau/php-importer/)
[![Code Climate](https://codeclimate.com/github/christophehurpeau/php-importer/badges/gpa.svg)](https://codeclimate.com/github/christophehurpeau/php-importer)
[![Test Coverage](https://codeclimate.com/github/christophehurpeau/php-importer/badges/coverage.svg)](https://codeclimate.com/github/christophehurpeau/php-importer)

php-importer
============

Import and process files


## Example:


```php
namespace CountriesExample;

class CountriesCsvProcessor implements \Importer\HeaderValidator, \Importer\LineProcessor
{
  const HEADER_COUNTRY_NAME = 'country_name';

  /**
   * @return array|true
   */
  public function processFile($file) {

      $engine = new \Importer\Csv\Engine;
      $parser = new \Importer\Csv\Parser($file);
      return $engine->process($parser, $this, $this);
  }

  /**
     * @return array
     */
    public function getRequiredHeaders()
    {
        return array( self::HEADER_COUNTRY_NAME );
    }

    /**
     * @param array $line
     */
    public function processLine(array $line)
    {
        $countryName = $line[self::HEADER_COUNTRY];
        if (empty($countryName)) {
            return 'Country name  for country' . $countryName . 'is empty for line '.print_r($line, true);
        }
        echo $countryName . "\n";//do something
        return true; // everything went well
    }
}
```


### How to use


```php
ini_set('auto_detect_line_endings', true);
$countriesCsvProcessor = new CountriesCsvProcessor();
$result = $dataCountriesCsvProcessor->processFile(__DIR__ . '/../data/countries.csv');
if ($result !== true) {
    throw new \Exception('Failed lines: '. print_r($result, true));
}
```
