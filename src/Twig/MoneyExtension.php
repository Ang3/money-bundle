<?php

namespace Ang3\Bundle\MoneyBundle\Twig;

use Ang3\Bundle\MoneyBundle\Config\MoneyConfig;
use Ang3\Bundle\MoneyBundle\Entity\Price;
use Brick\Money\Currency;
use Brick\Money\Money;
use Symfony\Component\Intl\Currencies;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MoneyExtension extends AbstractExtension
{
    public function __construct(private readonly MoneyConfig         $moneyConfig,
                                private readonly TranslatorInterface $translator)
    {
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

    public function createMoney(int $amount, Currency|string|null $currency = null, bool $fromMinor = true): string
    {
        $currency = $currency ? ($currency instanceof Currency ? $currency : Currency::of($currency)) : $this->moneyConfig->getDefaultCurrency();

        return $fromMinor ? Money::ofMinor($amount, $currency) : Money::of($amount, $currency);
    }

    public function formatMoney(Money $money, ?string $locale = null): string
    {
        $locale = $locale ?: $this->translator->getLocale();

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
