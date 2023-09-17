<?php 

namespace Md\Http;

use Md\Http\Exceptions\AppException;

use function curl_init;

class Client 
{
    public static function curl(string $url, array $postfields = []): string
    {
        $ch = curl_init($url);

        if(!$ch) {
            throw new AppException('HTTP Client Error');
        }

        try {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);   
            //curl_setopt($ch, CURLOPT_TIMEOUT, 5);         
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            if(!empty($postfields)) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            }
            
            $response = curl_exec($ch);
            
            if (curl_errno($ch) !== 0) {
                throw new AppException(curl_error($ch));
            }

            return $response;

        } catch (\Throwable $th) {
            throw $th;
        } finally {
            curl_close($ch);
        }
    }
}
