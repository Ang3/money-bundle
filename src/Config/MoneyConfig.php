<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Config;

use Symfony\Component\Intl\Currencies;

final class MoneyConfig
{
    public function __construct(private readonly array $parameters)
    {
    }

    public function getISOCurrencies(): array
    {
        $ISOCurrenciesEnabled = (bool) $this->parameters['iso_currencies']['enabled'];

        if (!$ISOCurrenciesEnabled) {
            return [];
        }

        return $this->parameters['iso_currencies']['codes'] ?: Currencies::getCurrencyCodes();
    }

    public function getCustomCurrencies(): array
    {
        return $this->parameters['custom_currencies'];
    }

    public function getDefaultCurrency(): string
    {
        return $this->parameters['default_currency'];
    }
}
