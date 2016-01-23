<?php
namespace Bowtie\Grawler\Nodes;

use Bowtie\Grawler\Config\ConfigAccess;
use Bowtie\Grawler\Nodes\Resolvers\Resolver;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

abstract Class Media extends Attributes
{

    use ConfigAccess;

    /**
     * Media base url
     *
     * @var null
     */
    public $url;

    /**
     * Media attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Media resolvers
     *
     * @var array
     */
    protected $resolvers = [];

    /**
     * Media constructor.
     *
     * @param $url
     */
    public function __construct($url = null)
    {
        $this->checkIfAbsolute($url);

        $this->url = $url;

        parent::__construct($this->makeAttributes());
    }

    /**
     * iterate through the subclass's resolvers and returns self instance with filled
     * attributes if a valid resolver is found else false.
     *
     * @return self | false
     *
     */
    public function resolve()
    {
        foreach ($this->resolvers as $resolver) {
            $this->isValidResolver($resolver);

            return (new $resolver($this->url))->loadConfig($this->config())->resolve();
        }

        return false;
    }

    /**
     * add a custom resolver to subclass's resolver to extend the build in library.
     *
     * @param $resolvers
     */
    public function addResolvers($resolvers)
    {
        $resolvers = is_array($resolvers) ? $resolvers : [$resolvers];

        foreach ($resolvers as $resolver) {

            if ($this->isValidResolver($resolver)) {
                array_push($this->resolvers, $resolver);
                continue;
            }

            throw new InvalidArgumentException('Resolvers can only be an array of string strings');
        }
    }


    /**
     * returns the lists of resolvers class names
     *
     * @return array
     */
    public function resolvers()
    {
        return $this->resolvers;
    }

    /**
     * check if the given url is absolute else throw a logic exception.
     *
     * @param $url
     */
    private function checkIfAbsolute($url)
    {
        if ($url and !in_array(strtolower(substr($url, 0, 4)), ['http', 'file'])) {
            throw new \InvalidArgumentException(sprintf('The url must be an absolute URL ("%s").', $url));
        }
    }


    /**
     * create an associative array from the sequential attributes array in subclass.
     *
     * @return array
     */
    private function makeAttributes()
    {
        return $this->attributes = array_fill_keys($this->attributes, '');
    }

    /**
     * @param $resolver
     * @return bool
     */
    private function isValidResolver($resolver)
    {
        if (is_string($resolver) and is_subclass_of($resolver, Resolver::class)) {
            return true;
        }

        throw new InvalidArgumentException($resolver . " is not an instance of Resolver");
    }


}