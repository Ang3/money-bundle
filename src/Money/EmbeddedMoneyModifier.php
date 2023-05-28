<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Money;

use Ang3\Bundle\MoneyBundle\Entity\EmbeddedMoney;
use Brick\Math\BigNumber;
use Brick\Math\Exception\MathException;
use Brick\Math\RoundingMode;
use Brick\Money\AbstractMoney;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;

class EmbeddedMoneyModifier
{
    private Money $money;

    /**
     * @var 0|1|2|3|4|5|6|7|8|9
     */
    private int $roundingMode;

    public function __construct(private readonly EmbeddedMoney $embeddedMoney, ?int $roundingMode = null)
    {
        $this->initialize();
        $this->setRoundingMode($roundingMode);
    }

    public function initialize(): void
    {
        $this->money = $this->embeddedMoney->getMoney($this->roundingMode);
    }

    /**
     * @return 0|1|2|3|4|5|6|7|8|9
     */
    public function getRoundingMode(): int
    {
        return $this->roundingMode;
    }

    public function setRoundingMode(?int $roundingMode = null): self
    {
        if (null !== $roundingMode && $roundingMode < 0 || $roundingMode > 9) {
            throw new \InvalidArgumentException(sprintf('The rounding mode value "%s" is not valid (min: 0 - max: 9).', $this->roundingMode));
        }

        $this->roundingMode = null !== $roundingMode ? $roundingMode : RoundingMode::DOWN;

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
     * @psalm-param RoundingMode::* $roundingMode
     *
     * @param EmbeddedMoney|AbstractMoney|BigNumber|int|float|string $that         the money or amount to add
     * @param int                                                    $roundingMode an optional RoundingMode constant
     *
     * @throws MathException          if the argument is an invalid number or rounding is necessary
     * @throws MoneyMismatchException if the argument is a money in a different currency or in a different context
     */
    public function plus(EmbeddedMoney|AbstractMoney|BigNumber|int|float|string $that, int $roundingMode = RoundingMode::UNNECESSARY): self
    {
        $this->money = $this->money->plus($this->getThat($that), $roundingMode ?: $this->roundingMode);

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
     * @psalm-param RoundingMode::* $roundingMode
     *
     * @param EmbeddedMoney|AbstractMoney|BigNumber|int|float|string $that         the money or amount to subtract
     * @param int                                                    $roundingMode an optional RoundingMode constant
     *
     * @throws MathException          if the argument is an invalid number or rounding is necessary
     * @throws MoneyMismatchException if the argument is a money in a different currency or in a different context
     */
    public function minus(EmbeddedMoney|AbstractMoney|BigNumber|int|float|string $that, int $roundingMode = RoundingMode::UNNECESSARY): self
    {
        $this->money = $this->money->minus($this->getThat($that), $roundingMode ?: $this->roundingMode);

        return $this;
    }

    /**
     * Returns the product of this Money and the given number.
     *
     * The resulting Money has the same context as this Money. If the result needs rounding to fit this context, a
     * rounding mode can be provided. If a rounding mode is not provided and rounding is necessary, an exception is
     * thrown.
     *
     * @psalm-param RoundingMode::* $roundingMode
     *
     * @param BigNumber|int|float|string $that         the multiplier
     * @param int                        $roundingMode an optional RoundingMode constant
     *
     * @throws MathException if the argument is an invalid number or rounding is necessary
     */
    public function multipliedBy(BigNumber|int|float|string $that, int $roundingMode = RoundingMode::UNNECESSARY): self
    {
        $this->money = $this->money->multipliedBy($that, $roundingMode ?: $this->roundingMode);

        return $this;
    }

    /**
     * Returns the result of the division of this Money by the given number.
     *
     * The resulting Money has the same context as this Money. If the result needs rounding to fit this context, a
     * rounding mode can be provided. If a rounding mode is not provided and rounding is necessary, an exception is
     * thrown.
     *
     * @psalm-param RoundingMode::* $roundingMode
     *
     * @param BigNumber|int|float|string $that         the divisor
     * @param int                        $roundingMode an optional RoundingMode constant
     *
     * @throws MathException if the argument is an invalid number or is zero, or rounding is necessary
     */
    public function dividedBy(BigNumber|int|float|string $that, int $roundingMode = RoundingMode::UNNECESSARY): self
    {
        $this->money = $this->money->dividedBy($that, $roundingMode ?: $this->roundingMode);

        return $this;
    }

    /**
     * Returns the quotient of the division of this Money by the given number.
     *
     * The given number must be a integer value. The resulting Money has the same context as this Money.
     * This method can serve as a basis for a money allocation algorithm.
     *
     * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigInteger.
     *
     * @throws MathException if the divisor cannot be converted to a BigInteger
     */
    public function quotient(BigNumber|int|float|string $that): self
    {
        $this->money = $this->money->quotient($that);

        return $this;
    }

    private function getThat(EmbeddedMoney|AbstractMoney|BigNumber|int|float|string $that): AbstractMoney|BigNumber|int|float|string
    {
        return $that instanceof EmbeddedMoney ? $that->getMoney() : $that;
    }

    public function getResult(): Money
    {
        return $this->money;
    }

    public function save(): EmbeddedMoney
    {
        $this->embeddedMoney->update($this->money);

        return $this->embeddedMoney;
    }
}
