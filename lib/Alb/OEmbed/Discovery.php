<?php

namespace Alb\OEmbed;

/**
 * Class responsible for discovering oEmbed endpoints
 * See http://oembed.com/#section4
 */
class Discovery
{
    /**
     * Discovers and API endpoint from an URL
     *
     * @param string $url URL
     * @return Provider A Provider instance or NULL
     */
    public function discover($url)
    {
        $html = $this->fetchUrl($url);
        return $this->discoverFromHtml($html);
    }

    /**
     * Discovers and API endpoint from an HTML document
     *
     * @param string $html HTML document
     * @return Provider A Provider instance or NULL
     */
    public function discoverFromHtml($html)
    {
        $links = $this->findLinks($html);

        if ( ! $link = reset($links)) {
            return;
        }

        $type = null;

        switch($link['type']) {
        case 'application/json+oembed':
            $type = Provider::TYPE_JSON;
            break;
        case 'text/xml+oembed':
            $type = Provider::TYPE_XML;
            break;
        }

        $url = $link['href'];
        $endpoint = $this->getEndpointUrl($url);

        return new Provider($endpoint, $type);
    }

    public function fetchLinks($url)
    {
        $html = $this->fetchUrl($url);

        return $this->findLinks($html);
    }

    public function findLinks($html)
    {
        $dom = $this->parseHtml($html);

        $links = array();

        $types = array('application/json+oembed', 'text/xml+oembed');

        foreach($dom->getElementsByTagName('link') as $link) {
            if (!preg_match('#\balternat(e|ive)\b#', $link->getAttribute('rel'))) {
                continue;
            }
            if (!in_array($link->getAttribute('type'), $types)) {
                continue;
            }
            $links[] = array(
                'type' => $link->getAttribute('type'),
                'href' => $link->getAttribute('href'),
                'title' => $link->getAttribute('title'),
            );
        }

        return $links;
    }

    /**
     * Returns an endpoint URL from an URL with the 'url' param pre-filled
     *
     * @param string $url URL
     * @return string URL
     */
    public function getEndpointUrl($url)
    {
        $components = parse_url($url);

        if (isset($components['query'])) {
            parse_str($components['query'], $query);
            unset($query['url']);
            $components['query'] = http_build_query($query);
        }

        return $this->assembleUrl($components);
    }

    protected function fetchUrl($url)
    {
        $html = @file_get_contents($url);
        if ( false === $html ) {
            throw new \Exception( 'Failed to open stream: HTTP request failed' );
        }
        return $html;
    }

    protected function parseHtml($html)
    {
        $dom = new \DomDocument;
        $old = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_use_internal_errors($old);

        return $dom;
    }

    protected function assembleUrl($components)
    {
        if (isset($components['scheme'])) {
            $url = $components['scheme'] . '://';
        }
        if (isset($components['user'])) {
            $url .= $components['user'];
            if (isset($components['password'])) {
                $url .= ':' . $components['password'];
            }
            $url .= '@';
        }
        if (isset($components['host'])) {
            $url .= $components['host'];
        }
        if (isset($components['port'])) {
            $url .= ':' . $components['port'];
        }
        if (isset($components['path'])) {
            $url .= $components['path'];
        }
        if (isset($components['query'])) {
            $url .= '?' . $components['query'];
        }
        if (isset($components['fragment'])) {
            $url .= '#' . $components['fragment'];
        }

        return $url;
    }
}

