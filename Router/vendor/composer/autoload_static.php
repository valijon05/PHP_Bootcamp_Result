<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit174b5308988e581c2fcc4d1981e63b2a
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Developer\\Router\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Developer\\Router\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit174b5308988e581c2fcc4d1981e63b2a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit174b5308988e581c2fcc4d1981e63b2a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit174b5308988e581c2fcc4d1981e63b2a::$classMap;

        }, null, ClassLoader::class);
    }
}