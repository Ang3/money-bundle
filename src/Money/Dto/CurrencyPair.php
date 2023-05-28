<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Money\Dto;

use Brick\Money\Currency;

class CurrencyPair
{
    public function __construct(private readonly Currency $source, private readonly Currency $target)
    {
    }

    public function __toString(): string
    {
        return "{$this->source}{$this->target}";
    }

    public static function create(Currency $sourceCurrency, Currency $targetCurrency): self
    {
        return new self($sourceCurrency, $targetCurrency);
    }

    public function getSource(): Currency
    {
        return $this->source;
    }

    public function getTarget(): Currency
    {
        return $this->target;
    }

    public function isRelatedToCurrency(string $currencyCode): bool
    {
        return \in_array($currencyCode, [$this->source->getCurrencyCode(), $this->target->getCurrencyCode()], true);
    }
}
