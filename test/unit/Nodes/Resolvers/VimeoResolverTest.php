<?php
use Bowtie\Grawler\Config\Config;
use Bowtie\Grawler\Nodes\Image;
use Bowtie\Grawler\Nodes\Resolvers\Resolver;
use Bowtie\Grawler\Nodes\Resolvers\VimeoResolver;
use Bowtie\Grawler\Nodes\Resolvers\YoutubeResolver;


class VimeoResolverTest extends PHPUnit_Framework_TestCase
{

    /** @test */
    public function it_returns_false_for_invalid_youtube_url()
    {
        $vimeoResolver = new VimeoResolver('vimeo-fake.com/video/vidid');
        $this->assertFalse($vimeoResolver->validate());
    }


    /** @test */
    public function it_can_validate_multiple_youtube_url_formats_and_returns_the_id()
    {
        $vimeoResolver = new VimeoResolver('');

        $vimeoResolver->changeUrl('http://vimeo.com/6701908');
        $this->assertEquals('6701908', $vimeoResolver->validate());

        $vimeoResolver->changeUrl('http://vimeo.com/670190233');
        $this->assertEquals('670190233', $vimeoResolver->validate());

        $vimeoResolver->changeUrl('http://player.vimeo.com/video/67019023');
        $this->assertEquals('67019023', $vimeoResolver->validate());

        $vimeoResolver->changeUrl('http://player.vimeo.com/video/6701906');
        $this->assertEquals('6701906', $vimeoResolver->validate());

        $vimeoResolver->changeUrl('http://player.vimeo.com/video/67019022?title=0&amp;byline=0&amp;portrait=0');
        $this->assertEquals('67019022', $vimeoResolver->validate());

        $vimeoResolver->changeUrl('http://player.vimeo.com/video/6719022?title=0&amp;byline=0&amp;portrait=0');
        $this->assertEquals('6719022', $vimeoResolver->validate());

        $vimeoResolver->changeUrl('http://vimeo.com/channels/vimeo/6701903');
        $this->assertEquals('6701903', $vimeoResolver->validate());

        $vimeoResolver->changeUrl('http://vimeo.com/channels/staffpicks/67019026');
        $this->assertEquals('67019026', $vimeoResolver->validate());

        $vimeoResolver->changeUrl('http://vimeo.com/15414122');
        $this->assertEquals('15414122', $vimeoResolver->validate());
    }


    /** @test */
    public function it_can_resolve_youtube_video_attributes()
    {
        $resolver = $this->initVimeoResolver();

        $video = $resolver->resolve();

        $this->assertEquals('152150724', $video->id);

        $this->assertEquals('The Self Practice', $video->title);
        $this->assertNotFalse(strpos($video->description, 'is a short documentary'));
        $this->assertEquals('Cameron Bryson', $video->author);
        $this->assertEquals('2023887', $video->authorId);

        $this->assertInstanceOf(Image::class, $video->images[0]);
        $this->assertEquals(100, $video->images[0]->width);
        $this->assertEquals(75, $video->images[0]->height);

        $this->assertEquals('https://vimeo.com/cameronbryson/theselfpractice', $video->url);
        $this->assertEquals('https://player.vimeo.com/video/152150724', $video->embedUrl);

        $this->assertEquals('00:14:21', $video->duration);

        $this->assertEquals('vimeo', $video->provider);
    }


    /** @test */
    public function it_returns_false_when_resolving_invalid_youtube_urls()
    {
        $resolver = new VimeoResolver('http://example.com');

        $this->assertFalse($resolver->resolve());
    }


    /** @test */
    public function it_returns_false_if_their_was_an_issue_while_resolving()
    {
        $resolver = $this->initVimeoResolver($validDoResolveResponse = false);

        $this->assertFalse($resolver->resolve());
    }

    /** @test */
    public function it_allow_access_to_raw_data_value()
    {
        $resolver = $this->initVimeoResolver($validDoResolveResponse = false);

        $this->assertEquals(null, $resolver->rawData());
    }


    protected function initVimeoResolver($validDoResolveResponse = true)
    {
        Mockery::mock(Resolver::class);

        $vimeoResolver = Mockery::mock(
            VimeoResolver::class."[doResolve,loadConfig]",
            ['https://vimeo.com/channels/staffpicks/152150724']
        )->makePartial()->shouldAllowMockingProtectedMethods()->shouldIgnoreMissing();

        $doResolveResponse = $validDoResolveResponse ? unserialize(file_get_contents(__DIR__ . '/../../../resources/json/vimeo-response.json')) : false;

        $vimeoResolver->shouldReceive('doResolve')->andReturn($doResolveResponse);

        return $vimeoResolver;
    }
}
