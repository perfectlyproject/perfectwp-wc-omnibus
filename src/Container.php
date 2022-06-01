<?php

namespace PerfectWPWCO;

if (!defined('ABSPATH')) exit;

abstract class Container
{
    /**
     * The current globally available container (if any).
     *
     * @var static
     */
    protected static $instance;

    /**
     * The container's shared instances.
     *
     * @var array
     */
    private $instances = [];

    private $resolved = [];

    private $bindings = [];

    /**
     * Set the shared instance of the container.
     *
     * @param  \PerfectWPWCO\Container|null
     * @return mixed
     */
    public static function setInstance(Container $container = null)
    {
        return static::$instance = $container;
    }

    /**
     * Return an existing instance
     *
     * @param $abstract
     * @return mixed|null
     */
    public function get($abstract)
    {
        return isset($this->instances[$abstract]) ? $this->instances[$abstract] : $this->resolve($abstract);
    }

    protected function resolveDependencies($dependencies = [])
    {
        $items = [];

        foreach ($dependencies as $dependency) {
            $typeName = null;

            if (version_compare(PHP_VERSION, '7.1', '<')) {
                $typeName = (string) $dependency->getType();
            } elseif ($dependency->getType() instanceof \ReflectionNamedType) {
                $typeName = $dependency->getType()->getName();
            }

            if ($typeName === null) {
                throw new \Exception('Dependency not found');
            }

            $resolved = $this->resolve($typeName);

            $items[] = $resolved;
        }

        return $items;
    }

    public function resolve($abstract, $params = [])
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = isset($this->bindings[$abstract]) ? $this->bindings[$abstract] : null;

        $object = null;

        if ($concrete instanceof \Closure) {
            $object = $concrete($this, $params);
        }

        $concrete = $abstract;

        if (class_exists($concrete)) {
            foreach ($this->instances as $instance) {
                if ($instance instanceof $concrete) {
                    return $instance;
                }
            }

            $reflector = new \ReflectionClass($concrete);

            $constructor = $reflector->getConstructor();

            if ($constructor === null) {
                $object = new $concrete;
            } else {
                $params = array_merge($params, $this->resolveDependencies($constructor->getParameters()));
                $object = $reflector->newInstanceArgs($params);
            }
        }

        $this->instances[$abstract] = $object;

        $this->resolved[$abstract] = true;

        return $object;
    }

    public function bind($abstract, $concrete)
    {
        if (!$concrete instanceof \Closure) {
            $concrete = function($container, $parameters = []) use ($abstract, $concrete) {
                return $container->resolve($concrete, $parameters);
            };
        }

        $this->bindings[$abstract]  = $concrete;
    }

    /**
     * Register an existing instance as shared in the container.
     *
     * @param  string  $abstract
     * @param  mixed  $instance
     * @return mixed
     */
    public function instance($abstract, $instance)
    {
        $this->instances[$abstract] = $instance;

        return $instance;
    }
}