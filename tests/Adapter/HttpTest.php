<?php

namespace BEAR\Resource;

use Ray\Di\Annotation,
Ray\Di\Config,
Ray\Di\Forge,
Ray\Di\Container,
Ray\Di\Manager,
Ray\Di\Injector,
Ray\Di\EmptyModule;

use BEAR\Resource\Builder,
BEAR\Resource\Mock\User;

use Guzzle\Service\Client as GuzzleClient;

/**
 * Test class for BEAR.Resource.
 */
class HttpAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $skelton;

    protected function setUp()
    {
        parent::setUp();
        $injector = new Injector(new Container(new Forge(new Config(new Annotation))), new EmptyModule);
        $scheme = new SchemeCollection;
        $scheme->scheme('http')->host('*')->toAdapter(new \BEAR\Resource\Adapter\Http);
        $this->factory = new Factory($scheme);
        $this->httpAdapter = $this->factory->newInstance('http://news.google.com/news?hl=ja&ned=us&ie=UTF-8&oe=UTF-8&output=rss');
    }

    public function test_New()
    {
        $this->assertInstanceOf('\BEAR\Resource\Adapter\Http\Guzzle', $this->httpAdapter);
    }

    public function testGetHeader()
    {
        $ro = $this->httpAdapter->onGet();
        $this->assertSame($ro->headers['Content-Type'], 'application/xml; charset=UTF-8');
    }

    public function testGetHeaderRepeatWithCache()
    {
        foreach(range(1,10) as $i) {
            $ro = $this->httpAdapter->onGet();
        }
        $this->assertSame($ro->headers['Content-Type'], 'application/xml; charset=UTF-8');
    }

    public function testGetBody()
    {
        $ro = $this->httpAdapter->onGet();
        $actual = (string)($ro->body->channel->title[0]);
        $expected = 'Top Stories - Google News';
        $this->assertSame($expected, $actual);
    }

    /**
     *
     * @expectedException Guzzle\Http\Message\BadResponseException
     */
    public function test404()
    {
        $this->httpAdapter = $this->factory->newInstance('http://news.google.com/notexists/');
        $ro = $this->httpAdapter->onGet();
        $expected = 'Top Stories - Google News';
        $this->assertSame($expected, $actual);
    }


    /**
     * @expectedException BEAR\Resource\Exception
     */
    //     public function test_Exception()
    //     {
    //         throw new Exception;
    //     }
    }