<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit451a425a5c56044c7b5789d66f53d017
{
    public static $prefixLengthsPsr4 = array (
        'Y' => 
        array (
            'YtDOwnloader\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'YtDOwnloader\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'YtDownloader\\YtDownloader' => __DIR__ . '/../..' . '/src/YtDownloader.class.php',
        'YtDownloader\\YtVideo' => __DIR__ . '/../..' . '/src/YtVideo.class.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit451a425a5c56044c7b5789d66f53d017::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit451a425a5c56044c7b5789d66f53d017::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit451a425a5c56044c7b5789d66f53d017::$classMap;

        }, null, ClassLoader::class);
    }
}