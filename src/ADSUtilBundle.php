<?php

declare(strict_types=1);

namespace ADS\Util;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class ADSUtilBundle extends AbstractBundle
{
    /** @param array<string,mixed> $config */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader(
            $builder,
            new FileLocator(__DIR__ . '/Resources/config'),
        );

        $loader->load('php_util.yaml');
    }
}
