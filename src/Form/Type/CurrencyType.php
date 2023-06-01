<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Form\Type;

use Ang3\Bundle\MoneyBundle\Currency\CurrencyRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\IntlCallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType as BaseCurrencyType;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Custom form type to represent Money based on Money/Money value objects.
 */
class CurrencyType extends AbstractType
{
    public function __construct(
        private readonly CurrencyRegistry $currencyRegistry,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $currencies = $this->currencyRegistry->getChoices();

        $resolver
            ->setDefaults([
                'choice_label' => fn (string $currencyCode, string $label) => $label === $currencyCode ? $currencyCode : sprintf('%s (%s)', $currencyCode, $label),
            ])
            ->addNormalizer('choice_loader', function (Options $options) use ($currencies) {
                /** @var string|null $choiceTranslationLocale */
                $choiceTranslationLocale = $options['choice_translation_locale'];

                /** @var string $choiceTranslationDomain */
                $choiceTranslationDomain = $options['choice_translation_domain'] ?: 'messages';

                return fn () => ChoiceList::loader($this, new IntlCallbackChoiceLoader(function () use ($currencies, $choiceTranslationLocale, $choiceTranslationDomain) {
                    $ISOCurrenciesList = array_flip(Currencies::getNames($choiceTranslationLocale));
                    $ISOCurrenciesFiltered = array_intersect($ISOCurrenciesList, $currencies);
                    $customCurrencies = array_flip(array_map(fn ($label) => $this->translator->trans($label, [], $choiceTranslationDomain, $choiceTranslationLocale), array_flip(array_diff($currencies, $ISOCurrenciesList))));
                    $choices = array_merge($ISOCurrenciesFiltered, $customCurrencies);
                    asort($choices);

                    return $choices;
                }), $choiceTranslationLocale);
            })
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'currency';
    }

    public function getParent(): string
    {
        return BaseCurrencyType::class;
    }
}
