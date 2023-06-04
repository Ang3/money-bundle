<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Decorator;

use Ang3\Bundle\MoneyBundle\Entity\EmbeddedMoney;
use Brick\Math\RoundingMode;

class EmbeddedMoneyModifier extends MoneyModifier
{
    public function __construct(private readonly EmbeddedMoney $embeddedMoney)
    {
        parent::__construct($this->embeddedMoney->getMoney());
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $roundingMode
     */
    public function clone(int $roundingMode = null): self
    {
        $newEmbeddedMoney = new EmbeddedMoney();
        $modifier = new self($newEmbeddedMoney);
        $modifier->money = clone $this->money;
        $modifier->context = $this->context ? clone $this->context : null;
        $modifier->save($roundingMode);

        return $modifier;
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $roundingMode
     */
    public function save(int $roundingMode = null): EmbeddedMoney
    {
        $roundingMode = $roundingMode ?: RoundingMode::DOWN;
        $money = $this->getResult($roundingMode);
        $this->embeddedMoney->setMoney($money);
        $this->setMoney($money);

        return $this->embeddedMoney;
    }

    public function getEmbeddedMoney(): EmbeddedMoney
    {
        return $this->embeddedMoney;
    }
}
