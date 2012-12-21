<?php

namespace PhlyRequireJs\View;

use RuntimeException;
use Zend\View\Helper\Placeholder\Container\AbstractStandalone as Container;

class RequireJs extends Container
{
    protected $captureStarted = false;

    protected $captureNameOrNames;
    protected $captureType;

    public function append($nameOrNames, $callback = null)
    {
        $requirement = new Requirement($nameOrNames, $callback);
        $this->getContainer()->append($requirement);
        return $this;
    }

    public function prepend($nameOrNames, $callback = null)
    {
        $requirement = new Requirement($nameOrNames, $callback);
        $this->getContainer()->prepend($requirement);
        return $this;
    }

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
}
