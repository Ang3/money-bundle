<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Decorator;

use Ang3\Bundle\MoneyBundle\Contracts\ExtendedMonetizable;
use Brick\Money\Contracts\MoneyInterface;

interface MoneyDecoratorInterface extends ExtendedMonetizable
{
    public function getDecorated(): MoneyInterface;
}
