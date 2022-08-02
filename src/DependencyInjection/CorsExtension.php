<?php

namespace Ellinaut\CorsBundle\DependencyInjection;

use Ellinaut\CorsBundle\Listener\RequestListener;
use Ellinaut\CorsBundle\Listener\ResponseListener;
use Ellinaut\CorsBundle\Provider\ConfigurationProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * @author Philipp Marien
 */
class CorsExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->autowire(ConfigurationProvider::class)
            ->setPublic(false)
            ->setArgument('$configuration', $mergedConfig);

        $container->autowire(RequestListener::class)
            ->setPublic(true)
            ->addTag(
                'kernel.event_listener',
                [
                    'event' => 'kernel.request',
                    'method' => 'onKernelRequest',
                    'priority' => 10
                ]
            );

        $container->autowire(ResponseListener::class)
            ->setPublic(true)
            ->addTag(
                'kernel.event_listener',
                [
                    'event' => 'kernel.request',
                    'method' => 'onKernelRequest'
                ]
            );
    }
}
