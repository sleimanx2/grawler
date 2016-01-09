<?php

use Bowtie\Grawler\Grawler;

class GrawlerTest extends PHPUnit_Framework_TestCase
{

    /** @test */
    function it_can_be_created()
    {
        $grawler = new Grawler();

        $this->assertInstanceOf(Grawler::class, $grawler);
    }

}