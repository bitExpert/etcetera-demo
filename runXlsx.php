<?php
require 'src/bootstrap.php';

use bitExpert\Etcetera\Extractor\Config\YamlConfigReader;

use bitExpert\Etcetera\Extractor\Config\JsonConfigReader;
use bitExpert\Etcetera\Extractor\StandardExtractorFactory;
use bitExpert\Etcetera\Extractor\Property\PropertyConverterTypeFactory;
use bitExpert\Etcetera\Processor\Processor;
use bitExpert\Etcetera\Extractor\Entity\EntityDecoratorTypeFactory;
use bitExpert\Etcetera\Reader\File\Excel\Excel2007Reader;
use bitExpert\EtceteraDemo\Writer\ConsoleWriter;
use bitExpert\EtceteraDemo\Writer\CsvWriter;
use bitExpert\EtceteraDemo\Extractor\Property\Converter\ToLowercaseConverter;
use bitExpert\EtceteraDemo\Extractor\Property\Filter\RestrictedEmailDomainFilter;
use bitExpert\Etcetera\Extractor\Property\PropertyFilterTypeFactory;
use bitExpert\Etcetera\Extractor\Entity\EntityFilterTypeFactory;
use bitExpert\EtceteraDemo\Extractor\Entity\Filter\FirstnameLastnameCombinationFilter;
use bitExpert\EtceteraDemo\Extractor\Entity\Decorator\Uuid4Decorator;

/**
 * You may either use Yaml or Json to configure the extractor
 */
/*$configReader = new YamlConfigReader();
$config = $configReader->read($currentDir . '/config/people/extractor.yml');*/

$configReader = new JsonConfigReader();
$config = $configReader->read('config/people/extractor.json');

// create the extractor factory
$extractorFactory = new StandardExtractorFactory();

// add the propertyconverter factory with configured propertyconverters
$propertyConverterFactory = new PropertyConverterTypeFactory([
    'toLowercase' => ToLowercaseConverter::class
]);
$extractorFactory->setPropertyConverterFactory($propertyConverterFactory);

// add propertyfilter factory with configured propertyfilters
$propertyFilterFactory = new PropertyFilterTypeFactory([
    'restrictToYahoo' => new RestrictedEmailDomainFilter([
        'yahoo.com'
    ])
]);
$extractorFactory->setPropertyFilterFactory($propertyFilterFactory);

// add entityfilter factory with configured entityfilters
$entityFilterFactory = new EntityFilterTypeFactory([
    'firstnamelastname' => new FirstnameLastnameCombinationFilter([
        [
            'firstname' => 'Kennedy',
            'lastname' => 'Weissnat'
        ],
        [
            'firstname' => 'Camila',
            'lastname' => 'Zulauf'
        ],
        [
            'firstname' => 'Jennie',
            'lastname' => 'Goyette'
        ]
    ])
]);
$extractorFactory->setEntityFilterFactory($entityFilterFactory);

// add entitydecorator factory with configured entitydecorators
$entityDecoratorFactory = new EntityDecoratorTypeFactory([
    'uuid' => new Uuid4Decorator('id')
]);
$extractorFactory->setEntityDecoratorFactory($entityDecoratorFactory);

// create the extractor using the factory and config
$extractor = $extractorFactory->create($config);

// instanciate the reader
$reader = new Excel2007Reader();
// set the filename of the file to read
$reader->setFilename(__DIR__ . '/data/people.xlsx');

// instanciate the writer
//$writer = new ConsoleWriter();
$writer = new CsvWriter(__DIR__ . '/out/people', ';');

// instanciate the processor using reader, extractor and writer
$processor = new Processor($reader, $extractor, $writer);
// run
$processor->process();
