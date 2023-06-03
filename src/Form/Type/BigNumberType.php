<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Form\Type;

use Ang3\Bundle\MoneyBundle\Form\DataTransformer\BigNumberToFloatTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Custom form type to represent BigNumber value objects.
 */
class BigNumberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new BigNumberToFloatTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'big_number';
    }

    public function getParent(): string
    {
        return NumberType::class;
    }
}
