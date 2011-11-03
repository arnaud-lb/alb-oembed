<?php

namespace Alb\OEmbed\Tests;

use Alb\OEmbed\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse()
    {
        $response = new Response(array(
            'type' => 'video',
            'version' => '1.0',
            'html' => '<x>',
            'width' => 400,
            'height' => 300,
            'provider_name' => 'Youtube',
        ));

        $this->assertSame('video', $response->getType());
        $this->assertSame('<x>', $response->getHtml());
        $this->assertSame('Youtube', $response->getProviderName());
    }
}

