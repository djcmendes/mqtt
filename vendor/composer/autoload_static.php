<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf22087b9272a72d0a009f7e6910dff6c
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'PhpMqtt\\Client\\' => 15,
        ),
        'M' => 
        array (
            'MyCLabs\\Enum\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'PhpMqtt\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-mqtt/client/src',
        ),
        'MyCLabs\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/myclabs/php-enum/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf22087b9272a72d0a009f7e6910dff6c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf22087b9272a72d0a009f7e6910dff6c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf22087b9272a72d0a009f7e6910dff6c::$classMap;

        }, null, ClassLoader::class);
    }
}
