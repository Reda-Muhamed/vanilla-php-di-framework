<?php

declare(strict_types=1);

namespace Core;

use Exception;
use ReflectionClass;
use ReflectionNamedType;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    // Allow $concrete to be a string OR a callable (Closure)
    public function bind(string $abstract, string|callable $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function make($abstract): object
    {
        // if an instance was created before
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $this->bindings[$abstract] ?? $abstract;

        // If the binding is a function, execute it to get the object
        if (is_callable($concrete)) {
            $instance = $concrete($this); 
            $this->instances[$abstract] = $instance;
            return $instance;
        }
        $reflector = new ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Class $concrete is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (!$constructor) {
            $instance =  new $concrete();
            $this->instances[$abstract] = $instance;
            return $instance;
        }

        $params = $constructor->getParameters();
        $dependencies = [];
        foreach ($params as $param) {
            $type = $param->getType();

            //if there is no type hinting before the param in the constructor
            if (!$type) {
                throw new Exception("Cannot resolve parameter '{$param->getName()}' in $concrete (No type hint).");
            }

            if ($type instanceof ReflectionNamedType && $type->isBuiltin()) {
                throw new Exception("Cannot auto-wire primitive type '{$type->getName()}' for parameter '{$param->getName()}' in $concrete.");
            }

            $dependencies[] = $this->make($type->getName());
        }
        $instance = $reflector->newInstanceArgs($dependencies);
        $this->instances[$abstract] = $instance;

        return  $instance;
    }
}
