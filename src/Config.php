<?php namespace DShumkov\Config;

class Config implements Registry
{
    protected $values = [];

    public function get($name)
    {
        return $this->values[$name];
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;
    }

    public function getAll()
    {
        return $this->values;
    }

    public function __construct($path, $env = null)
    {
        $this->loadDirectory($path);

        if (null !== $env)
        {
            $this->loadDirectory($path . '/'.$env);
        }

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

        $this->values = array_merge($this->values, $this->flatter($config)) ;
    }

}