<?php


use Bowtie\Grawler\Nodes\Media;
use Bowtie\Grawler\Nodes\Resolvers\YoutubeResolver;

class MediaTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @test
     * @expectedException InvalidArgumentException
     */
    function it_doesnt_accept_none_absolute_urls()
    {
            new MediaSubClass('/example');
    }

    /** @test */
    function it_can_add_valid_resolvers_to_the_subclass_list()
    {
        $mediaSubClass = new MediaSubClass();

        $mediaSubClass->addResolvers(YoutubeResolver::class);

        $this->assertCount(1,$mediaSubClass->resolvers());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    function it_doesnt_accept_invalid_resolvers()
    {
        $mediaSubClass = new MediaSubClass();

        $mediaSubClass->addResolvers(\Bowtie\Grawler\Nodes\Video::class);
    }

}


class MediaSubClass extends Media
{
    protected $resolvers = [];
}