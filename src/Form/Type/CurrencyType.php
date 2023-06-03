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
use Ang3\Bundle\MoneyBundle\Form\DataTransformer\CurrencyToStringTransformer;
use Brick\Money\Currency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\IntlCallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
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

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CurrencyToStringTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'choice_label' => function (Currency $currency) {
                    return $currency->getName() === $currency->getCurrencyCode()
                        ? $currency->getCurrencyCode()
                        : sprintf('%s (%s)', $currency->getCurrencyCode(), $this->translator->trans($currency->getName()));
                },
            ])
            ->addNormalizer('choice_loader', function (Options $options) {
                /** @var string|null $choiceTranslationLocale */
                $choiceTranslationLocale = $options['choice_translation_locale'];

                /** @var string $choiceTranslationDomain */
                $choiceTranslationDomain = $options['choice_translation_domain'] ?: 'messages';

                return fn () => ChoiceList::loader($this, new IntlCallbackChoiceLoader(function () use ($choiceTranslationDomain, $choiceTranslationLocale) {
                    $choices = array_combine(
                        $this->currencyRegistry->map(function (Currency $currency) use ($choiceTranslationDomain, $choiceTranslationLocale) {
                            return $currency->getName() === $currency->getCurrencyCode()
                                ? $currency->getCurrencyCode()
                                : sprintf('%s (%s)', $currency->getCurrencyCode(), $this->translator->trans($currency->getName(), [], $choiceTranslationDomain, $choiceTranslationLocale));
                        }),
                        $this->currencyRegistry->toArray()
                    );

                    asort($choices);

                    return $choices;
                }), $choiceTranslationLocale);
            })
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'brick_currency';
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
