<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Exchange;

use Ang3\Bundle\MoneyBundle\Exception\CurrencyNotFound;
use Ang3\Bundle\MoneyBundle\Money\CurrencyRegistry;
use Brick\Math\BigNumber;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Brick\Money\CurrencyConverter;
use Brick\Money\Exception\CurrencyConversionException;
use Brick\Money\ExchangeRateProvider\ConfigurableProvider;
use Brick\Money\Money;

class Exchanger
{
    private ConfigurableProvider $exchangeRateProvider;

    public function __construct(private CurrencyRegistry $currencyRegistry)
    {
        $this->setCurrencies($this->currencyRegistry);
    }

    /**
     * @throws CurrencyNotFound            When a currency was not found
     * @throws RoundingNecessaryException  When a rounding option is necessary
     * @throws CurrencyConversionException On conversion failure
     */
    public function exchange(Money $money, string $targetCurrency): Money
    {
        // Assert that the source currency exists
        $this->currencyRegistry->get($money->getCurrency()->getCurrencyCode());
        $targetCurrency = $this->currencyRegistry->get($targetCurrency);
        $converter = new CurrencyConverter($this->exchangeRateProvider);

        return $converter->convert($money, $targetCurrency, null, RoundingMode::DOWN);
    }

    public function addRate(string $sourceCurrency, string $targetCurrency, BigNumber|int|float|string $ratio): self
    {
        $this->exchangeRateProvider->setExchangeRate($sourceCurrency, $targetCurrency, $ratio);

        return $this;
    }

    /**
     * @throws CurrencyConversionException On conversion failure
     */
    public function getRate(string $sourceCurrency, string $targetCurrency): BigNumber|int|float|string
    {
        return $this->exchangeRateProvider->getExchangeRate($sourceCurrency, $targetCurrency);
    }

    public function getCurrencies(): CurrencyRegistry
    {
        return $this->currencyRegistry;
    }

    public function setCurrencies(CurrencyRegistry $currencyRegistry): self
    {
        $this->currencyRegistry = $currencyRegistry;
        $this->exchangeRateProvider = new ConfigurableProvider();

        return $this;
    }
}
