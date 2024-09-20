<?php

declare(strict_types=1);

/*
 * This file is part of the Contao IP Login extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoIpLoginBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ContainerBuilder;
use Contao\ManagerPlugin\Config\ExtensionPluginInterface;
use InspiredMinds\ContaoIpLoginBundle\ContaoIpLoginBundle;
use InspiredMinds\ContaoIpLoginBundle\Security\IpAuthenticator;

class Plugin implements BundlePluginInterface, ExtensionPluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoIpLoginBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }

    public function getExtensionConfig($extensionName, array $extensionConfigs, ContainerBuilder $container): array
    {
        if ('security' !== $extensionName) {
            return $extensionConfigs;
        }

        foreach ($extensionConfigs as &$extensionConfig) {
            if (isset($extensionConfig['firewalls']['contao_frontend'])) {
                $extensionConfig['firewalls']['contao_frontend']['custom_authenticators'][] = IpAuthenticator::class;
            }
        }

        $extensionConfigs[] = ['enable_authenticator_manager' => true];

        return $extensionConfigs;
    }
}
