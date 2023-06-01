<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Decorator;

use Brick\Money\Contracts\Monetizable;

interface MoneyModifierInterface
{
    /**
     * Gets the result monetizable object.
     */
    public function getResult(): Monetizable;

    /**
     * Saves the result and returns it.
     */
    public function save(): Monetizable;
}
