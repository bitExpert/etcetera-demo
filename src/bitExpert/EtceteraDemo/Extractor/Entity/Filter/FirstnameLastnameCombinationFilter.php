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
namespace bitExpert\EtceteraDemo\Extractor\Entity\Filter;

use bitExpert\Etcetera\Extractor\Entity\EntityFilter;

final class FirstnameLastnameCombinationFilter implements EntityFilter
{
    /**
     * @var array
     */
    private $blacklist;

    public function __construct(array $blacklist)
    {
        $this->blacklist = $blacklist;
    }

    /**
     * {@inheritDoc}
     */
    public function filter(array $propertyExtracts, array $values) : bool
    {
        $firstname = $propertyExtracts['firstname']->getValue();
        $lastname = $propertyExtracts['lastname']->getValue();

        foreach ($this->blacklist as $entry) {
            if ($entry['firstname'] === $firstname && $entry['lastname'] === $lastname) {
                return false;
            }
        }

        return true;
    }
}
