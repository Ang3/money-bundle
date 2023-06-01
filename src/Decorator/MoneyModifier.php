<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Decorator;

use Brick\Money\Contracts\Monetizable;

class MoneyModifier extends MoneyDecorator implements MoneyModifierInterface
{
    public function __construct(Monetizable $decorated, protected ?int $roundingMode = null)
    {
        parent::__construct($decorated);
    }

    public function save(): Monetizable
    {
        return $this->getResult();
    }

    public function getResult(int $roundingMode = null): Monetizable
    {
        return $this->getDecorated();
    }

    /**
     * @internal
     *
     * Method override to update the decorated object and avoid new instance of modifiers
     */
    protected function newInstance(Monetizable $money): self
    {
        // We keep the same instance but we change the decorated object
        $this->setDecorated($money);

        return $this;
    }
}
