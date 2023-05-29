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
use Brick\Math\RoundingMode;

class EmbeddedMoneyModifier extends MoneyBuilder
{
    public function __construct(private readonly EmbeddedMoney $embeddedMoney, int $roundingMode = null)
    {
        parent::__construct($this->embeddedMoney->getMoney($roundingMode ?: RoundingMode::DOWN));
    }

    public function initialize(): void
    {
        $money = $this->embeddedMoney->getMoney($this->roundingMode);
        $this->rationalMoney = $money->toRational();
        $this->context = $money->getContext();
    }

    public function save(): EmbeddedMoney
    {
        $this->embeddedMoney->update($this->getResult());

        return $this->embeddedMoney;
    }
}
