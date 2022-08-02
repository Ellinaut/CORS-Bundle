<?php

namespace Ellinaut\CorsBundle\Listener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @author Philipp Marien
 */
class ResponseListener extends AbstractCorsListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        if ($event->getRequestType() !== HttpKernelInterface::MAIN_REQUEST) {
            return;
        }

        if (!$this->configurationProvider->hasConfig($event->getRequest()->getPathInfo())) {
            return;
        }

        $config = $this->configurationProvider->getConfig($event->getRequest()->getPathInfo());
        foreach ($config->get('origin') as $origin) {
            if ($origin === '*' || $origin === $event->getRequest()->headers->get('Origin')) {
                $this->addHeaders($event->getResponse(), $config);
                break;
            }
        }
    }

    protected function addHeaders(Response $response, ParameterBagInterface $config): void
    {
        $response->headers->set('Access-Control-Allow-Origin', implode(',', (array)$config->get('origin')));

        if ($config->get('credentials')) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        } else {
            $response->headers->set('Access-Control-Allow-Credentials', 'false');
        }

        $response->headers->set(
            'Access-Control-Allow-Methods',
            implode(',', array_merge((array)$config->get('methods'), (array)$config->get('additional_methods')))
        );

        $response->headers->set(
            'Access-Control-Allow-Headers',
            implode(',', array_merge((array)$config->get('headers'), (array)$config->get('additional_headers')))
        );
    }
}
