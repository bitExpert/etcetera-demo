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
namespace bitExpert\EtceteraDemo\Extractor\Property\Filter;

use bitExpert\Etcetera\Extractor\Property\PropertyFilter;
use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;

final class RestrictedEmailDomainFilter implements PropertyFilter
{
    /**
     * @var array
     */
    private $validDomains;

    /**
     * RestrictedEmailDomainFilter constructor.
     * @param array $validDomains
     */
    public function __construct(array $validDomains)
    {
        $normalizedDomains = array_map(array($this, 'normalize'), $validDomains);
        $this->validDomains = $normalizedDomains;
    }

    /**
     * {@inheritDoc}
     */
    public function filter(ValueDescriptor $valueDescriptor): bool
    {
        $value = $valueDescriptor->getValue();
        $email = $this->normalize($value);

        foreach ($this->validDomains as $validDomain) {
            if (strpos($email, sprintf('@%s', $validDomain))) {
                return true;
            }
        }

        return false;
    }

    private function normalize(string $value) : string
    {
        return strtolower($value);
    }
}
