<?php

Autoloader::register();

class Autoloader
{

    public static function register()
    {
        spl_autoload_register(array('Autoloader', 'trigger'));
    }

    public static function unregister()
    {
        spl_autoload_unregister(array('Autoloader', 'trigger'));
    }

    public static function trigger($class_name)
    {
        $class_file = __DIR__.DIRECTORY_SEPARATOR.$class_name.self::suffix();

        if (is_file($class_file)) {
            include $class_file;
        }
    }

    //检测文件后缀名
    protected static function suffix()
    {
        return strstr(pathinfo(__FILE__, PATHINFO_BASENAME), '.');
    }

}