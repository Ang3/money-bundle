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
use Brick\Money\Contracts\Monetizable;

/**
 * Methods missing in base Monetizable interface.
 */
interface ExtendedMonetizable extends Monetizable
{
    public function toZero(): self;

    public function duplicate(BigNumber|int|float|string|null $amount = null): self;

    public function setAmount(BigNumber|int|float|string $amount): self;
}
