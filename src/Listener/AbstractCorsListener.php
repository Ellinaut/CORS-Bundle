<?php

namespace Ellinaut\CorsBundle\Listener;

use Ellinaut\CorsBundle\Provider\ConfigurationProvider;

/**
 * @author Philipp Marien
 */
abstract class AbstractCorsListener
{
    public function __construct(protected readonly ConfigurationProvider $configurationProvider)
    {
    }
}
