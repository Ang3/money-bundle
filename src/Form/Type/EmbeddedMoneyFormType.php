<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Form\Type;

use Ang3\Bundle\MoneyBundle\Entity\EmbeddedMoney;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Custom form type to represent embedded money objects.
 */
class EmbeddedMoneyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', NumberType::class, [
                'label' => 'Amount',
                'required' => $options['required'],
            ])
        ;

        if (false !== $options['currency_field']) {
            $builder->add('currency', CurrencyType::class, [
                'label' => 'Currency',
                'required' => $options['required'],
            ]);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var EmbeddedMoney $data */
        $data = $form->getData();

        // pass the form type option directly to the template
        $view->vars['currency'] = $data->getCurrency();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'class' => EmbeddedMoney::class,
                'required' => false,
                'currency_field' => true,
                'currency_options' => [],
            ])
            ->setAllowedTypes('required', 'bool')
            ->setAllowedTypes('currency', 'string')
            ->setAllowedTypes('currency_field', 'bool')
            ->setAllowedTypes('currency_options', 'array')
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'embedded_money';
    }
}
