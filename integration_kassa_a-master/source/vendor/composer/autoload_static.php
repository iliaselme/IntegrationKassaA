<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc8ce93619b401b139ccfa2937573966f
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpAmqpLib\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpAmqpLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-amqplib/php-amqplib/PhpAmqpLib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc8ce93619b401b139ccfa2937573966f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc8ce93619b401b139ccfa2937573966f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
