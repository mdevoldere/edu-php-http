<?php 

namespace Md\Http\Exceptions;


class InvalidContextException extends \Exception
{
    public function __construct(string $msg, ?\Throwable $ex = null)
    {
        parent::__construct($msg . ' ' . ($ex?->getMessage() ?? ''), 500);
    }
}
