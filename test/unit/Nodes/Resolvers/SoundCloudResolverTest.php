<?php
use Bowtie\Grawler\Nodes\Image;
use Bowtie\Grawler\Nodes\Resolvers\Resolver;
use Bowtie\Grawler\Nodes\Resolvers\SoundCloudResolver;

class SoundCloudResolverTest extends PHPUnit_Framework_TestCase
{

    /** @test */
    public function it_returns_false_for_invalid_soundcloud_url()
    {
        $soundCloudResolver = new SoundCloudResolver('soundcloud-fake.com/audio/vidid');

        $this->assertFalse($soundCloudResolver->validate());
    }


    /** @test */
    public function it_can_validate_multiple_soundcloud_url_formats_and_returns_the_id()
    {
        $soundCloudResolver = new SoundCloudResolver('');

        $soundCloudResolver->changeUrl('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/242539038&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true');
        $this->assertEquals('242539038', $soundCloudResolver->validate());

        $soundCloudResolver->changeUrl('https://soundcloud.com/namito/christian-hornbostel-chemical-species-namito-remix-preview');
        $this->assertEquals('namito/christian-hornbostel-chemical-species-namito-remix-preview',
            $soundCloudResolver->validate());

    }

    /** @test */
    public function it_can_resolve_soundcloud_audio_attributes()
    {
        $resolver = $this->initSoundCloudResolver();

        $audio = $resolver->resolve();

        $this->assertEquals('243208091', $audio->id);

        $this->assertEquals('Christian Hornbostel  Chemical Species (Namito Remix) PREVIEW', $audio->title);
        $this->assertEquals('', $audio->description);
        $this->assertEquals('Namito!', $audio->author);
        $this->assertEquals('103', $audio->authorId);

        $this->assertEquals('Namito', $audio->genre);

        $this->assertInstanceOf(Image::class, $audio->images[0]);
        $this->assertEquals(null, $audio->images[0]->width);
        $this->assertEquals(null, $audio->images[0]->height);


        $this->assertEquals('http://soundcloud.com/namito/christian-hornbostel-chemical-species-namito-remix-preview',
            $audio->url);

        $this->assertEquals('https://w.soundcloud.com/player/?url=https://api.soundcloud.com/tracks/243208091',
            $audio->embedUrl);

        $this->assertEquals('https://api.soundcloud.com/tracks/243208091/stream',
            $audio->streamUrl);

        $this->assertEquals('00:03:12', $audio->duration);

        $this->assertEquals('soundcloud', $audio->provider);
    }

    /** @test */
    public function it_returns_false_when_resolving_invalid_soundcloud_urls()
    {
        $resolver = new SoundCloudResolver('http://example.com');

        $this->assertFalse($resolver->resolve());
    }


    /** @test */
    public function it_returns_false_if_their_was_an_issue_while_resolving()
    {
        $resolver = $this->initSoundCloudResolver($validDoResolveResponse = false);

        $this->assertFalse($resolver->resolve());
    }

    /** @test */
    public function it_allow_access_to_raw_data_value()
    {
        $resolver = $this->initSoundCloudResolver($validDoResolveResponse = false);

        $this->assertEquals(null, $resolver->rawData());
    }

    protected function initSoundCloudResolver($validDoResolveResponse = true)
    {
        Mockery::mock(Resolver::class);

        $soundCloudResolver = Mockery::mock(
            SoundCloudResolver::class . "[doResolve,loadConfig]",
            ['https://soundcloud.com/namito/christian-hornbostel-chemical-species-namito-remix-preview']
        )->makePartial()->shouldAllowMockingProtectedMethods()->shouldIgnoreMissing();

        $doResolveResponse = $validDoResolveResponse ? unserialize(file_get_contents(__DIR__ . '/../../../resources/json/soundcloud-response.json')) : false;

        $soundCloudResolver->shouldReceive('doResolve')->andReturn($doResolveResponse);

        return $soundCloudResolver;
    }
}
