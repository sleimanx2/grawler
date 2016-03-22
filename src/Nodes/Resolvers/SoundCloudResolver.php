<?php

namespace Bowtie\Grawler\Nodes\Resolvers;


use Bowtie\Grawler\Nodes\Audio;
use Bowtie\Grawler\Nodes\Image;
use Njasm\Soundcloud\Soundcloud;

class SoundCloudResolver extends Resolver
{


    protected $resolves = Audio::class;

    /**
     * validate that the url given is valid to resolve and returns the identifier
     *
     * @return string|false the provider identifier or false if not valid
     */
    public function validate()
    {

        preg_match("/https?:\/\/(?:w\.|www\.|)(?:soundcloud\.com\/)(?:(?:player\/\?url=https\%3A\/\/api.soundcloud.com\/tracks\/)|)(((\w|-)[^A-z]{8})|([A-Za-z0-9]+(?:[-_][A-Za-z0-9]+)*(?!\/sets(?:\/|$))(?:\/[A-Za-z0-9]+(?:[-_][A-Za-z0-9]+)*){1,2}))/",
            $this->url, $matches);

        if (isset($matches[1])) {
            return $matches[1];
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
        $this->id = $this->id ? $this->id : $this->validate();

        $soundcloud = new Soundcloud($this->config()->get('soundcloudKey'), $this->config()->get('soundcloudSecret'));


        // if we have the track id get info directly else if the id is a string try to resolve the url and retry
        // with the retrieved id
        if (ctype_digit($this->id)) {


            $response = $soundcloud->get('/tracks/' . $this->id)->request()->bodyArray();


        } elseif (is_string($this->id)) {
            $response = $soundcloud->get('/resolve', ['url' => $this->url])->request()->bodyArray();

            // extract id from location if available
            if (isset($response['location'])) {
                $this->id = substr($response['location'], strrpos($response['location'], '/') + 1);
                $this->id = strtok($this->id, '?');

                // retry with the new ID
                return $this->doResolve();
            }

            return false;
        }

        if (isset($response['kind']) and $response['kind'] == 'track') {
            return $response;
        }

        return false;
    }

    /**
     * map the raw data to the media node attributes
     *
     * @return mixed
     */
    protected function map()
    {

        $node = new $this->resolves;

        $node->id = $this->rawData['id'];
        $node->title = $this->rawData['title'];
        $node->description = $this->rawData['description'];
        $node->url = $this->rawData['permalink_url'];
        $node->embedUrl = "https://w.soundcloud.com/player/?url=" . $this->rawData['uri'];
        $node->images = [new Image($this->rawData['permalink_url'])];
        $node->author = $this->rawData['user']['username'];
        $node->authorId = $this->rawData['user']['id'];
        $node->duration = gmdate("H:i:s", $this->rawData['duration'] / 1000);
        $node->provider = 'soundcloud';
        $node->streamUrl = $this->rawData['stream_url'];
        $node->genre = $this->rawData['genre'];

        return $node;
    }
}