<?php

namespace Kikwik\ApiAdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        if (in_array('getRootNode', get_class_methods(TreeBuilder::class)))
        {
            $treeBuilder = new TreeBuilder('kikwik_api_admin');
            $rootNode = $treeBuilder->getRootNode();
        }
        else
        {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('kikwik_api_admin');
        }

        $rootNode
            ->children()
                ->scalarNode('api_endpoint')->defaultValue('/api')->cannotBeEmpty()->end()
            ->end()
        ;

        return $treeBuilder;
    }

}