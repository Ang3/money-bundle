<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Currency;

use Ang3\Bundle\MoneyBundle\Config\MoneyConfig;
use Ang3\Bundle\MoneyBundle\Exception\CurrencyException;
use Ang3\Bundle\MoneyBundle\Exception\CurrencyRegistryException;
use Brick\Money\Currency;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Intl\Currencies;

class CurrencyRegistry
{
    /**
     * @var array<string, Currency>
     */
    private array $currencies = [];

    public function __construct(private ?Currency $defaultCurrency = null)
    {
    }

    /**
     * @throws InvalidConfigurationException on configuration errors
     */
    public static function create(MoneyConfig $config, CurrencyFactoryInterface $currencyFactory = null): self
    {
        $registry = new self();
        $currencyFactory = $currencyFactory ?: new CurrencyFactory();
        $currencies = [];

        $ISOCurrencies = $config->getISOCurrencies() ?: Currencies::getCurrencyCodes();

        foreach ($ISOCurrencies as $currencyCode) {
            $currencies[$currencyCode] = $currencyFactory->createISO($currencyCode);
        }

        foreach ($config->getCustomCurrencies() as $currencyCode => $parameters) {
            if ($registry->has($currencyCode)) {
                throw new InvalidConfigurationException('The custom currency with code "%s" is already configured as ISO currency.');
            }

            $currencies[$currencyCode] = $currencyFactory->createCustom((string) $currencyCode, (string) $parameters['name'], (int) $parameters['scale']);
        }

        $defaultCurrency = $currencies[$config->getDefaultCurrency()] ?? null;

        if (!$defaultCurrency) {
            throw new InvalidConfigurationException('The default currency configured under "ang3_money.default_currency" is neither defined as ISO or custom currency.');
        }

	    foreach ($currencies as $currency) {
		    $registry->add($currency);
	    }

        $registry->setDefaultCurrency($defaultCurrency);

        return $registry;
    }

    /**
     * @throws CurrencyException when the currency was not found
     */
    public function setDefaultCurrency(Currency $currency): self
    {
        if (!$this->contains($currency)) {
            throw CurrencyException::notFound($currency->getCurrencyCode());
        }

        $this->defaultCurrency = $currency;

        return $this;
    }

    /**
     * @throws CurrencyRegistryException when no default currency registered
     */
    public function getDefaultCurrency(): Currency
    {
        if (!$this->defaultCurrency) {
            throw CurrencyRegistryException::noDefaultCurrency();
        }

        return $this->defaultCurrency;
    }

    public function add(Currency $currency): self
    {
        $this->currencies[$currency->getCurrencyCode()] = $currency;

        if (!$this->defaultCurrency) {
            $this->defaultCurrency = $currency;
        }

        return $this;
    }

    /**
     * @throws CurrencyException when the currency was not found
     */
    public function get(string $currencyCode): Currency
    {
        $currency = $this->currencies[$currencyCode] ?? null;

        if (!$currency) {
            throw CurrencyException::notFound($currencyCode);
        }

        return $currency;
    }

    public function contains(Currency $currency): bool
    {
        return $this->has($currency->getCurrencyCode());
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
