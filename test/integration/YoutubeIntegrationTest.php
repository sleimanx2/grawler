<?php
use Bowtie\Grawler\Nodes\Resolvers\YoutubeResolver;


class YoutubeIntegrationTest extends IntegrationTest
{

    /** @test */
    public function it_resolves_youtube_url_data()
    {
        $youtubeResolver = new YoutubeResolver('https://www.youtube.com/watch?v=eZ-js5zn-I0');

        $reflection = new \ReflectionClass(get_class($youtubeResolver));
        $method = $reflection->getMethod('doResolve');
        $method->setAccessible(true);

        $data = $method->invokeArgs($youtubeResolver, $args = []);

        file_put_contents(__DIR__ . '/../resources/json/youtube-response.json', serialize($data));

        $this->assertInstanceOf(Google_Service_YouTube_Video::class,$data);
    }

}
