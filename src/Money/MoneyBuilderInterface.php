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
     * Build the instance of Money.
     */
    public function build(RoundingMode $roundingMode = null): Money;
}
