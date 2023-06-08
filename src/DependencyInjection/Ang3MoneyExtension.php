<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class Ang3MoneyExtension extends ConfigurableExtension implements PrependExtensionInterface
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->setParameter('ang3_money.config', $mergedConfig);
        $defaultLocale = $container->hasParameter('kernel.default_locale') ? $container->getParameter('kernel.default_locale') : $mergedConfig['default_locale'];
        $container->setParameter('ang3_money.default_locale', $defaultLocale);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.php');

        if (ContainerBuilder::willBeAvailable('twig/twig', Environment::class, ['symfony/twig-bundle', 'ang3/money-bundle'])) {
            $loader->load('twig.php');
        }

        if (ContainerBuilder::willBeAvailable('symfony/form', FormInterface::class, ['ang3/money-bundle'])) {
            $loader->load('form.php');
        }

        if (ContainerBuilder::willBeAvailable('symfony/serializer', SerializerInterface::class, ['ang3/money-bundle'])) {
            $loader->load('serializer.php');
        }

        if (ContainerBuilder::willBeAvailable('symfony/validator', ValidatorInterface::class, ['ang3/money-bundle'])) {
            $loader->load('validator.php');
        }
    }

    public function prepend(ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DoctrineBundle'])) {
            $container->prependExtensionConfig('doctrine', [
                'orm' => [
                    'mappings' => [
                        'Ang3MoneyBundle' => [
                            'is_bundle' => false,
                            'dir' => '%kernel.project_dir%/vendor/ang3/money-bundle/src/Entity',
                            'prefix' => 'Ang3\Bundle\MoneyBundle\Entity',
                            'alias' => 'Ang3MoneyBundle',
                        ],
                    ],
                ],
            ]);
        }

        if (isset($bundles['TwigBundle'])) {
            $container->prependExtensionConfig('twig', [
                'form_themes' => [
                    '@Ang3Money::form_theme.html.twig',
                ],
            ]);
        }
    }
}
