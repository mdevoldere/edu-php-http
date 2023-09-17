<?php 

namespace Md\Http;

class Response extends Message
{
    /** Define a HTML message type */
    public const HTML = 'text/html';
    /** Define a JSON message type */
    public const JSON = 'application/json';
    /** Define a TEXT message type */
    public const TEXT = 'text/plain';

    public static function end(int $code, string $body, string $contentType = self::HTML): never
    {
        http_response_code($code);
        header('Content-Type: ' . $contentType);
        echo $body;
        exit;
    }

    public static function redirect(string $url): never
    {
        header('location:' . $url);
        exit;
    }

    public static function error(int $code = 404, string $msg = 'Not found', string $contentType = self::HTML): never
    {
        self::end($code, ('[' . $code . '] ' . $msg . ' !'), $contentType);
    }

    public static function cors(array $methods = ['GET']): void
    {
        header('Access-Control-Allow-Headers: access-control-allow-origin');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: '. implode(',', $methods) .'');
    }

    /** @var int $code The current message HTTP code */
    private int $code;

    public function __construct()
    {
        parent::__construct();
        $this->code = 200;
    }

    public function send(): never
    {
        self::end($this->code, $this->getBody(), $this->getContentType());
    }

    /**
     * @return int Current Response HTTP Code
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): Response 
    {
        $this->code = $code;
        return $this;
    }   

    public function endJson(array $data): never
    {
        $this->setJson($data);
        $this->send();
    }

    public function endHtml(string $out): never
    {
        self::end($this->code, $out, Message::HTML);
    }

    public function endText(string $out): never
    {
        self::end($this->code, $out, Message::TEXT);
    }
}
