<?php

namespace Sowbiba\CommandWatcherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sowbiba_command_watcher');

        $rootNode
            ->children()
                ->arrayNode('commands')->isRequired()
                    ->prototype('scalar')
                    ->end() // end of commands children prototype
                ->end() // end of commands
                ->scalarNode('log_writer')->isRequired()->cannotBeEmpty()
                ->end() // end of log_writer
                ->scalarNode('log_reader')->isRequired()->cannotBeEmpty()
                ->end() // end of log_reader
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
