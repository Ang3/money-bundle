<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Money;

use Ang3\Bundle\MoneyBundle\Entity\EmbeddedMoney;
use Ang3\Bundle\MoneyBundle\Enum\RoundingMode;

class EmbeddedMoneyModifier extends RationalMoneyBuilder
{
    public function __construct(private readonly EmbeddedMoney $embeddedMoney, RoundingMode $roundingMode = null)
    {
        parent::__construct($this->embeddedMoney->getMoney($roundingMode), $roundingMode);
    }

    public function initialize(RoundingMode $roundingMode = null): self
    {
        $this->setMoney($this->embeddedMoney->getMoney($this->roundingMode));

        return $this;
    }

    public function save(RoundingMode $roundingMode = null): EmbeddedMoney
    {
        $this->embeddedMoney->updateMoney($this->build($roundingMode));

        return $this->embeddedMoney;
    }
}
