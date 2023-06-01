<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Currency;

use Brick\Money\Currency;
use Symfony\Component\Intl\Currencies;

class CurrencyFactory implements CurrencyFactoryInterface
{
    public function createISO(string $currencyCode): Currency
    {
        return new Currency($currencyCode, 0, Currencies::getName($currencyCode), Currencies::getFractionDigits($currencyCode));
    }

    public function createCustom(string $currencyCode, string $name, int $scale): Currency
    {
        return new Currency($currencyCode, 0, $name, $scale);
    }
}
