<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Decorator;

use Ang3\Bundle\MoneyBundle\Contracts\MoneyInterface;
use Ang3\Bundle\MoneyBundle\Currency\CurrencyRegistryProvider;
use Brick\Math\BigNumber;
use Brick\Math\RoundingMode;
use Brick\Money\AbstractMoney;
use Brick\Money\Money;
use Brick\Money\RationalMoney;
use Symfony\Component\Intl\Currencies;

/**
 * This class is a decorator or objects of type Brick\Money\AbstractMoney.
 */
abstract class AbstractMoneyDecorator implements MoneyDecoratorInterface
{
    /**
     * @var 0|1|2|3|4|5|6|7|8|9
     */
    private int $defaultRoundingMode = RoundingMode::DOWN;

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $defaultRoundingMode
     */
    public function __construct(Money|RationalMoney|MoneyInterface $decorated, int $defaultRoundingMode = null)
    {
        $this->setDecorated($decorated);
        $this->defaultRoundingMode = null !== $defaultRoundingMode ? $defaultRoundingMode : $this->defaultRoundingMode;
    }

    public function __toString(): string
    {
        return $this->getDecorated()->__toString();
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $defaultRoundingMode
     */
    abstract public static function create(Money|RationalMoney|MoneyInterface $decorated, int $defaultRoundingMode = null): MoneyDecoratorInterface;

    public function plus(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that, int $roundingMode = null): MoneyDecoratorInterface
    {
        return $this->newInstance($this->getDecorated()->plus($this->getAmountOf($that), $this->resolveRoundingMode($roundingMode)));
    }

    public function minus(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that, int $roundingMode = null): MoneyDecoratorInterface
    {
        return $this->newInstance($this->getDecorated()->minus($this->getAmountOf($that), $this->resolveRoundingMode($roundingMode)));
    }

    public function multipliedBy(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that, int $roundingMode = null): MoneyDecoratorInterface
    {
        return $this->newInstance($this->getDecorated()->multipliedBy($this->getAmountOf($that), $this->resolveRoundingMode($roundingMode)));
    }

    public function dividedBy(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that, int $roundingMode = null): MoneyDecoratorInterface
    {
        return $this->newInstance($this->getDecorated()->dividedBy($this->getAmountOf($that), $this->resolveRoundingMode($roundingMode)));
    }

    public function abs(): MoneyDecoratorInterface
    {
        return $this->isNegative() ? $this->newInstance($this->multipliedBy(-1)) : $this;
    }

    public function negated(): MoneyDecoratorInterface
    {
        return $this->isPositive() ? $this->newInstance($this->multipliedBy(-1)) : $this;
    }

    public function toZero(): MoneyDecoratorInterface
    {
        return $this->newInstance($this->getDecorated()->multipliedBy(0));
    }

    public function isZero(): bool
    {
        return $this->getDecorated()->isZero();
    }

    public function isPositive(): bool
    {
        return $this->getDecorated()->isPositive();
    }

    public function isPositiveOrZero(): bool
    {
        return $this->getDecorated()->isPositiveOrZero();
    }

    public function isNegative(): bool
    {
        return $this->getDecorated()->isNegative();
    }

    public function isNegativeOrZero(): bool
    {
        return $this->getDecorated()->isNegativeOrZero();
    }

    public function compareTo(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): int
    {
        return $this->getDecorated()->getAmount()->compareTo($this->getAmountOf($that));
    }

    public function isEqualTo(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): bool
    {
        return $this->getDecorated()->isEqualTo($this->getAmountOf($that));
    }

    public function isLessThan(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): bool
    {
        return $this->getDecorated()->isLessThan($this->getAmountOf($that));
    }

    public function isLessThanOrEqualTo(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): bool
    {
        return $this->getDecorated()->isLessThanOrEqualTo($this->getAmountOf($that));
    }

    public function isGreaterThan(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): bool
    {
        return $this->getDecorated()->isGreaterThan($this->getAmountOf($that));
    }

    public function isGreaterThanOrEqualTo(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): bool
    {
        return $this->getDecorated()->isGreaterThanOrEqualTo($this->getAmountOf($that));
    }

    public function isAmountAndCurrencyEqualTo(MoneyInterface|AbstractMoney $that): bool
    {
        /** @var AbstractMoney $money */
        $money = $this->getAbstractMoney($that);

        return $this->getDecorated()->getAmount()->isEqualTo($money->getAmount()) && $this->getDecorated()->getCurrency()->is($money->getCurrency());
    }

    public function getAmount(): string
    {
        return (string) $this->getDecorated()->getAmount();
    }

    public function setAmount(BigNumber|int|float|string $amount): MoneyDecoratorInterface
    {
        return $this->newInstance($this->getDecorated()->multipliedBy(0)->plus($amount));
    }

    public function getCurrency(): string
    {
        return (string) $this->getDecorated()->getCurrency();
    }

    public function isISOCurrency(): bool
    {
        return Currencies::exists($this->getCurrency());
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
    protected function newInstance(Money|RationalMoney|MoneyInterface $money): MoneyDecoratorInterface
    {
        return static::create($money, $this->defaultRoundingMode);
    }

    /**
     * @internal
     */
    protected function getAmountOf(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that): BigNumber|int|float|string
    {
        if ($that instanceof MoneyInterface) {
            return $that->getAmount();
        }

        if ($that instanceof AbstractMoney) {
            return $that->getAmount();
        }

        return $that;
    }

    /**
     * @internal
     */
    protected function getAbstractMoney(mixed $that): Money|RationalMoney|null
    {
        if ($that instanceof MoneyInterface) {
            $that = Money::of($that->getAmount(), CurrencyRegistryProvider::getRegistry()->get($that->getCurrency()));
        }

        if (($that instanceof Money) || ($that instanceof RationalMoney)) {
            return $that;
        }

        return null;
    }

    abstract public function getDecorated(): Money|RationalMoney;

    abstract public function setDecorated(Money|RationalMoney|MoneyInterface $decorated): static;
}
