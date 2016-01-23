<?php
use Bowtie\Grawler\Nodes\Resolvers\SoundCloudResolver;


class SoundCloudIntegrationTest extends IntegrationTest
{

    /** @test */
    public function it_resolves_soundcloud_embed_url_data()
    {
        $soundCloudResolver = new SoundCloudResolver('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/243208091&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true');

        $reflection = new \ReflectionClass(get_class($soundCloudResolver));
        $method = $reflection->getMethod('doResolve');
        $method->setAccessible(true);

        $data = $method->invokeArgs($soundCloudResolver, $args = []);

        file_put_contents(__DIR__ . '/../resources/json/soundcloud-response.json', serialize($data));

        $this->assertArrayHasKey('kind', $data);
        $this->assertArrayHasKey('id', $data);
    }

    /** @test */
    public function it_resolves_soundcloud_url_data()
    {
        $soundCloudResolver = new SoundCloudResolver('https://soundcloud.com/namito/christian-hornbostel-chemical-species-namito-remix-preview');

        $reflection = new \ReflectionClass(get_class($soundCloudResolver));
        $method = $reflection->getMethod('doResolve');
        $method->setAccessible(true);

        $data = $method->invokeArgs($soundCloudResolver, $args = []);

        file_put_contents(__DIR__ . '/../resources/json/soundcloud-response.json', serialize($data));

        $this->assertArrayHasKey('kind', $data);
        $this->assertArrayHasKey('id', $data);
    }

}
