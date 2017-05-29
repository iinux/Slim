<?php
/**
 * Slim Framework (https://slimframework.com)
 *
 * @link      https://github.com/slimphp/Slim
 * @copyright Copyright (c) 2011-2017 Josh Lockhart
 * @license   https://github.com/slimphp/Slim/blob/3.x/LICENSE.md (MIT License)
 */
namespace Slim;

use RuntimeException;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\CallableResolverInterface;

/**
 * This class resolves a string of the format 'class:method' into a closure
 * that can be dispatched.
 */
final class CallableResolver implements CallableResolverInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Resolve toResolve into a closure that that the router can dispatch.
     *
     * If toResolve is of the format 'class:method', then try to extract 'class'
     * from the container otherwise instantiate it and then dispatch 'method'.
     *
     * @param mixed $toResolve
     *
     * @return callable
     *
     * @throws RuntimeException if the callable does not exist
     * @throws RuntimeException if the callable is not resolvable
     */
    public function resolve($toResolve)
    {
        $resolved = $toResolve;

        if (!is_callable($toResolve) && is_string($toResolve)) {
            $class = $toResolve;
            $methods = ['__invoke', 'handle'];

            // check for slim callable as "class:method"
            $callablePattern = '!^([^\:]+)\:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$!';
            if (preg_match($callablePattern, $toResolve, $matches)) {
                $class = $matches[1];
                // if you spell the method name wrong and the class has __invoke or handle method
                // it will not report error, it not friendly for you debugging, so comment it
                // array_unshift($methods, $matches[2]);
                $methods = [$matches[2]];
            }

            if ($this->container instanceof ContainerInterface &&
                $this->container->has($class)) {
                foreach ($methods as $method) {
                    $resolved = [$this->container->get($class), $method];
                    if (is_callable($resolved)) {
                        break;
                    }
                }
            } else {
                if (!class_exists($class)) {
                    throw new RuntimeException(sprintf('Callable %s does not exist', $class));
                }
                foreach ($methods as $method) {
                    $resolved = [new $class($this->container), $method];
                    if (is_callable($resolved)) {
                        break;
                    }
                }
            }
        }

        if (!is_callable($resolved)) {
            throw new RuntimeException(sprintf(
                '%s is not resolvable',
                is_array($toResolve) || is_object($toResolve) ? json_encode($toResolve) : $toResolve
            ));
        }

        if ($this->container instanceof ContainerInterface && $resolved instanceof \Closure) {
            $resolved = $resolved->bindTo($this->container);
        }

        return $resolved;
    }
}
