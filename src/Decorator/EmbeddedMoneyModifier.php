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
use Brick\Money\Contracts\Monetizable;
use Brick\Money\Money;
use Brick\Money\RationalMoney;

class EmbeddedMoneyModifier extends MoneyModifier
{
    public function __construct(private readonly EmbeddedMoney $embeddedMoney, ?int $roundingMode = null)
    {
        $money = $this->embeddedMoney->getMoney();
        parent::__construct($money->toRational(), $money->getContext(), $roundingMode);
    }

    public function initialize(int $roundingMode = null): self
    {
        $money = $this->embeddedMoney->getMoney();
        $this->setDecorated($money->toRational());
        $this->setRoundingMode($roundingMode);
        $this->setContext($money->getContext());

        return $this;
    }

    public function save(?int $roundingMode = null): EmbeddedMoney
    {
        $result = $this->getResult($roundingMode);
        $this->embeddedMoney->setMoney($result);

        return $this->embeddedMoney;
    }

    public function getResult(?int $roundingMode = null): Money
    {
        $result = $this->getDecorated();

        if (!$result instanceof RationalMoney) {
            throw new \UnexpectedValueException(sprintf('Expected money of type "%s", got "%s".', RationalMoney::class, get_debug_type($result)));
        }

        return $result->to($this->embeddedMoney->getMoney()->getContext(), $this->resolveRoundingMode($roundingMode));
    }

    /**
     * @internal
     *
     * Method override to update the decorated object by creating a new instance of this modifier
     * especially for the embedded money and rounding mode
     */
    protected function newInstance(Monetizable $money): self
    {
        $modifier = new self($this->embeddedMoney, $this->getRoundingMode());
        $modifier->setDecorated($money);

        return $modifier;
    }
}
