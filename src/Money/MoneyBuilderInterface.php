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
use Brick\Money\Money;

interface MoneyBuilderInterface extends MoneyOperationInterface
{
    /**
     * Initialize the builder from the given or stored rounding mode.
     * Use this method to reset the builder on initial state.
     */
    public function initialize(?RoundingMode $roundingMode = null): self;

    /**
     * Build the instance of Money.
     */
    public function build(RoundingMode $roundingMode = null): Money;
}