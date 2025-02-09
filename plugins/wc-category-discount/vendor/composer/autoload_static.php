<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbb1cf7c0e8145d6065e01465d66f5e08
{
    public static $files = array (
        'de8ae3556e3528028bf78d63c06029aa' => __DIR__ . '/..' . '/cmb2/cmb2/init.php',
        '298d93e63abaa39062e0a531c701c1ed' => __DIR__ . '/../..' . '/src/addons/cmb-field-select2/cmb-field-select2.php',
        '090a82ef78f3e66ef3bf2403bd61ab7a' => __DIR__ . '/../..' . '/src/addons/cmb2-field-address/cmb2-field-address.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Wcd\\DiscountRules\\' => 18,
            'Wcd\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Wcd\\DiscountRules\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Wcd\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbb1cf7c0e8145d6065e01465d66f5e08::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbb1cf7c0e8145d6065e01465d66f5e08::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
