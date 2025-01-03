<?php

namespace Izopi4a\EcontBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class EcontBundleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Load the services YAML configuration
        $loader = new \Symfony\Component\DependencyInjection\Loader\YamlFileLoader(
            $container,
            new \Symfony\Component\Config\FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');
    }
}