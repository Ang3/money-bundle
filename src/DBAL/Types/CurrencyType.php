<?php

namespace Ang3\Bundle\MoneyBundle\DBAL\Types;

use Brick\Money\Currency;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

class CurrencyType extends StringType
{
    public const NAME = 'currency';

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

        return Currency::of($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
