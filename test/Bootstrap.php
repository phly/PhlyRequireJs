<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyRequireJsTest;

use RuntimeException;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

class Bootstrap
{
    public static function init()
    {
        static::initAutoloader();

    }

    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');

        if (! is_readable($vendorPath . '/autoload.php')) {
            throw new RuntimeException('Unable to load Composer autoloader; make sure you run "composer install" from the module root.');
        }

        include $vendorPath . '/autoload.php';
    }

    protected static function findParentPath($path)
    {
        $dir         = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

Bootstrap::init();
