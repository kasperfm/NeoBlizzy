<?php

namespace KasperFM\NeoBlizzy\Services;

use Illuminate\Support\Str;

class ApiResult
{
    private $followLinks = false;
    private $accessToken;

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public function setAccessToken($token)
    {
        $this->accessToken = $token;

        return $this;
    }

    public function followLinks()
    {
        $this->followLinks = true;

        $this->items->transform(function ($item, $key) {
            if (isset($item->href)) {
                return $this->setAccessToken($this->accessToken)->followApiLink($item->href);
            }

            if (isset($item->key->href)) {
                return $this->setAccessToken($this->accessToken)->followApiLink($item->key->href);
            }

            return $item;
        });

        return $this;
    }

    public function __get($name)
    {
        if (!empty($name)) {
            if(isset($this->items[$name]) || property_exists($this->items, $name)) {
                return $this->items[$name];
            }
        } else {
            throw new \Exception("The $name property does not exists in the api result!");
        }
    }

    protected function followApiLink($link, $parameters = [])
    {
        $url = parse_url($link);

        if(!$this->accessToken) {
            return $link;
        }

        $namespace = Str::after($url['query'], 'namespace=');

        $parameters = array_merge($parameters, [
            'access_token' => $this->accessToken,
            'locale' => config('neoblizzy.locale'),
            'namespace' => $namespace
        ]);

        $result = new self(collect(\NeoBlizzy::cacheApiCall($url['scheme'] . '://' . $url['host'] . $url['path'], $parameters)));

        return $result->setAccessToken($this->accessToken);
    }
}
