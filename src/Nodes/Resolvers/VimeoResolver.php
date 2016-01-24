<?php

namespace Bowtie\Grawler\Nodes\Resolvers;

use Bowtie\Grawler\Nodes\Image;
use Bowtie\Grawler\Nodes\Video;
use Vimeo\Vimeo;

class VimeoResolver extends Resolver
{

    protected $resolves = Video::class;

    /**
     * validate that the url given is valid to resolve and returns the identifier
     *
     * @return string|false the provider identifier or false if not valid
     */
    public function validate()
    {
        preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/",
            $this->url, $matches);

        if (isset($matches[5])) {
            return $matches[5];
        }

        return false;
    }

    /**
     * resolve the url data
     *
     * @return mixed
     */
    protected function doResolve()
    {
        $id = $this->id ? $this->id : $this->validate();

        $lib = new Vimeo($this->config()->get('vimeoKey'), $this->config()->get('vimeoSecret'));

        $token = $lib->clientCredentials('private public');

        if (isset($token['body']['access_token'])) {
            $lib->setToken($token['body']['access_token']);
        }

        $response = $lib->request('/videos/' . $id);

        if (isset($response['body']['error'])) {
            return false;
        }

        return $response['body'];
    }

    /**
     * map the raw data to the media node attributes
     *
     * @return mixed
     */
    protected function map()
    {
        $node = new $this->resolves;

        $node->id = $this->id;
        $node->url = $this->rawData['link'];

        $node->embedUrl = 'https://player.vimeo.com/video/' . $node->id;

        $node->title = $this->rawData['name'];
        $node->description = $this->rawData['description'];

        $images = [];

        foreach ($this->rawData['pictures']['sizes'] as $thumb) {
            $newImage = new Image($thumb['link']);
            $newImage->width = $thumb['width'];
            $newImage->height = $thumb['height'];
            $images[] = $newImage;
        }

        $node->images = $images;

        $node->author = $this->rawData['user']['name'];
        $node->authorId = substr($this->rawData['user']['uri'], strrpos($this->rawData['user']['uri'], '/') + 1);

        $node->provider = 'vimeo';

        $node->duration = gmdate("H:i:s", $this->rawData['duration']);

        return $node;
    }


}