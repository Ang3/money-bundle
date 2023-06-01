<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\DBAL\Types;

use Ang3\Bundle\MoneyBundle\Currency\CurrencyRegistryProvider;
use Brick\Money\Currency;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

class CurrencyType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (!$value) {
            return null;
        }

        if (!$value instanceof Currency) {
            throw ConversionException::conversionFailedInvalidType($value, self::class, [Currency::class]);
        }

        return $value->getCurrencyCode();
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Currency
    {
        if (!$value) {
            return null;
        }

        return CurrencyRegistryProvider::getRegistry()->get($value);
    }
}
