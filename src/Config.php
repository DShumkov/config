<?php namespace DShumkov\Config;

class Config
{
    public function __construct($path, $env = null)
    {
        if (null !== $env)
        {
            $path .= '/'.$env;
        }

        $this->loadDirectory($path);

    }

    public function get($name)
    {
        return $this->values[$name];
    }

    private function loadFile($fileName)
    {
        return require $fileName;
    }

    private function flatter($some_array, $prepend = '')
    {
        $result = [];
        foreach ($some_array as $key => $value)
        {
            if (is_array($value))
            {
                $result = array_merge($result, $this->flatter($value, $prepend.$key.'.'));
            }
            else
            {
                $result[$prepend.$key] = $value;
            }
        }
        return $result;
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * @param $path
     */
    private function loadDirectory($path)
    {
        $iterator = new \FilesystemIterator($path);
        $files = new \RegexIterator($iterator, '/\.php$/');
        $config = [];

        foreach ($files as $file) {
            if (is_dir($file->getPathname())) {
                continue;
            }
            $config[$file->getBaseName('.php')] = $this->loadFile($file->getPathname());
        }

        $this->values = $this->flatter($config);
    }

}
