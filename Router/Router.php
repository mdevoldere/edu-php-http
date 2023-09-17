<?php 

namespace Md\Router;

use Md\Http\Request;
use Md\Http\Response;
use Md\Http\Uri;

use function mb_substr, str_ends_with, str_starts_with;

class Router 
{
    private Uri $route;
    public readonly Request $req;
    public readonly Response $res;

    public function __construct(string $prefix = '', ?Request $request = null)
    {
        $this->req = $request ?? new Request();
        $this->req->uri->substract($prefix);
        $this->route = new Uri();
        $this->res = new Response();
    }

    public function r(string $route): static 
    {
        $this->route = new Uri($route);
        return $this;
    }

    protected function exactMatch(): bool
    {
        return $this->route->path === '*' ?: $this->route->path === $this->req->uri->path; 
    }

    protected function prefixMatch(): bool
    {
        return str_starts_with($this->req->uri->getPath(), $this->route->getPath());
    }

    protected function paramsMatch(): bool
    {
        $params = [];

        foreach($this->route->getParts() as $pos => $part) {
            $val = $this->req->uri->getPart($pos);
            if(str_starts_with($part, ':')) {
                if(!str_ends_with($part, '?') && $val === null) {
                    return false;
                }
                $params[mb_substr($part, 1)] = $val;
            } else {
                if($val !== $part) {
                    return false;
                }
            }
        }

        $this->req->params->addRange($params);
        return !empty($params);
    }

    public function prefixAll(array $routes, callable $callback): Router
    {
        foreach($routes as $route => $controller) {
            if($this->r($route)->prefixMatch()) {
                $this->req->uri->substract($this->route->getPath());
                $callback($this, $controller);
            }
        }
        return $this;
    }

    public function prefix(string $route, callable $callback): Router
    {
        if($this->r($route)->prefixMatch()) {
            $this->req->uri->substract($this->route->getPath());
            $callback($this);
        }
        return $this;
    }

    public function all(string $route, callable $callback): Router
    {
        if($this->r($route)->exactMatch() ?: $this->paramsMatch()) {
            $callback($this);
        }
        return $this;
    }

    final public function get(string $route, callable $callback): Router 
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET' ? $this->all($route, $callback) : $this;
    }

    final public function post(string $route, callable $callback): Router 
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST' ? $this->all($route, $callback) : $this;
    }

    final public function put(string $route, callable $callback): Router 
    {
        return ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH') 
                ? $this->all($route, $callback) : $this;
    }

    final public function delete(string $route, callable $callback): Router 
    {
        return $_SERVER['REQUEST_METHOD'] === 'DELETE' ? $this->all($route, $callback) : $this;
    }

}
