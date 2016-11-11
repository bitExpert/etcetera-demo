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
namespace bitExpert\EtceteraDemo\Extractor\Property\Converter;

use bitExpert\Etcetera\Extractor\Property\PropertyConverter;
use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;

final class ToLowercaseConverter implements PropertyConverter
{
    public function convert(ValueDescriptor $valueDescriptor)
    {
        $value = $valueDescriptor->getValue();
        return strtolower($value);
    }
}
