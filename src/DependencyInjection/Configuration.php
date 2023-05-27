<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Intl\Currencies;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ang3_money');

        $treeBuilder
            ->getRootNode()
            ->fixXmlConfig('currency', 'currencies')
            ->children()
            ->scalarNode('default_currency')->end()
            ->arrayNode('currencies')
            ->scalarPrototype()
            ->isRequired()
            ->validate()
            ->ifNotInArray(Currencies::getCurrencyCodes())
            ->thenInvalid('Invalid currency "%s".')
            ->end()
            ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
