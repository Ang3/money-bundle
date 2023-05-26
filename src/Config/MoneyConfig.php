<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Config;

use Ang3\Bundle\MoneyBundle\Entity\Price;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Intl\Currencies;

final class MoneyConfig
{
    private static ?array $currencies = null;

    public function __construct(#[Autowire('%ang3_money.config%')] private readonly array $parameters)
    {
        // We get money parameters, so we hydrate price default currency right now, it's great.
        Price::setDefaultCurrency($this->getDefaultCurrency());
    }

    public function getCurrencies(): array
    {
        if (null === self::$currencies) {
            $currencyCodes = $this->parameters['currencies'] ?: Currencies::getCurrencyCodes();
            self::$currencies = array_combine($currencyCodes, array_map(fn ($currencyCode) => ucfirst(Currencies::getName($currencyCode)), $currencyCodes));
        }

        return self::$currencies;
    }

    public function getDefaultCurrency(): string
    {
        return $this->parameters['default_currency'];
    }
}
