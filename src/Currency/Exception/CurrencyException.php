<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Currency\Exception;

class CurrencyException extends \RuntimeException
{
    public function __construct(private readonly string $currencyCode, string $message, \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function notFound(string $currencyCode): self
    {
        return new self($currencyCode, sprintf('The currency "%s" was not found.', $currencyCode));
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}
