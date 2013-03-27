<?php

namespace Alb\OEmbed\Tests;

use Alb\OEmbed\Discovery;
use Alb\OEmbed\Provider;

class DiscoveryTest extends \PHPUnit_Framework_TestCase
{
    public function testFindLinks()
    {
        $html = file_get_contents(__DIR__ . '/fixtures/discovery/001.html');

        $discovery = new Discovery;
        $links = $discovery->findLinks($html);

        $this->assertSame(array(
            array(
                'type' => 'application/json+oembed',
                'href' => 'http://www.youtube.com/oembed?url=http%3A//www.youtube.com/watch?v%3DzTDGSjSBHn4&format=json',
                'title' => 'bref j\'ai joué à call of duty avec diablox9 (parodie bref)',
            ),
            array(
                'type' => 'text/xml+oembed',
                'href' => 'http://www.youtube.com/oembed?url=http%3A//www.youtube.com/watch?v%3DzTDGSjSBHn4&format=xml',
                'title' => 'bref j\'ai joué à call of duty avec diablox9 (parodie bref)',
            ),
        ), $links);
    }

    public function testDiscoverFromHtml()
    {
        $html = file_get_contents(__DIR__ . '/fixtures/discovery/001.html');

        $discovery = new Discovery;
        $provider = $discovery->discoverFromHtml($html);

        $this->assertSame('http://www.youtube.com/oembed?format=json', $provider->getEndpoint());
        $this->assertSame(Provider::TYPE_JSON, $provider->getType());
    }

    public function testDiscoverFromHtml2()
    {
        $html = file_get_contents(__DIR__ . '/fixtures/discovery/002.html');

        $discovery = new Discovery;
        $provider = $discovery->discoverFromHtml($html);

        $this->assertSame('http://www.youtube.com/oembed?format=xml', $provider->getEndpoint());
        $this->assertSame(Provider::TYPE_XML, $provider->getType());
    }

    public function testDiscoverKo()
    {
        $discovery = new Discovery;
        $this->setExpectedException( 'Exception' );
        $discovery->discover('');
    }
}

