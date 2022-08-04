<?php

namespace Ellinaut\CorsBundle\Listener;

use Ellinaut\CorsBundle\Provider\ConfigurationProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Exception\ExceptionInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Philipp Marien
 */
class RequestListener extends AbstractCorsListener
{
    public function __construct(
        ConfigurationProvider $configurationProvider,
        private readonly RouterInterface $router
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

        $optionsConfigured = false;
        try {
            foreach ($this->router->match($event->getRequest()->getPathInfo()) as $route) {
                $routeConfig = $this->router->getRouteCollection()->get($route);
                if ($routeConfig && in_array(Request::METHOD_OPTIONS, $routeConfig->getMethods(), true)) {
                    $optionsConfigured = true;
                    break;
                }
            }
        } catch (ExceptionInterface $exception) {

        }

        if (!$optionsConfigured) {
            // if no options request is configured explicitly...
            $config = $this->configurationProvider->getConfig($event->getRequest()->getPathInfo());
            if ($config->get('handle_options')) {
                $event->setResponse(new Response());
            }
        }
    }
}
