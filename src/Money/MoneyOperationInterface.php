<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Money;

use Ang3\Bundle\MoneyBundle\Enum\RoundingMode;
use Brick\Math\Exception\MathException;
use Brick\Money\Exception\MoneyMismatchException;

interface MoneyOperationInterface
{
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
     * @param mixed $that the money or amount to add
     *
     * @throws MathException          if the argument is an invalid number or rounding is necessary
     * @throws MoneyMismatchException if the argument is a money in a different currency or in a different context
     */
    public function plus(mixed $that, ?RoundingMode $roundingMode = null): self;

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
     * @param mixed $that the money or amount to subtract
     *
     * @throws MathException          if the argument is an invalid number or rounding is necessary
     * @throws MoneyMismatchException if the argument is a money in a different currency or in a different context
     */
    public function minus(mixed $that, ?RoundingMode $roundingMode = null): self;

    /**
     * Returns the product of this Money and the given number.
     *
     * The resulting Money has the same context as this Money. If the result needs rounding to fit this context, a
     * rounding mode can be provided. If a rounding mode is not provided and rounding is necessary, an exception is
     * thrown.
     *
     * @param mixed $that the multiplier
     *
     * @throws MathException if the argument is an invalid number or rounding is necessary
     */
    public function multipliedBy(mixed $that, ?RoundingMode $roundingMode = null): self;

    /**
     * Returns the result of the division of this Money by the given number.
     *
     * The resulting Money has the same context as this Money. If the result needs rounding to fit this context, a
     * rounding mode can be provided. If a rounding mode is not provided and rounding is necessary, an exception is
     * thrown.
     *
     * @param mixed $that the divisor
     *
     * @throws MathException if the argument is an invalid number or is zero, or rounding is necessary
     */
    public function dividedBy(mixed $that, ?RoundingMode $roundingMode = null): self;
}
