<?php 

namespace Md\Http\Exceptions;

use function filter_var, header, http_response_code, in_array, trim;

class AppException extends \Exception
{
    public static function throw(?\Throwable $ex)
    {
        http_response_code((filter_var($ex->getCode(), FILTER_VALIDATE_INT, ['options' => ['min_range'=> 400, 'max_range'=> 406]]) ? $ex->getCode() : 500));
        
        if( in_array('text/html', explode(',', $_SERVER['HTTP_ACCEPT'] ?? [])) ) {
            header('Content-Type: text/html');
            exit(('<h1>Error ' . $ex->getCode() .'</h1><p>' . $ex->getMessage() . '</p>'));
        } else {
            header('Content-Type: application/json;charset=utf-8');
            exit('{"error": {"code": "' . $ex->getCode() .'", "message": "' . $ex->getMessage() . '"}}');
        }
    }

    public function __construct(string $msg = "Internal Error", int $code = 500)
    {
        $msg = !empty($msg) ? trim($msg) : 'Internal Error';
        parent::__construct($msg, $code);
    }
}
