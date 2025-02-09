<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcd59f14bef7adff3b35a12ee4c80b7a8
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tests\\Ikana\\EmbedVideoThumbnail\\' => 32,
        ),
        'I' => 
        array (
            'Ikana\\EmbedVideoThumbnail\\' => 26,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tests\\Ikana\\EmbedVideoThumbnail\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'Ikana\\EmbedVideoThumbnail\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcd59f14bef7adff3b35a12ee4c80b7a8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcd59f14bef7adff3b35a12ee4c80b7a8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
