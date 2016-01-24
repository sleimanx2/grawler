<?php
use Bowtie\Grawler\Config\Config;
use Bowtie\Grawler\Nodes\Image;
use Bowtie\Grawler\Nodes\Resolvers\Resolver;
use Bowtie\Grawler\Nodes\Resolvers\YoutubeResolver;


class YoutubeResolverTest extends PHPUnit_Framework_TestCase
{

    /** @test */
    public function it_returns_false_for_invalid_youtube_url()
    {
        $youtubeResolver = new YoutubeResolver('youtube-fake.com/video/vidid');
        $this->assertFalse($youtubeResolver->validate());
    }


    /** @test */
    public function it_can_validate_multiple_youtube_url_formats_and_returns_the_id()
    {
        $youtubeResolver = new YoutubeResolver('');

        $youtubeResolver->changeUrl('youtube.com/v/vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('youtube.com/vi/vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('youtube.com/v/vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('youtube.com/?v=vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('youtube.com/?vi=vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('youtube.com/watch?v=vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('youtube.com/watch?vi=vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('youtu.be/vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('youtube.com/embed/vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('http://youtube.com/v/vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('http://www.youtube.com/v/vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('https://www.youtube.com/v/vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('youtube.com/watch?v=vidid&wtv=wtv');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('http://www.youtube.com/watch?dev=inprogress&v=vidid&feature=related');
        $this->assertEquals('vidid', $youtubeResolver->validate());

        $youtubeResolver->changeUrl('https://m.youtube.com/watch?v=vidid');
        $this->assertEquals('vidid', $youtubeResolver->validate());
    }


    /** @test */
    public function it_can_resolve_youtube_video_attributes()
    {
        $resolver = $this->initYoutubeResolver();

        $video = $resolver->resolve();

        $this->assertEquals('eZ-js5zn-I0', $video->id);

        $this->assertEquals('The Making of Drone 100 | Intel', $video->title);
        $this->assertNotFalse(strpos($video->description, 'Intel and Ars Electronica FutureLab explain'));
        $this->assertEquals('Intel', $video->author);
        $this->assertEquals('UCk7SjrXVXAj8m8BLgzh6dGA', $video->authorId);


        $this->assertInstanceOf(Image::class, $video->images[0]);
        $this->assertEquals(120, $video->images[0]->width);
        $this->assertEquals(90, $video->images[0]->height);

        $this->assertEquals('http://www.youtube.com/watch?v=eZ-js5zn-I0', $video->url);
        $this->assertEquals('http://www.youtube.com/embed/eZ-js5zn-I0', $video->embedUrl);

        $this->assertEquals('00:05:35', $video->duration);

        $this->assertEquals('youtube', $video->provider);
    }


    /** @test */
    public function it_returns_false_when_resolving_invalid_youtube_urls()
    {
        $resolver = new YoutubeResolver('http://example.com');

        $this->assertFalse($resolver->resolve());
    }


    /** @test */
    public function it_returns_false_if_their_was_an_issue_while_resolving()
    {
        $resolver = $this->initYoutubeResolver($validDoResolveResponse = false);

        $this->assertFalse($resolver->resolve());
    }

    /** @test */
    public function it_allow_access_to_raw_data_value()
    {
        $resolver = $this->initYoutubeResolver($validDoResolveResponse = false);

        $this->assertEquals(null, $resolver->rawData());
    }


    protected function initYoutubeResolver($validDoResolveResponse = true)
    {
        Mockery::mock(Resolver::class);

        $youtubeResolver = Mockery::mock(
            YoutubeResolver::class . "[doResolve,loadConfig]",
            ['https://www.youtube.com/watch?v=eZ-js5zn-I0']
        )->makePartial()->shouldAllowMockingProtectedMethods()->shouldIgnoreMissing();

        $doResolveResponse = $validDoResolveResponse ? unserialize(file_get_contents(__DIR__ . '/../../../resources/json/youtube-response.json')) : false;

        $youtubeResolver->shouldReceive('doResolve')->andReturn($doResolveResponse);

        return $youtubeResolver;
    }
}
