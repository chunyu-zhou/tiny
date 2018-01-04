<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6835957bc29912b8a4cd2dd758af2e80
{
    public static $files = array (
        'b0655c4b47b25ec49f0e931fe41ab7a3' => __DIR__ . '/..' . '/phalapi/kernal/src/bootstrap.php',
        '5cab427b0519bb4ddb2f894b03d1d957' => __DIR__ . '/..' . '/phalapi/kernal/src/functions.php',
        'dee36c56d6bb319b2a744b267373bb4b' => __DIR__ . '/../..' . '/src/app/functions.php',
        '2b26c09c6872981e11ab534a4df3de88' => __DIR__ . '/../..' . '/src/Common/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhalApi\\Task\\' => 13,
            'PhalApi\\NotORM\\' => 15,
            'PhalApi\\' => 8,
        ),
        'C' => 
        array (
            'Chunyu\\JWT\\' => 11,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhalApi\\Task\\' => 
        array (
            0 => __DIR__ . '/..' . '/phalapi/task/src',
        ),
        'PhalApi\\NotORM\\' => 
        array (
            0 => __DIR__ . '/..' . '/phalapi/notorm/src',
        ),
        'PhalApi\\' => 
        array (
            0 => __DIR__ . '/..' . '/phalapi/kernal/src',
        ),
        'Chunyu\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/chunyu/jwt/src',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6835957bc29912b8a4cd2dd758af2e80::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6835957bc29912b8a4cd2dd758af2e80::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}