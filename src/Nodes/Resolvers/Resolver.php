<?php

namespace Bowtie\Grawler\Nodes\Resolvers;

use Bowtie\Grawler\Config\ConfigAccess;
use Bowtie\Grawler\Nodes\Media;

abstract class Resolver
{
    use ConfigAccess;
    /**
     * url to resolve
     *
     * @var string
     */
    protected $url;

    /**
     * resolved data
     *
     * @var array
     */
    protected $rawData;


    /**
     * the provider identifier for instance "eTzc9JOFvwk" in https://www.youtube.com/watch?v=eTzc9JOFvwk
     *
     * @var string
     */
    protected $id;


    /**
     * if the attributes where resolved or not
     *
     * @var boolean
     */
    protected $resolved;


    /**
     * the media node that the resolver resolves (Audio,Image,Video)
     *
     * @var Media
     */
    protected $resolves;



    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * change the url to resolve and reset previous data
     *
     * @param $url
     */
    public function changeUrl($url)
    {
        $this->reset();
        $this->url = $url;
    }

    /**
     * return the raw data value from
     *
     * @return mixed
     */
    public function rawData()
    {
        return $this->rawData;
    }

    /**
     *  resolves the media node attributes from a valid provider
     *
     * @return bool|mixed
     */
    public function resolve()
    {
        if (!$this->id = $this->validate()) {
            return false;
        }

        if ($this->rawData = $this->doResolve()) {
            $this->resolved = true;

            return $this->map();
        }

        return $this->resolved = false;
    }

    /**
     * validate that the url given is valid to resolve and returns the identifier
     *
     * @return string|false the provider identifier or false if not valid
     */
    abstract public function validate();

    /**
     * resolve the url data
     *
     * @return mixed
     */
    abstract protected function doResolve();

    /**
     * map the raw data to the media node attributes
     *
     * @return mixed
     */
    abstract protected function map();

    /**
     *  reset previous data
     */
    private function reset()
    {
        $this->rawData = null;
        $this->id = null;
        $this->resolved = false;
    }

}