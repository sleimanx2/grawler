<?php


use Bowtie\Grawler\Nodes\Media;
use Bowtie\Grawler\Nodes\Resolvers\VimeoResolver;
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

        $this->assertCount(3,$mediaSubClass->resolvers());
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


    /**
     * @test
     */
    function it_returns_false_if_no_resolver_found_to_resolve()
    {
        $mediaSubClass = new EmptyMediaSubClass('http://example.com/home');

        $this->assertEquals(null,$mediaSubClass->resolve());
    }


    /**
     * @test
     */
    function it_validates_media_url_against_media_resolvers_searching_for_a_match()
    {
        $mediaSubClass = new MediaSubClass('http://example.com/home');

        $this->assertEquals(null,$mediaSubClass->resolve());
    }


}

class MediaSubClass extends Media
{
    protected $resolvers = [YoutubeResolver::class,VimeoResolver::class];
}

class EmptyMediaSubClass extends Media
{
    protected $resolvers = [];
}