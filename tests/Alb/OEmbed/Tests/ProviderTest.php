<?php

namespace Alb\OEmbed\Tests;

use Alb\OEmbed\Provider;

class ProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testJson()
    {
        $json = file_get_contents(__DIR__ . '/fixtures/provider/001.json');

        $provider = new Provider('http://www.example.com/', Provider::TYPE_JSON);
        $response = $provider->createResponseFromJson($json);

        $this->assertSame('video', $response->getType());
        $this->assertSame('http://i3.ytimg.com/vi/zTDGSjSBHn4/hqdefault.jpg', $response->getThumbnailUrl());
    }

    public function testXml()
    {
        $json = file_get_contents(__DIR__ . '/fixtures/provider/002.xml');

        $provider = new Provider('http://www.example.com/', Provider::TYPE_JSON);
        $response = $provider->createResponseFromXml($json);

        $this->assertSame('video', $response->getType());
        $this->assertSame('http://i3.ytimg.com/vi/zTDGSjSBHn4/hqdefault.jpg', $response->getThumbnailUrl());
    }

    public function testKo()
    {
        $provider = new Provider('', Provider::TYPE_JSON);
        $this->setExpectedException( 'Exception' );
        $provider->request('http://i3.ytimg.com/vi/zTDGSjSBHn4/hqdefault.jpg');
    }
}

