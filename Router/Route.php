<?php 


namespace Md\Router;

class Route 
{
    public readonly string $url;

    public function __construct(string $url)
    {
        $this->url = ('/'.trim($url, '/').'/');
    }
}
