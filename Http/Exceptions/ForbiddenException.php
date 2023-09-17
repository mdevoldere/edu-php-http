<?php 

namespace Md\Http\Exceptions;


class ForbiddenException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Forbidden !", 403);
    }
}
