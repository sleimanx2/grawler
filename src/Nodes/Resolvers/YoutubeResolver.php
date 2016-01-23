<?php

namespace Bowtie\Grawler\Nodes\Resolvers;


use Google_Client;
use Google_Service_YouTube;
use Bowtie\Grawler\Nodes\Video;

class YoutubeResolver extends Resolver
{


    protected $resolves = Video::class;

    /**
     * validate that the url given is valid to resolve and returns the identifier
     *
     * @return string|false the provider identifier or false if not valid
     */
    public function validate()
    {
        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",
            $this->url, $matches);

        if (isset($matches[1])) {
            return $matches[1];
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

        $node->id = $this->rawData->id;
        $node->url = 'http://www.youtube.com/watch?v=' . $node->id;

        $node->embedUrl = 'http://www.youtube.com/embed/' . $node->id;
        $node->provider = 'youtube';

        if ($this->rawData->snippet) {
            $node->title = $this->rawData->snippet->title;
            $node->description = $this->rawData->snippet->description;

            // @toDo return an instance of images
            $node->images = $this->rawData->snippet->thumbnails;

            $node->author = $this->rawData->snippet->channelTitle;
            $node->authorId = $this->rawData->snippet->channelId;
            $node->provider = 'youtube';
        }

        if ($this->rawData->contentDetails) {
            $node->duration = (new \DateTime('@0'))
                ->add(new \DateInterval($this->rawData->contentDetails->duration))
                ->format('H:i:s');
        }

        return $node;
    }

    /**
     * resolve the url raw data
     *
     * @return mixed the youtube video object if the video is found or false if the youtube video is not found
     */
    protected function doResolve()
    {
        $id = $this->id ? $this->id : $this->validate();

        $client = new Google_Client();
        $client->setApplicationName("Grawler");

        $client->setDeveloperKey( $this->config()->get('youtubeKey'));

        $youtube = new Google_Service_YouTube($client);

        $response = $youtube->videos->listVideos('contentDetails,snippet', ['id' => $id]);

        $videos = $response->getItems();

        return reset($videos);
    }

}