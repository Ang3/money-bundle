<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Factory;

use Ang3\Bundle\MoneyBundle\Currency\CurrencyRegistry;
use Ang3\Bundle\MoneyBundle\Entity\EmbeddedMoney;
use Brick\Money\Currency;

class MoneyEmbeddableFactory
{
    public function __construct(private readonly CurrencyRegistry $currencyRegistry)
    {
    }

    public function create(int|float|string|null $amount = null, Currency|string|null $currency = null, bool $isMinor = true): EmbeddedMoney
    {
        if (null !== $currency) {
            $currency = $currency instanceof Currency ? $currency : $this->currencyRegistry->get($currency);
        } else {
            $currency = $this->currencyRegistry->getDefaultCurrency();
        }

        return null !== $amount ? EmbeddedMoney::create($amount, $currency, $isMinor) : EmbeddedMoney::zero($currency);
    }
}
