<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Utils;

use Brick\Money\Currency;
use Symfony\Component\Intl\Currencies;

class CurrencyUtils
{
    public static function parse(string $currencyCode, int $scale = null): Currency
    {
        if (self::isISOCurrency($currencyCode)) {
            return Currency::of($currencyCode);
        }

        if (null === $scale) {
            throw new \InvalidArgumentException('Missing non-ISO currency scale - You must set a scale before calling this method.');
        }

        return new Currency($currencyCode, 0, $currencyCode, $scale);
    }

    public static function isISOCurrency(string $currencyCode): bool
    {
        return Currencies::exists($currencyCode);
    }
}
