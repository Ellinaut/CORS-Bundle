<?php

namespace Ellinaut\CorsBundle\Listener;

use Ellinaut\CorsBundle\Provider\ConfigurationProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * @author Philipp Marien
 */
class RequestListener extends AbstractCorsListener
{
    public function __construct(
        ConfigurationProvider $configurationProvider,
        private readonly RequestMatcherInterface $requestMatcher
    ) {
        parent::__construct($configurationProvider);
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$this->configurationProvider->hasConfig($event->getRequest()->getPathInfo())) {
            return;
        }

        if ($event->getRequest()->getMethod() !== Request::METHOD_OPTIONS) {
            return;
        }

        if (!$this->requestMatcher->matches($event->getRequest())) {
            // if no options request is configured explicitly...
            $config = $this->configurationProvider->getConfig($event->getRequest()->getPathInfo());
            if ($config->get('handle_options')) {
                $event->setResponse(new Response());
            }
        }
    }
}
