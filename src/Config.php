<?php namespace DShumkov\Config;

class Config extends Registry
{
    public static function init($path, $env = null)
    {
        if (null !== $env)
        {
            $path .= '/'.$env;
        }

        self::loadDirectory($path);

    }

    private static function loadFile($fileName)
    {
        return require $fileName;
    }

    private static function flatter($some_array, $prepend = '')
    {
        $result = [];
        foreach ($some_array as $key => $value)
        {
            if (is_array($value))
            {
                $result = array_merge($result, self::flatter($value, $prepend.$key.'.'));
            }
            else
            {
                $result[$prepend.$key] = $value;
            }
        }
        return $result;
    }

    /**
     * @param $path
     */
    private static function loadDirectory($path)
    {
        $iterator = new \FilesystemIterator($path);
        $files = new \RegexIterator($iterator, '/\.php$/');
        $config = [];

        foreach ($files as $file) {
            if (is_dir($file->getPathname())) {
                continue;
            }
            $config[$file->getBaseName('.php')] = self::loadFile($file->getPathname());
        }

        self::$values = self::flatter($config);
    }

}