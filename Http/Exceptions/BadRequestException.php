<?php 

namespace Md\Http\Exceptions;


class BadRequestException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Bad Request !", 400);
    }
}