<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/23/15
 * Time: 9:35 AM
 */

namespace WellsFargo\ACHBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\NodeInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     */
    protected $root;

    /**
     * Generates the configuration tree builder.
     *
     * @return NodeInterface
     */
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();
        $this->root = $treeBuilder->root("wells_fargo_ach");
        $this->root
            ->children()
                ->scalarNode('routing_number')->isRequired()->end()
                ->scalarNode('credit_company_id')->isRequired()->end()
                ->scalarNode('debit_company_id')->isRequired()->end()
                ->scalarNode('application_id')->isRequired()->end()
                ->scalarNode('file_id')->isRequired()->end()
                ->scalarNode('originating_bank')->isRequired()->end()
                ->scalarNode('company_name')->isRequired()->end()
                ->arrayNode('transmission')->isRequired()
                    ->children()
                        ->scalarNode('host')->isRequired()->end()
                        ->scalarNode('username')->isRequired()->end()
                        ->scalarNode('private_key_path')->isRequired()->end()
                        ->scalarNode('private_key_password')->isRequired()->end()
                        ->scalarNode('public_key_path')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('folders')->isRequired()
                    ->children()
                        ->scalarNode('inbound')->isRequired()->end()
                        ->scalarNode('outbound')->isRequired()->end()
                        ->scalarNode('returns_report')->isRequired()->end()

                    ->end()
                ->end()
                ->arrayNode('archive')->isRequired()
                    ->children()
                        ->scalarNode('inbound')->isRequired()->end()
                        ->scalarNode('outbound')->isRequired()->end()
                        ->scalarNode('returns_report')->isRequired()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

}