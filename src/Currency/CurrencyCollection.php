<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Currency;

use Ang3\Bundle\MoneyBundle\Exception\CurrencyException;
use Brick\Money\Currency;

class CurrencyCollection
{
    /**
     * @param array<string, Currency> $currencies
     */
    public function __construct(protected array $currencies = [])
    {
        foreach ($currencies as $currency) {
            $this->add($currency);
        }
    }

    public function add(Currency $currency): self
    {
        $this->currencies[$currency->getCurrencyCode()] = $currency;

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
     * @return string[]
     */
    public function getCodes(): array
    {
        return $this->map(fn (Currency $currency) => $currency->getCurrencyCode());
    }

    public function filter(callable $predicate): self
    {
        return new self(array_filter($this->currencies, $predicate));
    }

    public function map(callable $callable): array
    {
        return array_map($callable, $this->currencies);
    }

    /**
     * @return array<string, Currency>
     */
    public function toArray(): array
    {
        return $this->currencies;
    }

    public function count(): int
    {
        return \count($this->currencies);
    }
}
