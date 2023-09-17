<?php 

namespace Md\Http;

use Md\Http\Validator;

use function array_filter, file_get_contents, filter_var, http_response_code, mb_substr, preg_match, str_starts_with;

class Request extends Message
{
    public readonly Uri $uri;

    public readonly ParamsCollection $params;

    public function __construct(?string $_uri = null)
    {
        parent::__construct();
        $this->uri = new Uri($_uri ?? '/');
        Validator::safeUriString($this->uri->path);
        $this->params = new ParamsCollection();
    }
}
