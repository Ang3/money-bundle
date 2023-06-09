<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Currency\Exception;

class CurrencyRegistryException extends \RuntimeException
{
    public static function noDefaultCurrency(): self
    {
        return new self('No default currency registered into the currency registry.');
    }
}
