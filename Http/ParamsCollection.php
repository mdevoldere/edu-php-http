<?php 

namespace Md\Http;

class ParamsCollection 
{
    private array $c;

    public function __construct()
    {
        $this->c = [];
    }

    public function getAll(): array 
    {
        return $this->c;
    }

    public function get(string $k): mixed
    {
        return $this->c[$k] ?? null;
    }

    public function add(string $k, mixed $v): mixed
    {
        $v = Validator::validateRegex($v, Validator::ALPHANUM);
        $this->c[$k] = $v;
        return $v;
    }

    public function addRange(array $v): void
    {
        $v = array_map(function($a) {
            return Validator::validateRegex($a, Validator::ALPHANUM);
        }, $v);

        $this->c = \array_merge($this->c, $v);
    }
}
