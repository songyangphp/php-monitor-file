<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitca5888691483b8de0e48bd4c723a6c65
{
    public static $prefixLengthsPsr4 = array (
        'w' => 
        array (
            'wslibs\\php_monitor_file\\' => 24,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'wslibs\\php_monitor_file\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitca5888691483b8de0e48bd4c723a6c65::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitca5888691483b8de0e48bd4c723a6c65::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
