<?php

namespace Alb\OEmbed;

/**
 * An oEmbed response
 */
class Response
{
    protected $fields = array(
        'type' => 'type',
        'version' => 'version',
        'title' => 'title',
        'author_name' => 'authorName',
        'author_url' => 'authorUrl',
        'provider_name' => 'providerName',
        'provider_url' => 'providerUrl',
        'cache_age' => 'cacheAge',
        'thumbnail_url' => 'thumbnailUrl',
        'thumbnail_width' => 'thumbnailWidth',
        'thumbnail_height' => 'thumbnailHeight',
        'html' => 'html',
        'width' => 'width',
        'height' => 'height',
    );

    protected $type;
    protected $version;
    protected $title;
    protected $authorName;
    protected $authorUrl;
    protected $providerName;
    protected $providerUrl;
    protected $cacheAge;
    protected $thumbnailUrl;
    protected $thumbnailWidth;
    protected $thumbnailHeight;
    protected $html;
    protected $raw;

    public function __construct($data)
    {
        foreach($data as $key => $value) {
            if (isset($this->fields[$key])) {
                $prop = $this->fields[$key];
                $this->$prop = $value;
            }
        }

        $this->raw = $data;
    }

    public function __call($name, $args)
    {
        if (strncmp($name, 'get', 3) === 0) {
            $prop = strtolower($name[3]) . substr($name, 4);
            return $this->$prop;
        } else {
            throw new \BadMethodCallException(sprintf('method %s doesn\'t exists', $name));
        }
    }

    public function toArray()
    {
        $ary = array();

        foreach($this->fields as $name => $prop) {
            $ary[$name] = $this->$prop;
        }

        return $ary;
    }
}

