<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Entity;

use Ang3\Bundle\MoneyBundle\Enum\RoundingMode;
use Ang3\Bundle\MoneyBundle\Money\EmbeddedMoneyModifier;

trait EmbeddedMoneyOperationTrait
{
    public function plus(mixed $that, RoundingMode $roundingMode = null): EmbeddedMoneyModifier
    {
        return $this->modify($roundingMode)->plus($that);
    }

    public function minus(mixed $that, RoundingMode $roundingMode = null): EmbeddedMoneyModifier
    {
        return $this->modify($roundingMode)->minus($that);
    }

    public function multipliedBy(mixed $that, RoundingMode $roundingMode = null): EmbeddedMoneyModifier
    {
        return $this->modify($roundingMode)->multipliedBy($that);
    }

    public function dividedBy(mixed $that, RoundingMode $roundingMode = null): EmbeddedMoneyModifier
    {
        return $this->modify($roundingMode)->dividedBy($that);
    }

    public function modify(RoundingMode $roundingMode = null): EmbeddedMoneyModifier
    {
        return new EmbeddedMoneyModifier($this, $roundingMode);
    }
}
