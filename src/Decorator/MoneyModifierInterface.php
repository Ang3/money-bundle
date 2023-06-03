<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Decorator;

use Ang3\Bundle\MoneyBundle\Contracts\MoneyAwareInterface;
use Brick\Math\BigNumber;
use Brick\Money\AbstractMoney;
use Brick\Money\Currency;
use Brick\Money\Money;
use Brick\Money\RationalMoney;

interface MoneyModifierInterface
{
    public function __toString(): string;

    public function plus(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): self;

    public function minus(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): self;

    public function multipliedBy(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): self;

    public function dividedBy(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): self;

    public function abs(): self;

    public function negated(): self;

    public function toZero(): self;

    public function isZero(): bool;

    public function isPositive(): bool;

    public function isPositiveOrZero(): bool;

    public function isNegative(): bool;

    public function isNegativeOrZero(): bool;

    public function isEqualTo(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): bool;

    public function isLessThan(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): bool;

    public function isLessThanOrEqualTo(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): bool;

    public function isGreaterThan(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): bool;

    public function isGreaterThanOrEqualTo(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): bool;

    public function isAmountAndCurrencyEqualTo(MoneyAwareInterface|AbstractMoney $that): bool;

    public function getMoney(): RationalMoney;

    public function setMoney(Money|RationalMoney $money): self;

    public function getAmount(): BigNumber;

    public function setAmount(BigNumber|int|float|string $amount): self;

    public function getCurrency(): Currency;

    public function isISOCurrency(): bool;

    public function getResult(int $roundingMode = null): Money;
}
