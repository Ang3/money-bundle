<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Exception;

class CurrencyNotFound extends \RuntimeException
{
    public function __construct(private readonly string $currencyCode)
    {
        parent::__construct(sprintf('The currency "%s" was not found.', $this->currencyCode));
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}
