<?php

namespace Src;

use ReflectionClass;
use Exception;

class Container {
    private array $instances = [];

    public function set(string $id, object $instance): void {
        $this->instances[$id] = $instance;
    }

    public function get(string $id) {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        $reflector = new ReflectionClass($id);
        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$id} not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $id();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (!$type || $type->isBuiltin()) {
                throw new Exception("Cannot resolve automatically.");
            }

            $dependencies[] = $this->get($type->getName());
        }

        $instance = $reflector->newInstanceArgs($dependencies);

        if ($instance instanceof Controller) {
            $view = $this->get(View::class);
            $instance->setView($view);
        }

        return $instance;
    }
}
