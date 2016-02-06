<?php

namespace Sowbiba\CommandsStatsBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('sowbiba_commands_stats');

        $rootNode
            ->children()
                ->arrayNode('commands')->isRequired()
                    ->prototype('scalar')
                    ->end() // end of commands children prototype
                ->end() // end of commands
                ->scalarNode('log_path')->isRequired()->cannotBeEmpty()
                ->end() // end of log_path
                ->scalarNode('log_prefix')
                ->end() // end of log_prefix
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
