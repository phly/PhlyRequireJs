<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyRequireJs\View;

use RuntimeException;
use Zend\View\Helper\Placeholder\Container\AbstractStandalone as Container;

class RequireJs extends Container
{
    /**
     * Whether or not a capture has already been started
     *
     * @var bool
     */
    protected $captureStarted = false;

    /**
     * Name or names to capture
     *
     * @var string|array
     */
    protected $captureNameOrNames;

    /**
     * Type of capture (prepend, append)
     *
     * @var string
     */
    protected $captureType;

    /**
     * Append a requirement
     *
     * @param string|array $nameOrNames
     * @param string $callback JavaScript callback for the requirement
     * @return self
     */
    public function append($nameOrNames, $callback = null)
    {
        $requirement = new Requirement($nameOrNames, $callback);
        $this->getContainer()->append($requirement);
        return $this;
    }

    /**
     * Prepend a requirement
     *
     * @param string|array $nameOrNames
     * @param string $callback JavaScript callback for the requirement
     * @return self
     */
    public function prepend($nameOrNames, $callback = null)
    {
        $requirement = new Requirement($nameOrNames, $callback);
        $this->getContainer()->prepend($requirement);
        return $this;
    }

    /**
     * Begin capturing the JavaScript requirement callback to append later
     *
     * @param string|array $nameOrNames
     */
    public function appendAndCaptureCallback($nameOrNames)
    {
        if ($this->captureStarted) {
            throw new RuntimeException('Cannot nest requirejs callback captures');
        }

        $this->captureNameOrNames = $nameOrNames;
        $this->captureType        = 'append';
        ob_start();
        $this->captureStarted = true;
    }

    /**
     * Begin capturing the JavaScript requirement callback to prepend later
     *
     * @param string|array $nameOrNames
     */
    public function prependAndCaptureCallback($nameOrNames)
    {
        if ($this->captureStarted) {
            throw new RuntimeException('Cannot nest requirejs callback captures');
        }

        $this->captureNameOrNames = $nameOrNames;
        $this->captureType        = 'prepend';
        ob_start();
        $this->captureStarted = true;
    }

    /**
     * Stop capturing
     */
    public function stopCapture()
    {
        if (!$this->captureStarted) {
            return;
        }

        if (null === $this->captureNameOrNames) {
            throw new RuntimeException('Capture detected, but no name present; cannot proceed');
        }

        $callback = ob_get_clean();
        $this->captureStarted = false;

        $callback = trim($callback);
        if (empty($callback)) {
            $callback = null;
        }

        switch ($this->captureType) {
            case 'prepend':
                $this->prepend($this->captureNameOrNames, $callback);
                break;
            case 'append':
            default:
                $this->append($this->captureNameOrNames, $callback);
                break;
        }
        $this->captureNameOrNames = null;
        $this->captureType        = null;
    }

    /**
     * Cast to string
     *
     * @return string
     */
    public function toString()
    {
        $script = array();
        foreach ($this as $require) {
            if (!$require instanceof Requirement) {
                continue;
            }
            $name = $this->formatName($require->getName());
            $script[] = sprintf('require([%s], %s);', $name, $require->getCallback());
        }
        $script = implode("\n", $script);
        return sprintf("<script>\n%s\n</script>", $script);
    }

    /**
     * Format the requirement name
     *
     * @param string|array $name
     * @return string
     */
    protected function formatName($name)
    {
        if (is_string($name)) {
            return sprintf('"%s"', $name);
        }
        $names = array();
        foreach ($name as $module) {
            if (!is_string($module)) {
                continue;
            }
            $names[] = sprintf('"%s"', $module);
        }
        return implode(', ', $names);
    }
}
