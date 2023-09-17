<?php 

namespace Md\Http;

use function array_filter, explode, preg_replace, trim;

class Uri 
{  
    public readonly string $url;

    public readonly string $path;

    public readonly array $query;

    public readonly array $parts;

    public function __construct(string $uri = '/')
    {
        $this->url = preg_replace(['#\.+/#','#/+#'], '/', $uri);
        $p = parse_url($this->url);
        $this->path = trim($p['path'] ?? '/', '/');
        $this->parts = array_filter(explode('/', $this->path));
        parse_str($p['query'] ?? '', $this->query);
    }

    public function setUri(string $path = '/'): Uri 
    {
        return new Uri($path);
    }

    public function substract(string $prefix): Uri 
    {
        $prefix = trim($prefix, '/');
        if(!empty($prefix) && str_starts_with($this->path, $prefix)) {
            return $this->setUri(mb_substr($this->path, mb_strlen($prefix), mb_strlen($this->path)));
        }
        return $this;
    }
}
