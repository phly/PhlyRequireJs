<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyRequireJs\View;

use InvalidArgumentException;
use Traversable;

/**
 * Value object describing a requirejs callback
 */
class Requirement
{
    /**
     * @var string
     */
    protected $callback;

    /**
     * @var string
     */
    protected $name;

    public function __construct($name, $callback = null)
    {
        $this->setName($name);
        $this->setCallback($callback);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Set the requirement name
     * 
     * @param string|array|Traversable $name 
     */
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

    /**
     * Set the JavaScript callback to execute with the requirement
     * 
     * @param string $callback 
     */
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
