<?php 

namespace Md\Http;

use function filter_var, http_response_code, preg_match;

class Validator 
{
    public const ALPHANUM = "/^[A-Za-z0-9\.\-_@]*$/";
    public const URISAFE = "/^[A-Za-z0-9\.\-_\/]*$/";

    public static function badRequest(string $data): never
    {
        http_response_code(400);
        exit('Bad Request' . $data);
    }

    public static function validateRegex(string $data, string $reg): string
    {
        if(preg_match($reg, $data)) {
            return $data;
        }
        self::badRequest($data);
    }

    public static function safeUriString(string $data): string
    {
        $data = filter_var($data, FILTER_SANITIZE_URL);
        return self::validateRegex($data, self::URISAFE);
    }

    public static function safeEmail(string $email): string
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if($email === false) {
            self::badRequest($email);
        }

        return $email;
    }
}
