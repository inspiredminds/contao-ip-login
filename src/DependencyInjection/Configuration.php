<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoIpLoginBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoIpLoginBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('contao_iplogin');
        $treeBuilder
            ->getRootNode()
            ->children()
                ->arrayNode('allowed_ips')
                    ->info('The available IPs for the automatic login.')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()
                ->arrayNode('ignored_paths')
                    ->info('IP login will be disabled on these paths. Can be regular expressions.')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()
                ->scalarNode('request_condition')
                    ->info('Optional expression language condition on the request. The request object is available via the \'request\' variable.')
                    ->defaultValue(null)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
