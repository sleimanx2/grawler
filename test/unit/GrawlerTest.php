<?php

use Bowtie\Grawler\Client;
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
    function it_can_extract_the_selected_images_from_dom()
    {

        $grawler = $this->initGrawler('images-dom', 'http://example.com/images/');

        $images = $grawler->images('.image');

        $this->assertEquals(4, count($images));


        $this->assertEquals('http://example.com/images/full.png', $images[0]->url);
        $this->assertEquals('http://example.com/images/full.png', $images[3]->url);

    }

    /** @test */
    function it_can_extract_the_selected_videos_from_dom()
    {

        $grawler = $this->initGrawler('videos-dom', 'http://example.com/videos/');

        $videos = $grawler->videos('.video');

        $this->assertEquals(4, count($videos));

        $this->assertEquals('http://example.com/videos/watch?v=NU7W7qe2R0A', $videos[0]->url);
        $this->assertEquals('https://www.youtube.com/watch?v=NU7W7qe2R0A', $videos[3]->url);
    }


    /**
     * Create a Symphony crawler instance
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