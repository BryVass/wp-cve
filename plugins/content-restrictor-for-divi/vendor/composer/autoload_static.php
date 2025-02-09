<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit124f8aaf61a88607d3ea6acff3085931
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPTools\\Psr\\Container\\' => 22,
            'WPTools\\Pimple\\' => 15,
            'WPT\\RestrictContent\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPTools\\Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/wpt00ls/container/src',
        ),
        'WPTools\\Pimple\\' => 
        array (
            0 => __DIR__ . '/..' . '/wpt00ls/pimple/src/Pimple',
        ),
        'WPT\\RestrictContent\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit124f8aaf61a88607d3ea6acff3085931::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit124f8aaf61a88607d3ea6acff3085931::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit124f8aaf61a88607d3ea6acff3085931::$classMap;

        }, null, ClassLoader::class);
    }
}
