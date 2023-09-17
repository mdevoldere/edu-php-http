<?php 

namespace Md\Http;

use Md\Http\Validator;

use function array_merge, file_get_contents, filter_var, http_response_code, mb_substr, preg_match, str_starts_with;

class ServerRequest extends Request
{
    public function __construct()
    {
        parent::__construct($_SERVER['REQUEST_URI']);
    }

    public function isPost(): bool 
    {
        if(!empty($_POST)) {
            $this->params->addRange($_POST);
            return true;
        }
        return false;
    }

    public function text(): string 
    {
        return file_get_contents('php://input') ?? '';
    }

    public function json(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
}
