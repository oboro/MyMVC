<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita87762e5e616a2ca66e88d450b2c2c97
{
    public static $files = array (
        '2c102faa651ef8ea5874edb585946bce' => __DIR__ . '/..' . '/swiftmailer/swiftmailer/lib/swift_required.php',
    );

    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Genkgo\\Mail\\' => 12,
        ),
        'E' => 
        array (
            'Egulias\\EmailValidator\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Genkgo\\Mail\\' => 
        array (
            0 => __DIR__ . '/..' . '/genkgo/mail/src',
        ),
        'Egulias\\EmailValidator\\' => 
        array (
            0 => __DIR__ . '/..' . '/egulias/email-validator/EmailValidator',
        ),
    );

    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'Doctrine\\Common\\Lexer\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/lexer/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita87762e5e616a2ca66e88d450b2c2c97::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita87762e5e616a2ca66e88d450b2c2c97::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInita87762e5e616a2ca66e88d450b2c2c97::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
