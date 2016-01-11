<?php

use Bowtie\Grawler\Client;
use Bowtie\Grawler\Grawler;

class GrawlerTest extends PHPUnit_Framework_TestCase
{

    function it_can_be_created()
    {

        $client = new Client();
        $grawler = $client->download('http://hacker-news.com');

        $grawler->links([
            '.latest-news',
            '.trending-news'
        ]);


        $grawler->title();

        $grawler->content();



        $this->assertInstanceOf(Grawler::class, $grawler);
    }

}