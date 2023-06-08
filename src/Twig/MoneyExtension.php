<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Twig;

use Ang3\Bundle\MoneyBundle\Contracts\MoneyInterface;
use Ang3\Bundle\MoneyBundle\Currency\CurrencyRegistry;
use Ang3\Bundle\MoneyBundle\Entity\EmbeddedMoney;
use Brick\Math\BigNumber;
use Brick\Money\AbstractMoney;
use Brick\Money\Currency;
use Brick\Money\Money;
use Symfony\Component\Intl\Currencies;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MoneyExtension extends AbstractExtension
{
    public function __construct(
        private readonly CurrencyRegistry $currencyRegistry,
        private readonly string $defaultLocale,
        private readonly ?TranslatorInterface $translator = null
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('money', [$this, 'createMoney']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('money', [$this, 'formatMoney']),
            new TwigFilter('currency_symbol', [$this, 'currencySymbol']),
        ];
    }

    public function createMoney(BigNumber|int|float|string $amount, Currency|string|null $currency = null, bool $fromMinor = false): Money
    {
        $currency = $currency ? ($currency instanceof Currency ? $currency : $this->currencyRegistry->get($currency)) : $this->currencyRegistry->getDefaultCurrency();

        return $fromMinor ? Money::ofMinor($amount, $currency) : Money::of($amount, $currency);
    }

    public function formatMoney(AbstractMoney|MoneyInterface $money, string $locale = null): string
    {
        if (!$money instanceof Money) {
            if ($money instanceof EmbeddedMoney) {
                $money = $money->getMoney();
            } elseif ($money instanceof AbstractMoney) {
                $money = Money::of($money->getAmount(), $money->getCurrency());
            } else {
                $money = Money::of($money->getAmount(), $this->currencyRegistry->get($money->getCurrency()));
            }
        }

        $locale = $locale ?: ($this->translator ? $this->translator->getLocale() : $this->defaultLocale);

        return $money->formatTo($locale);
    }

    public function currencySymbol(string $currency): string
    {
        return Currencies::getSymbol($currency);
    }

    public function getName(): string
    {
        return 'ang3_money.twig.money_extension';
    }
}
