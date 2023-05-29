<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Enum;

enum RoundingMode: int
{
    /*
     * Asserts that the requested operation has an exact result, hence no rounding is necessary.
     *
     * If this rounding mode is specified on an operation that yields a result that
     * cannot be represented at the requested scale, a RoundingNecessaryException is thrown.
     */
    case Unnecessary = \Brick\Math\RoundingMode::UNNECESSARY;

    /*
     * Rounds away from zero.
     *
     * Always increments the digit prior to a nonzero discarded fraction.
     * Note that this rounding mode never decreases the magnitude of the calculated value.
     */
    case Up = \Brick\Math\RoundingMode::UP;

    /*
     * Rounds towards zero.
     *
     * Never increments the digit prior to a discarded fraction (i.e., truncates).
     * Note that this rounding mode never increases the magnitude of the calculated value.
     */
    case Down = \Brick\Math\RoundingMode::DOWN;

    /*
     * Rounds towards positive infinity.
     *
     * If the result is positive, behaves as for UP; if negative, behaves as for DOWN.
     * Note that this rounding mode never decreases the calculated value.
     */
    case Ceiling = \Brick\Math\RoundingMode::CEILING;

    /*
     * Rounds towards negative infinity.
     *
     * If the result is positive, behave as for DOWN; if negative, behave as for UP.
     * Note that this rounding mode never increases the calculated value.
     */
    case Floor = \Brick\Math\RoundingMode::FLOOR;

    /*
     * Rounds towards "nearest neighbor" unless both neighbors are equidistant, in which case round up.
     *
     * Behaves as for UP if the discarded fraction is >= 0.5; otherwise, behaves as for DOWN.
     * Note that this is the rounding mode commonly taught at school.
     */
    case HalfUp = \Brick\Math\RoundingMode::HALF_UP;

    /*
     * Rounds towards "nearest neighbor" unless both neighbors are equidistant, in which case round down.
     *
     * Behaves as for UP if the discarded fraction is > 0.5; otherwise, behaves as for DOWN.
     */
    case HalfDown = \Brick\Math\RoundingMode::HALF_DOWN;

    /*
     * Rounds towards "nearest neighbor" unless both neighbors are equidistant, in which case round towards positive infinity.
     *
     * If the result is positive, behaves as for HALF_UP; if negative, behaves as for HALF_DOWN.
     */
    case HalfCeiling = \Brick\Math\RoundingMode::HALF_CEILING;

    /*
     * Rounds towards "nearest neighbor" unless both neighbors are equidistant, in which case round towards negative infinity.
     *
     * If the result is positive, behaves as for HALF_DOWN; if negative, behaves as for HALF_UP.
     */
    case halfFloor = \Brick\Math\RoundingMode::HALF_FLOOR;

    /*
     * Rounds towards the "nearest neighbor" unless both neighbors are equidistant, in which case rounds towards the even neighbor.
     *
     * Behaves as for HALF_UP if the digit to the left of the discarded fraction is odd;
     * behaves as for HALF_DOWN if it's even.
     *
     * Note that this is the rounding mode that statistically minimizes
     * cumulative error when applied repeatedly over a sequence of calculations.
     * It is sometimes known as "Banker's rounding", and is chiefly used in the USA.
     */
    case halfEven = \Brick\Math\RoundingMode::HALF_EVEN;
}