<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Form\DataTransformer;

use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Currency;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transforms between a Money/Money value objects and integer value.
 */
class MoneyToFloatTransformer implements DataTransformerInterface
{
    public function __construct(private readonly Currency $currency)
    {
    }

    /**
     * Transforms a Money object to a numeric integer.
     *
     * @param Money|null $value
     *
     * @throws MathException
     */
    public function transform($value): float
    {
        if (null === $value) {
            return 0;
        }

        return $value->getAmount()->toFloat();
    }

    /**
     * Transforms an integer|string to a Money object.
     *
     * @param int|float|string|null $value
     *
     * @throws NumberFormatException
     * @throws RoundingNecessaryException
     * @throws UnknownCurrencyException
     */
    public function reverseTransform($value): Money
    {
        return null !== $value ? Money::of($value, $this->currency) : Money::zero($this->currency);
    }
}
