<?php

declare(strict_types=1);

/*
 * This file is part of the Contao IP Login extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoIpLoginBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class ContaoIpLoginBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
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
                    ->info("Optional expression language condition on the request. The request object is available via the 'request' variable.")
                    ->defaultValue(null)
                ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void
    {
        $containerConfigurator->import('../config/services.yaml');

        $containerBuilder->setParameter('contao_iplogin.allowed_ips', $config['allowed_ips']);
        $containerBuilder->setParameter('contao_iplogin.ignored_paths', $config['ignored_paths']);
        $containerBuilder->setParameter('contao_iplogin.request_condition', $config['request_condition']);
    }
}
