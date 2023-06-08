<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Currency;

use Ang3\Bundle\MoneyBundle\Currency\Exception\CurrencyException;
use Ang3\Bundle\MoneyBundle\Currency\Exception\CurrencyRegistryException;
use Brick\Money\Currency;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Intl\Currencies;

class CurrencyRegistry extends CurrencyCollection
{
    private readonly CurrencyFactoryInterface $currencyFactory;
    private ?Currency $defaultCurrency = null;

    public function __construct(CurrencyFactoryInterface $currencyFactory = null)
    {
        parent::__construct();
        $this->currencyFactory = $currencyFactory ?: new CurrencyFactory();
    }

    /**
     * @throws InvalidConfigurationException on configuration errors
     */
    public static function create(array $config, CurrencyFactoryInterface $currencyFactory = null): self
    {
        $registry = new self($currencyFactory);
        $currencies = [];

        if (true === $config['iso_currencies']['enabled']) {
            $ISOCurrencies = $config['iso_currencies']['codes'] ?? null;
            $ISOCurrencies = $ISOCurrencies ?: Currencies::getCurrencyCodes();

            foreach ($ISOCurrencies as $currencyCode) {
                $currencies[$currencyCode] = $registry->getCurrencyFactory()->createISO($currencyCode);
            }
        }

        foreach ($config['custom_currencies'] as $currencyCode => $parameters) {
            if ($registry->has($currencyCode)) {
                throw new InvalidConfigurationException('The custom currency with code "%s" is already configured as ISO currency.');
            }

            $currencies[$currencyCode] = $registry->getCurrencyFactory()->createCustom((string) $currencyCode, (string) $parameters['name'], (int) $parameters['scale']);
        }

        $defaultCurrency = $currencies[$config['default_currency']] ?? null;

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
        parent::add($currency);

        if (!$this->defaultCurrency) {
            $this->defaultCurrency = $currency;
        }

        return $this;
    }

    public function getCurrencyFactory(): CurrencyFactoryInterface
    {
        return $this->currencyFactory;
    }
}
