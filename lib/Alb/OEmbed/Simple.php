<?php

namespace Alb\OEmbed;

class Simple
{
    static public function request($url, array $params = array())
    {
        if ($provider = static::getProvider($url)) {
            return $provider->request($url, $params);
        }
    }

    static public function getProvider($url)
    {
        $discovery = new Discovery;
        return $discovery->discover($url);
    }
}

