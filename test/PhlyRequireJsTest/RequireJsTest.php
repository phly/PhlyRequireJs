<?php

namespace PhlyRequireJsTest;

use PhlyRequireJs\View\RequireJs;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\View\Helper\Placeholder\Registry;

class RequestJsTest extends TestCase
{
    public function setUp()
    {
        Registry::unsetRegistry();
        $this->requirejs = new RequireJs();
    }

    public function testCanAddUsingSingleStringArgumentOnly()
    {
        $this->markTestIncomplete();
    }

    public function testCanAddUsingArrayArgumentOnly()
    {
        $this->markTestIncomplete();
    }

    public function testCanAddUsingArrayLikeArgumentOnly()
    {
        $this->markTestIncomplete();
    }

    public function testCanAcceptJavaScriptCallbackStringAsSecondArgument()
    {
        $this->markTestIncomplete();
    }

    public function testCanCaptureJavaScriptCallbackWhenAdding()
    {
        $this->markTestIncomplete();
    }

    public function testCanPrependRequire()
    {
        $this->markTestIncomplete();
    }

    public function testRenderingCreatesJavaScriptWithRequireContentsInOrder()
    {
        $this->markTestIncomplete();
    }
}
