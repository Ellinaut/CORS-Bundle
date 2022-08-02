<?php

namespace Ellinaut\CorsBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * @author Philipp Marien
 */
class RequestListener extends AbstractCorsListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$this->configurationProvider->hasConfig($event->getRequest()->getPathInfo())) {
            return;
        }

        $config = $this->configurationProvider->getConfig($event->getRequest()->getPathInfo());
        if ($config->get('handle_options')) {
            $event->setResponse(new Response());
        }
    }
}
