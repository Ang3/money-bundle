<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Contracts;

use Brick\Math\BigNumber;

interface MoneyInterface
{
    public function getAmount(): BigNumber|int|float|string;

    public function getCurrency(): string;
}
