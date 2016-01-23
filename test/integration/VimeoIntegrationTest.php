<?php
use Bowtie\Grawler\Nodes\Resolvers\VimeoResolver;
use Bowtie\Grawler\Nodes\Resolvers\YoutubeResolver;


class VimeoIntegrationTest extends IntegrationTest
{


    /** @test */
    public function it_resolves_vimeos_url_data()
    {
        $youtubeResolver = new VimeoResolver('https://vimeo.com/channels/staffpicks/152150724');

        $reflection = new \ReflectionClass(get_class($youtubeResolver));
        $method = $reflection->getMethod('doResolve');
        $method->setAccessible(true);

        $data = $method->invokeArgs($youtubeResolver, $args = []);

        file_put_contents(__DIR__ . '/../resources/json/vimeo-response.json', serialize($data));

        $this->assertArrayHasKey('name',$data);
        $this->assertArrayHasKey('description',$data);
    }

    /** @test */
    public function it_returns_false_for_invalid_vimeo_url_response()
    {
        $youtubeResolver = new VimeoResolver('https://www.youtube.com/watch?v=eZ-js5zn-I0');

        $youtubeResolver->loadConfig(['vimeoKey'=>'123','vimeoSecret'=>'123']);

        $reflection = new \ReflectionClass(get_class($youtubeResolver));
        $method = $reflection->getMethod('doResolve');
        $method->setAccessible(true);

        $data = $method->invokeArgs($youtubeResolver, $args = []);

        $this->assertFalse($data);
    }

}
