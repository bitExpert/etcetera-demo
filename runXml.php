<?php
require 'src/bootstrap.php';

use bitExpert\Etcetera\Extractor\Config\YamlConfigReader;
use bitExpert\Etcetera\Extractor\StandardExtractorFactory;
use bitExpert\Etcetera\Extractor\Property\PropertyConverterTypeFactory;
use bitExpert\Etcetera\Processor\Processor;
use bitExpert\EtceteraDemo\Writer\CsvWriter;


$configReader = new YamlConfigReader();
$config = $configReader->read('config/repositories/extractor.yml');

// create the extractor factory
$extractorFactory = new StandardExtractorFactory();

//// add the propertyconverter factory with configured propertyconverters
$propertyConverterFactory = new PropertyConverterTypeFactory([]);
$extractorFactory->setPropertyConverterFactory($propertyConverterFactory);

// create the extractor using the factory and config
$extractor = $extractorFactory->create($config);

// instanciate the reader
$reader = new \bitExpert\Etcetera\Reader\File\Xml\GenericXmlReader();

// set the filename of the file to read
$reader->setFilename(__DIR__ . '/data/repositories.xml');
$reader->setOffset(0);
$reader->setRootNodeXPath('//repositories/repository');

// instanciate the writer
//$writer = new ConsoleWriter();
$writer = new CsvWriter(__DIR__ . '/out/repositories', ';');

// instanciate the processor using reader, extractor and writer
$processor = new Processor($reader, $extractor, $writer);
// run
$processor->process();
