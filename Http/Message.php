<?php 

namespace Md\Http;

use function trim;

/**
 * Class Message
 * Represents a HTTP Message
 */
class Message
{
    /** Define a HTML message type */
    public const HTML = 'text/html';
    /** Define a JSON message type */
    public const JSON = 'application/json';
    /** Define a TEXT message type */
    public const TEXT = 'text/plain';

    public const URLENCODED = 'application/x-www-form-urlencoded';

    public const MULTIPART = 'multipart/form-data';

    /** @var string $contentType The current message content-type */
    private string $contentType;

    /** @var string $body The current message body */
    private string $body;

    /** @var string $data The current message body as array */
    // private array $data;

    /**
     * Initialize a new HTTP Message with empty content-type and empty body
     */
    public function __construct() 
    {
        $this->contentType = '';
        $this->body = '';
    }

    /**
     * Get the current message content-type
     * @return string The message content-type
     */
    public function getContentType(): string 
    {
        return $this->contentType;
    }

    /**
     * Set the current message content-type
     * @param string $_contentType The content-type to apply 
     * @return self
     */
    public function setContentType(string $_contentType): Message 
    {
        $this->contentType = trim($_contentType);
        return $this;
    }

    /**
     * Get the current message body
     * @return string The message body
     */
    public function getBody(): string 
    {
        return $this->body;
    }

    /**
     * Set the current message body
     * @param string $_body The message body
     * @return self
     */
    public function setBody(string $_body): Message 
    {
        $this->body = trim($_body);
        return $this;
    }

    /**
     * Set the current Message as JSON Document 
     * @param array $data the array to convert to json
     * @return self
     */
    public function setJson(array $data): Message 
    {
        $this->contentType = Message::JSON;
        $this->body = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        return $this;
    }
}
