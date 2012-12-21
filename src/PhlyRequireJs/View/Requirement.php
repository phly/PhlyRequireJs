<?php

namespace PhlyRequireJs\View;

use InvalidArgumentException;
use Traversable;

class Requirement
{
    protected $name;

    public function __construct($name, $callback = null)
    {
        $this->setName($name);
        $this->setCallback($callback);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    protected function setName($name)
    {
        if (is_string($name)) {
            $this->name = $name;
            return;
        }

        if ($name instanceof Traversable) {
            $name = iterator_to_array($name);
        }

        if (!is_array($name)) {
            throw new InvalidArgumentException('Invalid name provided; must be a string, array, or array-like object');
        }

        $this->name = $name;
    }

    protected function setCallback($callback)
    {
        if (null === $callback) {
            $callback = 'function () {}';
        }
        if (!is_string($callback)) {
            throw new InvalidArgumentException('Callback must be a string');
        }
        $this->callback = $callback;
    }
}
