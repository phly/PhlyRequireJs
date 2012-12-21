<?php

namespace PhlyRequireJs\View;

use Zend\View\Helper\Placeholder\Container\AbstractStandalone as Container;

class RequireJs extends Container
{
    public function append($nameOrNames, $callback = null)
    {
        $requirement = new Requirement($nameOrNames, $callback);
        $this->getContainer()->append($requirement);
        return $this;
    }
}
