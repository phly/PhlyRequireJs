PhlyRequireJs
=============

[https://travis-ci.org/phly/PhlyRequireJs.svg?branch=master](https://travis-ci.org/phly/PhlyRequireJs)

Simple library providing a ZF2 view helper for aggregating RequireJs calls.

Installation
------------

Install the module via git submodules, unzipping the download zipball, or,
later, via Composer.

Enable it in a ZF2 module by adding the "PhlyRequireJs" module to your
`config/application.config.php` file.

If not using in ZF2, but standalone with the ZF2 `PhpRenderer`, you will need to
add the `requirejs` helper as an invokable to the `ViewHelperManager`.

Usage
-----

```php
<?php

// require(["foo/bar"], function () {});
$this->requirejs()->append('foo/bar'); 

// require(["foo/bar"], function (bar) { bar.baz(); });
$this->requirejs()->append('foo/bar', 'function (bar) { bar.baz(); }'); 

// Capture the callback
$this->requirejs()->appendAndCaptureCallback('foo/bar'); ?>
function(bar) {
    bar.baz();
    bar.onClick(bar.doSomething);
}
<?php
$this->requirejs()->stopCapture();

// Echo all requires
echo $this->requirejs();
```

The view helper also defines `prepend` and `prependAndCaptureCallback` methods;
they work identically, but prepend the require to the start of the list.

LICENSE
-------

BSD-2-Clause
