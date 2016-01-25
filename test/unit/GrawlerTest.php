<?php

use Bowtie\Grawler\Grawler;
use Symfony\Component\DomCrawler\Crawler;

class GrawlerTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_extract_the_default_title_from_dom()
    {
        $grawler = $this->initGrawler('title-dom');

        $title = $grawler->title();

        $this->assertEquals('default title', $title);
    }


    /** @test */
    function it_can_extract_the_selected_title_from_dom()
    {
        $grawler = $this->initGrawler('title-dom');

        $title = $grawler->title('h1');

        $this->assertEquals('selected title', $title);
    }

    /** @test */
    function it_can_extract_the_selected_body_from_dom()
    {
        $grawler = $this->initGrawler('body-dom');

        $body = $grawler->body('article');
        $this->assertEquals('body from as single node', $body);

        $body = $grawler->body('p');
        $this->assertEquals("body\nfrom\nmultiple\nnodes", $body);
    }


    /** @test */
    function it_can_extract_the_selected_links_from_dom()
    {
        $grawler = $this->initGrawler('links-dom','http://example.com/news/latest/');

        $links = $grawler->links('.latest-articles');

        $this->assertEquals(3, count($links));

        $this->assertEquals('http://example.com/news/latest/news-1', $links[0]->getUri());
        $this->assertEquals('http://example.com/news/news-2', $links[1]->getUri());
        $this->assertEquals('http://example.com/news-3', $links[2]->getUri());

        $links = $grawler->links('.post-link');

        $this->assertEquals('http://example.com/news/latest/news-1', $links[0]->getUri());
        $this->assertEquals('http://example.com/news/news-2', $links[1]->getUri());
        $this->assertEquals('http://example.com/news-3', $links[2]->getUri());
    }


    /** @test */
    function it_can_extract_the_selected_images_from_dom()
    {

        $grawler = $this->initGrawler('images-dom', 'http://example.com/images/');

        $images = $grawler->images('.image');

        $this->assertEquals(4, count($images));

        $this->assertEquals('http://example.com/images/full.png', $images[0]->url);
        $this->assertEquals('http://example.com/images/full-4.png', $images[3]->url);

    }

    /** @test */
    function it_can_extract_the_selected_videos_from_dom()
    {

        $grawler = $this->initGrawler('videos-dom', 'http://example.com/videos/');

        $videos = $grawler->videos('.video');

        // we assert 3 because we expect the grawler to remove duplicates
        $this->assertEquals(3, count($videos));


        $this->assertEquals('http://example.com/videos/watch?v=NU7W7qe2R0A', $videos[0]->url);
        $this->assertEquals('https://www.youtube.com/watch?v=NU7W7qe2R0A', $videos[2]->url);
    }


    /** @test */
    function it_can_extract_the_selected_audio_from_dom()
    {

        $grawler = $this->initGrawler('audio-dom', 'http://example.com/audio/');

        $audio = $grawler->audio('.audio');

        // we assert 3 because we expect the grawler to remove duplicates
        $this->assertEquals(3, count($audio));

        $this->assertEquals('http://example.com/audio/listen?a=NU7W7qe2R0A', $audio[0]->url);
        $this->assertEquals('https://www.soundcloud.com/listen?a=NU7W7qe2R0A', $audio[2]->url);
    }


    /**
     * Init a Symphony Crawler instance
     *
     * @param $DOM
     * @param string $uri
     * @return Grawler
     */
    private function initGrawler($DOM, $uri = 'http://example.com')
    {
        $DOM = file_get_contents(__DIR__ . '/../resources/html/' . $DOM . '.html');

        return new Grawler(new Crawler($DOM, $uri), $uri);
    }

}