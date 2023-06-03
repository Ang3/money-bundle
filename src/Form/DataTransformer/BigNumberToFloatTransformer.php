<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Form\DataTransformer;

use Brick\Math\BigNumber;
use Brick\Math\Exception\DivisionByZeroException;
use Brick\Math\Exception\NumberFormatException;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transforms between BigNumber objects and number value.
 */
class BigNumberToFloatTransformer implements DataTransformerInterface
{
    /**
     * Transforms a BigNumber object to a numeric value.
     *
     * @param BigNumber|null $value
     */
    public function transform($value): ?float
    {
        if (null === $value) {
            return null;
        }

        return $value->toFloat();
    }

    /**
     * Transforms a scalar value to a BigNumber object.
     *
     * @param int|float|string|null $value
     *
     * @throws NumberFormatException
     * @throws DivisionByZeroException
     */
    public function reverseTransform($value): BigNumber
    {
        return BigNumber::of(null !== $value ? $value : 0);
    }
}
