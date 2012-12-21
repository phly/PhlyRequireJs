<?php

namespace PhlyRequireJsTest;

use ArrayObject;
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
        $this->requirejs->append('foo/bar');
        $this->assertEquals(1, count($this->requirejs));
        $require = false;
        foreach ($this->requirejs as $require) {
            // iterating to get first item
            break;
        }
        $this->assertInstanceOf('PhlyRequireJs\View\Requirement', $require);
        $this->assertEquals('foo/bar', $require->getName());
    }

    public function testCanAddUsingArrayArgumentOnly()
    {
        $this->requirejs->append(array('foo/bar', 'bar/baz'));
        $this->assertEquals(1, count($this->requirejs));
        $require = false;
        foreach ($this->requirejs as $require) {
            // iterating to get first item
            break;
        }
        $this->assertInstanceOf('PhlyRequireJs\View\Requirement', $require);
        $names = $require->getName();
        $this->assertInternalType('array', $names);
        $this->assertEquals(array('foo/bar', 'bar/baz'), $names);
    }

    public function testCanAddUsingArrayLikeArgumentOnly()
    {
        $names = new ArrayObject(array('foo/bar', 'bar/baz'));
        $this->requirejs->append($names);
        $this->assertEquals(1, count($this->requirejs));
        $require = false;
        foreach ($this->requirejs as $require) {
            // iterating to get first item
            break;
        }
        $this->assertInstanceOf('PhlyRequireJs\View\Requirement', $require);
        $test = $require->getName();
        $this->assertInternalType('array', $test);
        $this->assertEquals(array('foo/bar', 'bar/baz'), $test);
    }

    public function testCanAcceptJavaScriptCallbackStringAsSecondArgument()
    {
        $this->requirejs->append('foo/bar', 'function (bar) { bar(); }');
        $this->assertEquals(1, count($this->requirejs));
        $require = false;
        foreach ($this->requirejs as $require) {
            // iterating to get first item
            break;
        }
        $this->assertInstanceOf('PhlyRequireJs\View\Requirement', $require);
        $this->assertEquals('function (bar) { bar(); }', $require->getCallback());
    }

    public function testCanCaptureJavaScriptCallbackWhenAdding()
    {
        $this->requirejs->appendAndCaptureCallback('foo/bar'); ?>
function(bar){
    bar.baz();
}
<?php
        $this->requirejs->stopCapture();
        $this->assertEquals(1, count($this->requirejs));
        $require = false;
        foreach ($this->requirejs as $require) {
            // iterating to get first item
            break;
        }
        $this->assertInstanceOf('PhlyRequireJs\View\Requirement', $require);
        $this->assertEquals("function(bar){\n    bar.baz();\n}", trim($require->getCallback()));
    }

    public function testCanPrependRequire()
    {
        $this->requirejs->append('foo/bar');
        $this->requirejs->prepend('bar/baz');
        $this->assertEquals(2, count($this->requirejs));
        $require = false;
        foreach ($this->requirejs as $require) {
            // iterating to get first item
            break;
        }
        $this->assertInstanceOf('PhlyRequireJs\View\Requirement', $require);
        $this->assertEquals('bar/baz', $require->getName());
    }

    public function testCanCaptureJavaScriptCallbackWhenPrepending()
    {
        $this->requirejs->append('foo/bar');
        $this->requirejs->prependAndCaptureCallback('bar/baz'); ?>
function(bar){
    bar.baz();
}
<?php
        $this->requirejs->stopCapture();
        $this->assertEquals(2, count($this->requirejs));
        $require = false;
        foreach ($this->requirejs as $require) {
            // iterating to get first item
            break;
        }
        $this->assertInstanceOf('PhlyRequireJs\View\Requirement', $require);
        $this->assertEquals("function(bar){\n    bar.baz();\n}", trim($require->getCallback()));
    }


    public function testRenderingCreatesJavaScriptWithRequireContentsInOrder()
    {
        $this->requirejs->append('foo/bar');
        $this->requirejs->prependAndCaptureCallback('bar/baz'); ?>
function(bar){
    bar.baz();
}
<?php
        $this->requirejs->stopCapture();
        $this->requirejs->append('baz/bat', 'function (bat) { bat.d.ball(); }');

        $string = $this->requirejs->toString();
        $this->assertRegexp("#\<script\>\n\s*require\(\s*\[\s*\"bar/baz\"\s*\], function\(bar\)\s*\{\s*bar\.baz\(\);\s*\}\s*\);\n\s*require\(\s*\[\s*\"foo/bar\"\s*\], function \(\) \{\}\s*\);\n\s*require\(\s*\[\s*\"baz/bat\"\s*\], function \(bat\) \{ bat\.d\.ball\(\); \}\s*\);\n\s*\</script\>#s", $string);
    }
}
