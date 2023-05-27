<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Factory;

use Ang3\Bundle\MoneyBundle\Config\MoneyConfig;
use Ang3\Bundle\MoneyBundle\Entity\Price;
use Brick\Money\Currency;

class PriceFactory
{
    public function __construct(private readonly MoneyConfig $moneyConfig)
    {
    }

    public function create(int|float|null $amount, Currency|string|null $currency): Price
    {
        $currency = $currency ?: $this->moneyConfig->getDefaultCurrency();

        return Price::create($amount, $currency);
    }
}
