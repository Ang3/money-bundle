<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Decorator;

use Ang3\Bundle\MoneyBundle\Contracts\MoneyInterface;
use Ang3\Bundle\MoneyBundle\Entity\EmbeddedMoney;
use Brick\Math\BigNumber;
use Brick\Math\RoundingMode;
use Brick\Money\AbstractMoney;

/**
 * @method self plus(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that, int $roundingMode = null)
 * @method self minus(MoneyInterface|AbstractMoney|BigNumber|int|float|string $that, int $roundingMode = null)
 * @method self multipliedBy(BigNumber|int|float|string $that, int $roundingMode = null)
 * @method self dividedBy(BigNumber|int|float|string $that, int $roundingMode = null)
 * @method self toZero()
 * @method self abs()
 * @method self negated()
 */
class EmbeddedMoneyModifier extends MoneyModifier
{
    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $defaultRoundingMode
     */
    public function __construct(private readonly EmbeddedMoney $embeddedMoney, int $defaultRoundingMode = null)
    {
        parent::__construct($this->embeddedMoney->getMoney(), $defaultRoundingMode);
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $roundingMode
     */
    public function clone(int $roundingMode = null): self
    {
        $newEmbeddedMoney = new EmbeddedMoney();
        $modifier = new self($newEmbeddedMoney);
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

        return $this->embeddedMoney;
    }

    public function getEmbeddedMoney(): EmbeddedMoney
    {
        return $this->embeddedMoney;
    }
}
