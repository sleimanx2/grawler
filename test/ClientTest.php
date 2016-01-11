<?php


use Bowtie\Grawler\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;

class ClientTest extends PHPUnit_Framework_TestCase
{

    /** @var Guzzle Request History */
    protected $history;

    /** @var MockHandler */
    protected $mock;

    /** @test */
    function it_can_set_a_custom_user_agent()
    {
        $client = $this->createDefaultClient();

        $client->agent('foo')->download('http://example.com');

        $this->assertEquals('foo', end($this->history)['request']->getHeaderLine('User-Agent'));
    }

    /** @test */
    function it_can_set_custom_auth_credentials()
    {
        $client = $this->createDefaultClient();

        $client->auth('me', '**')->download('http://example.com');

        $this->assertEquals('Basic bWU6Kio=', end($this->history)['request']->getMeth('Authorization'));
    }

    /** @test */
    function it_can_set_custom_http_method()
    {
        $client = $this->createDefaultClient();

        $client->method('post')->download('http://example.com');

        $this->assertEquals('POST', end($this->history)['request']->getMethod());

    }


    /**
     * @return Client
     */
    protected function createDefaultClient()
    {
        $client = new Client();

        $client->setClient($this->getGuzzle());

        return $client;
    }

    /**
     * mock guzzle response and save the requests in history
     *
     * @param array $responses
     * @return GuzzleClient
     */
    protected function getGuzzle(array $responses = [])
    {
        if (empty($responses)) {
            $responses = [new Response(200, [], '<html><body><p>Hi</p></body></html>')];
        }
        $this->mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($this->mock);
        $this->history = [];
        $handlerStack->push(Middleware::history($this->history));
        $guzzle = new GuzzleClient(['redirect.disable' => true, 'base_uri' => '', 'handler' => $handlerStack]);

        return $guzzle;
    }
}