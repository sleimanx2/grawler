<?php
namespace Bowtie\Grawler\Config;


trait ConfigAccess
{
    protected $config;

    public function config()
    {
        return $this->config instanceof Config ? $this->config : new Config([]);
    }

    public function loadConfig($config = null)
    {
        if ($config instanceof Config) {
            $this->config = $config;
        } elseif (is_array($config)) {
            $this->config = new Config($config);
        } else {
            $this->config = new Config([]);
        }

        return $this;
    }
}