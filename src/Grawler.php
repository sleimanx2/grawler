<?php

namespace Bowtie\Grawler;

use Bowtie\Grawler\Nodes\Audio;
use Bowtie\Grawler\Nodes\Image;
use Bowtie\Grawler\Nodes\Link;
use Bowtie\Grawler\Nodes\MediaCollection;
use Bowtie\Grawler\Nodes\Video;
use Bowtie\Grawler\Config\ConfigAccess;
use Symfony\Component\DomCrawler\Crawler;

class Grawler
{
    use ConfigAccess;

    /** @var Crawler */
    private $DOM;

    /**
     * Current page absolute url
     *
     * @var string
     */
    private $uri;


    /**
     * Create a new Grawler Instance
     *
     * @param Crawler $DOM
     * @param array $paths
     */
    public function __construct(Crawler $DOM, $uri, array $paths = [])
    {
        $this->DOM = $DOM;
        $this->uri = $uri;

        $this->clearNoneHumanTags();
    }

    /**
     * return the parent DOMDocument
     *
     * @return \DOMDocument
     */
    public function document()
    {
        return $this->DOM->getNode(0)->parentNode;
    }

    /**
     * Extract the meta keywords from document
     */
    public function keywords()
    {
        $keywords = explode(',', $this->DOM->filter("meta[name='keywords']")->attr('content'));

        $keywords = array_map(function ($keyword) {
            return strtolower(trim($keyword));
        }, $keywords);

        return $keywords;
    }


    /**
     * Extract the meta description from document
     */
    public function description()
    {
        $description = $this->DOM->filter("meta[name='description']")->attr('content');

        return $description;
    }

    /**
     * extract title from dom given a path
     *
     * @param null|string $path
     * @return string
     */
    public function title($path = 'title')
    {
        if (!$path) {
            return "";
        }

        $filter = $this->DOM->filter($path);

        if ($filter->count()) {
            $title = $filter->first()->text();
        } else {
            $filter = $this->DOM->filter('title');
            $title = $filter->count() ? $filter->first()->text() : "";
        }

        return $title;
    }

    /**
     * extract body from dom given a path
     *
     * @param $path
     * @return string
     */
    public function body($path)
    {
        if (!$path) {
            return "";
        }

        $content = $this->DOM->filter($path)->each(function ($node) {
            return trim($node->text());
        });

        return implode("\n", $content);
    }

    /**
     * extract images from dom given a path
     *
     * @param $path
     * @return array
     */
    public function images($path)
    {
        if (!$path) {
            return new MediaCollection([]);
        }

        $attributes = ['data-image', 'data-url', 'data-src', 'data-pin-media', 'data-highres', 'src', 'href'];

        $links = $this->generateLinks($path, $attributes);

        $links = array_filter($links, function ($link) {
            return $this->isImage($link->getUri());
        });

        $images = array_map(function ($link) {
            return (new Image($link->getUri()))->loadConfig($this->config());
        }, $links);

        return new MediaCollection(array_values($images));
    }

    /**
     * extract videos from dom given a path
     *
     * @param $path
     * @return array
     */
    public function videos($path)
    {
        if (!$path) {
            return new MediaCollection([]);
        }


        $attributes = ['src', 'href', 'content'];

        $links = $this->generateLinks($path, $attributes);

        $videos = array_map(function ($link) {
            return (new Video($link->getUri()))->loadConfig($this->config());
        }, $links);


        return new MediaCollection($videos);
    }

    /**
     * extract audio from dom given a path
     *
     * @param $path
     * @return array
     */
    public function audio($path)
    {
        if (!$path) {
            return new MediaCollection([]);
        }

        $attributes = ['src', 'href', 'content'];

        $links = $this->generateLinks($path, $attributes);

        $audio = array_map(function ($link) {
            return (new Audio($link->getUri()))->loadConfig($this->config());
        }, $links);

        return new MediaCollection($audio);
    }


    /**
     * extract links from dom given a path
     *
     * @param $path
     * @return array
     */
    public function links($path)
    {
        if (!$path) {
            return [];
        }

        $attributes = ['href'];

        $nodes = $this->DOM->filter($path);

        // if nodes are found after filter
        if ($nodes->count()) {
            // if the nodes found aren't of type anchors add "a" to the path and try again
            if ($nodes->first()->nodeName() !== 'a') {
                $path = $path . ' a';
            }
        }

        return $this->generateLinks($path, $attributes);;
    }

    /**
     * @param $path
     * @param $attributes
     * @return array
     */
    private function generateLinks($path, $attributes)
    {

        $links = $this->DOM->filter($path)->each(function ($node) use ($attributes) {
            foreach ($attributes as $attribute) {

                if ($url = $node->attr($attribute)) {

                    $document = new \DOMDocument('1.0');
                    $linkNode = $document->createElement('a');
                    $linkNode->setAttribute('href', $url);
                    $linkNode->setAttribute('alt', $node->attr('alt'));

                    $link = new Link($linkNode, $this->uri);

                    if (filter_var($link->getUri(), FILTER_VALIDATE_URL) == false) {
                        return null;
                    }

                    return $link;
                }
            }
        });

        return array_values(array_unique(array_filter($links)));
    }

    /**
     * Checks if the url type is an image.
     *
     * @param string $url
     *
     * @return bool
     */
    private function isImage($url)
    {
        $pos = strrpos($url, '.');
        if ($pos === false) {
            return false;
        }
        $ext = strtolower(trim(substr($url, $pos)));
        $imgExts = [
            '.gif',
            '.jpg',
            '.jpeg',
            '.png',
            '.tiff',
            '.tif',
        ];

        // this is far from complete but that's always going to be the case...
        if (in_array($ext, $imgExts)) {
            return true;
        }

        return false;
    }

    /**
     * clear all html tags that aren't human readable
     */
    protected function clearNoneHumanTags()
    {
        $filter = $this->DOM->filter('script');

        $filter->each(function (Crawler $crawler) {
            foreach ($crawler as $node) {
                $node->parentNode->removeChild($node);
            }
        });
    }
}
