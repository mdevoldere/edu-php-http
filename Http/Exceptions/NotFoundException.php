<?php 

namespace Md\Http\Exceptions;


class NotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Not Found !", 404);
    }
}
