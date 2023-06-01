<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Currency;

use Brick\Money\Currency;

interface CurrencyFactoryInterface
{
    public function createISO(string $currencyCode): Currency;

    public function createCustom(string $currencyCode, string $name, int $scale): Currency;
}
