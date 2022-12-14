<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbcf00675b22c118fb53dab31bc16d89b
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitbcf00675b22c118fb53dab31bc16d89b', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbcf00675b22c118fb53dab31bc16d89b', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbcf00675b22c118fb53dab31bc16d89b::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
