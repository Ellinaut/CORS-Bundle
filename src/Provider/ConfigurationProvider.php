<?php

namespace Ellinaut\CorsBundle\Provider;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @author Philipp Marien
 */
class ConfigurationProvider
{
    public function __construct(private readonly array $configuration)
    {
    }

    public function hasConfig(string $path): bool
    {
        foreach ($this->configuration['patterns'] as $pattern => $config) {
            if ($this->match($path, $pattern)) {
                return true;
            }
        }

        return false;
    }

    public function getConfig(string $path): ParameterBagInterface
    {
        foreach ($this->configuration['patterns'] as $pattern => $config) {
            if ($this->match($path, $pattern)) {
                return new ParameterBag($config);
            }
        }

        throw new InvalidArgumentException('Missing configuration!');
    }

    private function match(string $path, string $pattern): bool
    {
        if ($pattern === '*') {
            return true;
        }

        return (bool)preg_match($pattern, $path);
    }
}
