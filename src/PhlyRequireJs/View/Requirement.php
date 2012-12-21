<?php

namespace PhlyRequireJs\View;

use InvalidArgumentException;

class Requirement
{
    protected $name;

    public function __construct($name, $callback = null)
    {
        $this->name = $name;

        if (null === $callback) {
            $callback = 'function () {}';
        }
        if (!is_string($callback)) {
            throw new InvalidArgumentException('Callback must be a string');
        }
        $this->callback = $callback;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCallback()
    {
        return $this->callback;
    }
}
