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
namespace bitExpert\EtceteraDemo\Extractor\Entity\Decorator;

use bitExpert\Etcetera\Extractor\Entity\EntityDecorator;
use bitExpert\Etcetera\Extractor\Extract\PropertyExtract;
use Ramsey\Uuid\Uuid;

final class Uuid4Decorator implements EntityDecorator
{
    /**
     * @var string
     */
    private $propertyName;

    /**
     * UuidDecorator constructor.
     * @param string $propertyName
     */
    public function __construct(string $propertyName)
    {
        $this->propertyName = $propertyName;
    }

    /**
     * {@inheritDoc}
     */
    public function decorate(array $propertyExtracts, array $values): PropertyExtract
    {
        return new PropertyExtract($this->propertyName, Uuid::uuid4()->toString());
    }
}
