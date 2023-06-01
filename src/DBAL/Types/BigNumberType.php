<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\DBAL\Types;

use Brick\Math\BigNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

class BigNumberType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (!$value) {
            return null;
        }

        if (!$value instanceof BigNumber) {
            throw ConversionException::conversionFailedInvalidType($value, self::class, [BigNumber::class]);
        }

        return (string) $value;
    }

    /**
     * @param string|null $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?BigNumber
    {
        if (!$value) {
            return null;
        }

        return BigNumber::of($value);
    }
}
