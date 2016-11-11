<?php
declare(strict_types = 1);

/*
 * This file is part of the EtceteraDemo package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bitExpert\EtceteraDemo\Writer;

use bitExpert\Etcetera\Extractor\Extract\Extract;
use bitExpert\Etcetera\Reader\Meta;
use bitExpert\Etcetera\Writer\Writer;

final class CsvWriter implements Writer
{
    /**
     * @var string
     */
    private $outputPath;
    private $delimiter;
    private $enclosure;
    private $escapeChar;
    private $fileHandles;
    private $headersWritten;
    private $entitiesWritten;
    private $relationsWritten;

    public function __construct(
        string $outputPath,
        string $delimiter = ',',
        string $enclosure = '"',
        string $escapeChar = '\\'
    ) {
        $this->outputPath = $outputPath;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escapeChar = $escapeChar;
        $this->headersWritten = [];
        $this->fileHandles = [];
    }

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->entitiesWritten = 0;
        $this->relationsWritten = 0;

        echo 'SETUP!' . PHP_EOL;
    }

    /**
     * {@inheritDoc}
     */
    public function write(Extract $extract, Meta $meta)
    {
        $this->writeEntityExtracts($extract->getEntityExtracts(), $this->entitiesWritten);
        $this->writeEntityExtracts($extract->getRelationExtracts(), $this->relationsWritten);
    }

    /**
     * Writes given extracts to CSV and increases the given count by reference
     *
     * @param array $extracts
     * @param int $count
     */
    private function writeEntityExtracts(array $extracts, int &$count)
    {
        foreach ($extracts as $extract) {
            $type = $extract->getType();
            $handle = $this->getFileHandleForType($type);

            if (!$this->isHeaderWrittenForType($type)) {
                $header = array_keys($extract->getPropertyExtracts());
                $this->writeCsv($handle, $header);
                $this->markHeaderAsWrittenForType($type);
            }

            $this->writeCsv($handle, $extract->toArray());

            $count += 1;
        }
    }

    /**
     * Marks header as written for given type
     *
     * @param string $type
     * @return bool
     */
    private function isHeaderWrittenForType($type) : bool
    {
        return in_array($type, $this->headersWritten);
    }

    /**
     * Returns whether header already has been written for given type
     *
     * @param string $type
     */
    private function markHeaderAsWrittenForType(string $type)
    {
        array_push($this->headersWritten, $type);
    }

    /**
     * Writes given data as csv to given handle
     *
     * @param Extract $handle
     * @param array $data
     */
    private function writeCsv($handle, array $data)
    {
        fputcsv($handle, $data, $this->delimiter, $this->enclosure, $this->escapeChar);
    }

    /**
     * @param $type
     * @return mixed
     */
    private function getFileHandleForType(string $type)
    {
        if (!isset($this->fileHandles[$type])) {
            $this->fileHandles[$type] = fopen(sprintf('%s/%s.csv', $this->outputPath, $type), 'w');
        }

        return $this->fileHandles[$type];
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        echo 'TEARDOWN!' . PHP_EOL;
        echo sprintf('Wrote %s entities', $this->entitiesWritten) . PHP_EOL;
        echo sprintf('Wrote %s relations', $this->relationsWritten) . PHP_EOL;

        foreach ($this->fileHandles as $handle) {
            fclose($handle);
        }
    }
}
