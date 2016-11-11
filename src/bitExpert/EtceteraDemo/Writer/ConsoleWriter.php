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

use bitExpert\Etcetera\Extractor\Extract\EntityExtract;
use bitExpert\Etcetera\Extractor\Extract\Extract;
use bitExpert\Etcetera\Extractor\Extract\RelationExtract;
use bitExpert\Etcetera\Reader\Meta;
use bitExpert\Etcetera\Writer\Writer;

final class ConsoleWriter implements Writer
{
    /**
     * @var int
     */
    private $entitiesWritten;
    /**
     * @var int
     */
    private $relationsWritten;

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
        $entities = $extract->getEntityExtracts();

        foreach ($entities as $entity) {
            echo $this->entityExtractToString($entity) . PHP_EOL;

            $this->entitiesWritten += 1;
        }

        $relations = $extract->getRelationExtracts();

        foreach ($relations as $relation) {
            echo $this->relationExtractToString($relation) . PHP_EOL;

            $this->relationsWritten += 1;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        echo 'TEARDOWN!' . PHP_EOL;
        echo sprintf('Wrote %s entities', $this->entitiesWritten) . PHP_EOL;
        echo sprintf('Wrote %s relations', $this->relationsWritten) . PHP_EOL;
    }

    /**
     * Represents the given entity extract in a readable format
     *
     * @param EntityExtract $extract
     * @return string
     */
    private function entityExtractToString(EntityExtract $extract)
    {
        return sprintf(
            '%s: %s',
            $extract->getType(),
            json_encode($extract->toArray())
        );
    }

    /**
     * Represents the given relation extract in a readable format
     *
     * @param RelationExtract $extract
     * @return string
     */
    private function relationExtractToString(RelationExtract $extract)
    {
        $info = $this->entityExtractToString($extract);
        $from = $extract->getFrom();
        $to = $extract->getTo();

        if ($from) {
            $info = sprintf('%s; from:%s', $info, $this->entityExtractToString($from));
        }

        if ($to) {
            $info = sprintf('%s; to:%s', $info, $this->entityExtractToString($to));
        }

        return $info;
    }
}
