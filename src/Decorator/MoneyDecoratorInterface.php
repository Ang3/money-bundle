<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Decorator;

use Ang3\Bundle\MoneyBundle\Contracts\Monetizable;
use Ang3\Bundle\MoneyBundle\Contracts\MoneyInterface;
use Brick\Money\Money;
use Brick\Money\RationalMoney;

interface MoneyDecoratorInterface extends Monetizable
{
    public static function create(Money|RationalMoney|MoneyInterface $decorated, int $defaultRoundingMode = null): self;

    public function getDecorated(): Money|RationalMoney;

    public function setDecorated(Money|RationalMoney|MoneyInterface $decorated): self;
}
