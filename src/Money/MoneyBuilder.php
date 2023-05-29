<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Money;

use Brick\Math\BigNumber;
use Brick\Math\Exception\MathException;
use Brick\Math\RoundingMode;
use Brick\Money\AbstractMoney;
use Brick\Money\Context;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;
use Brick\Money\RationalMoney;

class MoneyBuilder
{
    protected RationalMoney $rationalMoney;
    protected Context $context;

    /**
     * @var 0|1|2|3|4|5|6|7|8|9
     */
    protected int $roundingMode = RoundingMode::DOWN;

    public function __construct(private readonly Money $money, int $roundingMode = null)
    {
        $this->initialize();

        if ($roundingMode) {
            $this->setRoundingMode($roundingMode);
        }
    }

    public static function create(Money $money, int $roundingMode = null): self
    {
        return new self($money, $roundingMode);
    }

    public function initialize(): void
    {
        $this->rationalMoney = $this->money->toRational();
        $this->context = $this->money->getContext();
    }

    /**
     * @return 0|1|2|3|4|5|6|7|8|9
     */
    public function getRoundingMode(): int
    {
        return $this->roundingMode;
    }

    public function setRoundingMode(int $roundingMode): self
    {
        if ($roundingMode < 0 || $roundingMode > 9) {
            throw new \InvalidArgumentException(sprintf('The rounding mode value "%s" is not valid (min: 0 - max: 9).', $this->roundingMode));
        }

        $this->roundingMode = $roundingMode;

        return $this;
    }

    /**
     * Returns the sum of this Money and the given amount.
     *
     * If the operand is a Money, it must have the same context as this Money, or an exception is thrown.
     * This is by design, to ensure that contexts are not mixed accidentally.
     * If you do need to add a Money in a different context, you can use `plus($money->toRational())`.
     *
     * The resulting Money has the same context as this Money. If the result needs rounding to fit this context, a
     * rounding mode can be provided. If a rounding mode is not provided and rounding is necessary, an exception is
     * thrown.
     *
     * @param MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that the money or amount to add
     *
     * @throws MathException          if the argument is an invalid number or rounding is necessary
     * @throws MoneyMismatchException if the argument is a money in a different currency or in a different context
     */
    public function plus(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): self
    {
        $this->rationalMoney = $this->rationalMoney->plus($this->getThat($that));

        return $this;
    }

    /**
     * Returns the difference of this Money and the given amount.
     *
     * If the operand is a Money, it must have the same context as this Money, or an exception is thrown.
     * This is by design, to ensure that contexts are not mixed accidentally.
     * If you do need to subtract a Money in a different context, you can use `minus($money->toRational())`.
     *
     * The resulting Money has the same context as this Money. If the result needs rounding to fit this context, a
     * rounding mode can be provided. If a rounding mode is not provided and rounding is necessary, an exception is
     * thrown.
     *
     * @param MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that the money or amount to subtract
     *
     * @throws MathException          if the argument is an invalid number or rounding is necessary
     * @throws MoneyMismatchException if the argument is a money in a different currency or in a different context
     */
    public function minus(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): self
    {
        $this->rationalMoney = $this->rationalMoney->minus($this->getThat($that));

        return $this;
    }

    /**
     * Returns the product of this Money and the given number.
     *
     * The resulting Money has the same context as this Money. If the result needs rounding to fit this context, a
     * rounding mode can be provided. If a rounding mode is not provided and rounding is necessary, an exception is
     * thrown.
     *
     * @param BigNumber|int|float|string $that the multiplier
     *
     * @throws MathException if the argument is an invalid number or rounding is necessary
     */
    public function multipliedBy(BigNumber|int|float|string $that): self
    {
        $this->rationalMoney = $this->rationalMoney->multipliedBy($that);

        return $this;
    }

    /**
     * Returns the result of the division of this Money by the given number.
     *
     * The resulting Money has the same context as this Money. If the result needs rounding to fit this context, a
     * rounding mode can be provided. If a rounding mode is not provided and rounding is necessary, an exception is
     * thrown.
     *
     * @param BigNumber|int|float|string $that the divisor
     *
     * @throws MathException if the argument is an invalid number or is zero, or rounding is necessary
     */
    public function dividedBy(BigNumber|int|float|string $that): self
    {
        $this->rationalMoney = $this->rationalMoney->dividedBy($that);

        return $this;
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $roundingMode
     */
    public function getResult(int $roundingMode = null): Money
    {
        return $this->rationalMoney->to($this->context, $roundingMode ?: $this->roundingMode);
    }

    /**
     * @internal
     */
    private function getThat(MoneyAwareInterface|AbstractMoney|BigNumber|int|float|string $that): AbstractMoney|BigNumber|int|float|string
    {
        return $that instanceof MoneyAwareInterface ? $that->getMoney() : $that;
    }
}
