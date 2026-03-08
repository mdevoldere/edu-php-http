<?php 

require dirname(__DIR__) . '/vendor/autoload.php';

use Md\Router\Router;

$_i = 0;
function d($var) { 
    global $_i;
    echo '<pre>'.(++$_i).': '.var_export($var, true).'</pre><hr>';
}


$r = new Router('');

//d($r);

$r->get('/tests/toto', function(Router $r) {
    d($r);
    echo 'Hello Toto';
});

$r->get('*', function(Router $r) {
    d($r);
    echo 'Hello World';
});
