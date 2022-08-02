<?php

namespace Ellinaut\CorsBundle\DependencyInjection;

use Ellinaut\CorsBundle\Listener\RequestListener;
use Ellinaut\CorsBundle\Listener\ResponseListener;
use Ellinaut\CorsBundle\Provider\ConfigurationProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;

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

        if (!$container->hasAlias(RequestMatcherInterface::class)) {
            $container->setAlias(RequestMatcherInterface::class, 'router.default');
        }

        $container->autowire(RequestListener::class)
            ->setPublic(true)
            ->addTag(
                'kernel.event_listener',
                [
                    'event' => 'kernel.request',
                    'method' => 'onKernelRequest',
                    'priority' => 50
                ]
            );

        $container->autowire(ResponseListener::class)
            ->setPublic(true)
            ->addTag(
                'kernel.event_listener',
                [
                    'event' => 'kernel.response',
                    'method' => 'onKernelResponse'
                ]
            );
    }
}
