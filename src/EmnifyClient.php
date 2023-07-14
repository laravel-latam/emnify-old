<?php

namespace LaravelLatam\Emnify;

use Illuminate\Support\Facades\Http;

class EmnifyClient extends Emnify
{
    public $emnify;
    private string $_protocol = 'https';
    private string $_host = 'cdn.emnify.net';
    private string $_uri = '/api/';
    private string $_version = 'v1';
    private string $_endpoint;
    private string $_token;

    public function boot()
    {
        dd("xD");
    }
    public function auth()
    {
        dd("xD");
    }

    final public function __construct()
    {
        $this->_endpoint = sprintf('%s://%s%s%s', $this->_protocol, $this->_host, $this->_uri, $this->_version);
    }

    public function __call($method, $parameters)
    {
    }

    public function setOpt($name, $value): EmnifyClient
    {

        return $this;
    }
}
