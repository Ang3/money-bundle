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
use Ang3\Bundle\MoneyBundle\Contracts\MoneyInterface;
use Ang3\Bundle\MoneyBundle\Currency\CurrencyRegistryProvider;
use Brick\Math\BigNumber;
use Brick\Math\RoundingMode;
use Brick\Money\AbstractMoney;
use Brick\Money\Context;
use Brick\Money\Currency;
use Brick\Money\Money;
use Brick\Money\RationalMoney;
use Symfony\Component\Intl\Currencies;

/**
 * This class allows money calculations and keep the result in memory.
 * All calculations are done with rational money to avoid rounding errors.
 */
class MoneyModifier implements MoneyAwareInterface
{
    protected RationalMoney $money;
    protected ?Context $context = null;
    private RationalMoney $initialMoney;

    /**
     * @var 0|1|2|3|4|5|6|7|8|9
     */
    private int $defaultRoundingMode = RoundingMode::DOWN;

    /**
     * @param 0|1|2|3|4|5|6|7|8|9 $defaultRoundingMode
     */
    protected function __construct(Money|RationalMoney|MoneyInterface $money, int $defaultRoundingMode = null)
    {
        $this->setMoney($money);
        $this->defaultRoundingMode = $defaultRoundingMode ?: $this->defaultRoundingMode;
    }

    public function __toString(): string
    {
        return $this->money->__toString();
    }

    public function plus(MoneyAwareInterface|MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): static
    {
        $this->money = $this->money->plus($this->getAmountOf($that));

        return $this;
    }

    public function minus(MoneyAwareInterface|MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): static
    {
        $this->money = $this->money->minus($this->getAmountOf($that));

        return $this;
    }

    public function multipliedBy(MoneyAwareInterface|MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): static
    {
        $this->money = $this->money->multipliedBy($this->getAmountOf($that));

        return $this;
    }

    public function dividedBy(MoneyAwareInterface|MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): static
    {
        $this->money = $this->money->dividedBy($this->getAmountOf($that));

        return $this;
    }

    public function abs(): static
    {
        return $this->isNegative() ? $this->multipliedBy(-1) : $this;
    }

    public function negated(): static
    {
        return $this->isPositive() ? $this->multipliedBy(-1) : $this;
    }

    public function toZero(): static
    {
        return $this->setAmount(0);
    }

    public function isZero(): bool
    {
        return $this->money->isZero();
    }

    public function isPositive(): bool
    {
        return $this->money->isPositive();
    }

    public function isPositiveOrZero(): bool
    {
        return $this->money->isPositiveOrZero();
    }

    public function isNegative(): bool
    {
        return $this->money->isNegative();
    }

    public function isNegativeOrZero(): bool
    {
        return $this->money->isNegativeOrZero();
    }

    public function compareTo(MoneyAwareInterface|MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): int
    {
        return $this->money->getAmount()->compareTo($this->getAmountOf($that));
    }

    public function isEqualTo(MoneyAwareInterface|MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): bool
    {
        return $this->money->isEqualTo($this->getAmountOf($that));
    }

    public function isLessThan(MoneyAwareInterface|MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): bool
    {
        return $this->money->isLessThan($this->getAmountOf($that));
    }

    public function isLessThanOrEqualTo(MoneyAwareInterface|MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): bool
    {
        return $this->money->isLessThanOrEqualTo($this->getAmountOf($that));
    }

    public function isGreaterThan(MoneyAwareInterface|MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): bool
    {
        return $this->money->isGreaterThan($this->getAmountOf($that));
    }

    public function isGreaterThanOrEqualTo(MoneyAwareInterface|MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): bool
    {
        return $this->money->isGreaterThanOrEqualTo($this->getAmountOf($that));
    }

    public function isAmountAndCurrencyEqualTo(MoneyAwareInterface|MoneyInterface|AbstractMoney $that): bool
    {
        /** @var AbstractMoney $money */
        $money = $this->getAbstractMoney($that);

        return $this->money->getAmount()->isEqualTo($money->getAmount()) && $this->getCurrency()->is($money->getCurrency());
    }

    public function getMoney(): RationalMoney
    {
        return $this->money;
    }

    public function setMoney(Money|RationalMoney|MoneyInterface $money): static
    {
        if ($money instanceof MoneyInterface) {
            $money = Money::of($money->getAmount(), CurrencyRegistryProvider::getRegistry()->get($money->getCurrency()));
        }

        if ($money instanceof Money) {
            $this->context = $money->getContext();
            $money = $money->toRational();
        }

        $this->money = $money;
        $this->initialMoney = Money::of($money->getAmount(), $money->getCurrency())->toRational();

        return $this;
    }

    public function getAmount(): BigNumber
    {
        return $this->money->getAmount();
    }

    public function setAmount(BigNumber|int|float|string $amount): static
    {
        $this->money = $this->money->multipliedBy(0)->plus($amount);

        return $this;
    }

    public function getCurrency(): Currency
    {
        return $this->money->getCurrency();
    }

    public function isISOCurrency(): bool
    {
        return Currencies::exists($this->getCurrency()->getCurrencyCode());
    }

    /**
     * @return 0|1|2|3|4|5|6|7|8|9
     */
    public function getDefaultRoundingMode(): int
    {
        return $this->defaultRoundingMode;
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9 $defaultRoundingMode
     */
    public function setDefaultRoundingMode(int $defaultRoundingMode): self
    {
        $this->defaultRoundingMode = $defaultRoundingMode;

        return $this;
    }

    public function reset(): self
    {
        $this->money = $this->initialMoney;

        return $this;
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $roundingMode
     */
    public function getResult(int $roundingMode = null): Money
    {
        return $this->money->to($this->context ?: new Context\DefaultContext(), $this->resolveRoundingMode($roundingMode));
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $roundingMode
     *
     * @return 0|1|2|3|4|5|6|7|8|9
     */
    protected function resolveRoundingMode(int $roundingMode = null): int
    {
        return $roundingMode ?: $this->defaultRoundingMode;
    }

    /**
     * @internal
     */
    protected function getAmountOf(MoneyAwareInterface|MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): BigNumber|int|float|string
    {
        if ($that instanceof MoneyInterface) {
            return $that->getAmount();
        }

        return $this->getAbstractMoney($that)?->getAmount() ?: $that;
    }

    /**
     * @internal
     */
    protected function getAbstractMoney(mixed $that): ?AbstractMoney
    {
        if ($that instanceof MoneyInterface) {
            $that = Money::of($that->getAmount(), CurrencyRegistryProvider::getRegistry()->get($that->getCurrency()));
        }

        if ($that instanceof MoneyAwareInterface) {
            $that = $that->getMoney();
        }

        return $that instanceof AbstractMoney ? $that : null;
    }
}
