<?php

namespace Ellinaut\CorsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Philipp Marien
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('cors');
        $root = $builder->getRootNode()->children();

        $pattern = $root->arrayNode('patterns')
            ->useAttributeAsKey('pattern')
            ->arrayPrototype()
            ->children();

        $pattern->booleanNode('handle_options')->defaultTrue();

        $pattern->booleanNode('credentials')->defaultTrue();

        $pattern->arrayNode('origin')->defaultValue(['*'])->scalarPrototype();

        $pattern->arrayNode('methods')->defaultValue([
            'OPTIONS',
            'HEAD',
            'GET',
            'POST',
            'PATCH',
            'PUT',
            'DELETE'
        ])->scalarPrototype();

        $pattern->arrayNode('additional_methods')->defaultValue([])->scalarPrototype();

        $pattern->arrayNode('headers')->defaultValue([
            'Access-Control-Allow-Headers',
            'Access-Control-Request-Method',
            'Access-Control-Request-Headers',
            'Access-Control-Allow-Credentials',
            'Origin',
            'Authorization',
            'Content-Type',
            'Accept',
            'Accept-Language',
            'Vary',
            'X-Requested-With',
        ])->scalarPrototype();

        $pattern->arrayNode('additional_headers')->defaultValue([])->scalarPrototype();

        return $builder;
    }
}
