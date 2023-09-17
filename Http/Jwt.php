<?php 

namespace Md\Http;


class Jwt 
{
    private static function encodeJwtData(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function splitJwt($jwt): array 
    {
        $parts = explode('.', $jwt);
        $header = base64_decode($parts[0] ?? '');
        $payload = base64_decode($parts[1] ?? '');
        $signature = $parts[2] ?? null;
        return [$header, $payload, $signature];
    }

    public static function getJwtPayload($jwt): array
    {
        return json_decode(self::splitJwt($jwt)[1] ?? ['username' => 'Anonymous'], true);
    }

    private static function getAuthorizationToken(): ?string
    {
        $headers = null;
        
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } else if (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        
        return $headers;
    }
    
    public static function getBearerToken(): ?string
    {
        $headers = self::getAuthorizationToken();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public static function getBearerTokenData(): array
    {
        $t = self::getBearerToken() ?? $_GET['token'] ?? null;

        if(!empty($t)) {
            $jwt = new Jwt();
            if($jwt->isValid($t)) {
                return self::getJwtPayload($t);
            }
        }
        //Response::end(401, json_encode(['error' => '401 Unauthorized !'], JSON_PRETTY_PRINT), Message::JSON);
        Response::end(401, json_encode(['error' => '404 Not Found !'], JSON_PRETTY_PRINT), Message::JSON);
    }


    private string $secret;

    private array $headers;  


    public function __construct(?string $secret = 'secret') 
    {
        $this->headers = array('alg'=>'HS256','typ'=>'JWT');
        $this->secret = $secret;
    }

    private function encodeSignature(string $headers_encoded, string $payload_encoded): string
    {
        return self::encodeJwtData(hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $this->secret, true));
    }

    public function generate(array $payload): string
    {
        $payload['exp'] = ($_SERVER['REQUEST_TIME'] + 60);

        $h = self::encodeJwtData(json_encode($this->headers));
        $p = self::encodeJwtData(json_encode($payload));
        $s = $this->encodeSignature($h, $p);

        return ("$h.$p.$s");
    }   

    public function isValid(string $jwt): bool
    {
        $tokenParts = self::splitJwt($jwt);
        $header = $tokenParts[0];
        $payload = $tokenParts[1];
        $signature = $tokenParts[2];

        if(!$header || !$payload || !$signature) { return false; }
    
        // check expiration time
        $exp = json_decode($payload, true)['exp'] ?? 0;
        $exp = ($exp - time());
        
        if($exp < 0) { return false; }
    
        // build signature based on provided header and payload
        $headers_h = self::encodeJwtData($header);
        $payload_h = self::encodeJwtData($payload);
        $signature_h = $this->encodeSignature($headers_h, $payload_h);
     
        // verify it matches the signature provided in the jwt
        return ($signature_h === $signature);
    }    
}
