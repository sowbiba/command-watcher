<?php

namespace Sowbiba\CommandWatcherBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SowbibaCommandWatcherExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->defineParameters($container, $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    private function defineParameters(ContainerInterface $container, array $config)
    {
        $container->setParameter('sowbiba_commands_stats.commands', $config['commands']);
        $container->setParameter('sowbiba_commands_stats.log_path', $config['log_path']);
        $container->setParameter('sowbiba_commands_stats.log_prefix', isset($config['log_prefix']) ? $config['log_prefix'] : '');
    }
}
