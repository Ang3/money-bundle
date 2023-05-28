<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Money;

use Ang3\Bundle\MoneyBundle\Config\MoneyConfig;
use Ang3\Bundle\MoneyBundle\Exception\CurrencyNotFound;
use Brick\Money\Currency;
use Symfony\Component\Intl\Currencies;

class CurrencyRegistry
{
    /**
     * @var array<string, Currency>
     */
    private array $currencies = [];
    private Currency $defaultCurrency;

    /**
     * @throws CurrencyNotFound when the default currency was not found
     */
    public static function create(MoneyConfig $config): void
    {
        $registry = new self();
        $registry->currencies = [];
        $ISOCurrencies = $config->getISOCurrencies() ?: Currencies::getCurrencyCodes();

        foreach ($ISOCurrencies as $currencyCode) {
            $registry->add(Currency::of($currencyCode));
        }

        foreach ($config->getCustomCurrencies() as $currencyCode => $parameters) {
            $registry->add(new Currency((string) $currencyCode, 0, (string) $parameters['name'], (int) $parameters['scale']));
        }

        $registry->setDefaultCurrency($config->getDefaultCurrency());
    }

    /**
     * @throws CurrencyNotFound when the currency code was not found
     */
    public function setDefaultCurrency(Currency|string $currency): self
    {
        $currencyCode = $currency instanceof Currency ? $currency->getCurrencyCode() : $currency;

        if (!$this->has($currencyCode)) {
            throw new CurrencyNotFound($currencyCode);
        }

        $this->defaultCurrency = $this->get($currencyCode);

        return $this;
    }

    public function getDefaultCurrency(): Currency
    {
        return $this->defaultCurrency;
    }

    public function add(Currency $currency): self
    {
        $this->currencies[$currency->getCurrencyCode()] = $currency;

        return $this;
    }

    /**
     * @throws CurrencyNotFound when the currency code was not found
     */
    public function get(string $currencyCode): Currency
    {
        $currency = $this->currencies[$currencyCode] ?? null;

        if (!$currency) {
            throw new CurrencyNotFound($currencyCode);
        }

        return $currency;
    }

    public function has(string $currencyCode): bool
    {
        return \array_key_exists($currencyCode, $this->currencies);
    }

    public function getChoices(): array
    {
        return array_combine(
            array_map(fn (Currency $currency) => $currency->getName(), $this->currencies),
            array_map(fn (Currency $currency) => $currency->getCurrencyCode(), $this->currencies)
        );
    }

    /**
     * @return array<string, Currency>
     */
    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    public function count(): int
    {
        return \count($this->currencies);
    }
}
