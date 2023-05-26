<?php

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
                ->arrayNode('currencies')
                    ->scalarPrototype()
                        ->isRequired()
                        ->validate()
                            ->ifNotInArray(Currencies::getCurrencyCodes())
                            ->thenInvalid('Invalid currency "%s".')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('default_currency')
                    ->isRequired()
                    ->defaultValue('EUR')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
