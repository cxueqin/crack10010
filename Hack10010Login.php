<?php

class Hack10010Login
{

    static function checkPass($mobile, $pass)
    {
        $privateKey = hex2bin("f6b0d3f905bf02939b4f6d29f257c2ab");
        $iv = hex2bin("1a42eb4565be8628a807403d67dce78d");


        $randomStr = substr(time(), -6, 6);

        $mobile = self::genMobile($mobile, $randomStr);
        $password = self::genPassword($pass, $randomStr);


        $encryptedMobile = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $privateKey, hex2bin($mobile), MCRYPT_MODE_CBC, $iv);
        $encryptedMobile = (bin2hex($encryptedMobile));


        $encryptedPassword = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $privateKey, hex2bin($password), MCRYPT_MODE_CBC, $iv);
        $encryptedPassword = (bin2hex($encryptedPassword));

//        $client = new Client();

//
//        $result = $client->post('http://m.client.10010.com//mobileService/login.htm',
//            ['form_params' =>
//                [
//                    'mobile' => $encryptedMobile,
//                    'password' => $encryptedPassword,
//                    'deviceId' => md5(uniqid()) . md5(uniqid()),
//                    'deviceCode' => strtoupper(self::makeGuid()),
//                    'keyVersion' => '',
//                    'netWay' => 'Over',
//                    'deviceOS' => '9.3.1',
//                    'version' => 'iphone_c@4.2',
//                    'isRemberPwd' => 'true',
//                    'deviceBrand' => 'iphone',
//                    'deviceModel' => 'iPhone'
//                ],
//                'proxy' => '192.168.16.112:8888',
//                'headers' => ['Cookie' => 'gipgeo=38|380; mallcity=38|380;']])->getBody()->getContents();

        $result = self::post('http://m.client.10010.com//mobileService/login.htm',
            [
                'mobile' => $encryptedMobile,
                'password' => $encryptedPassword,
                'deviceId' => md5(uniqid()) . md5(uniqid()),
                'deviceCode' => strtoupper(self::makeGuid()),
                'keyVersion' => '',
                'netWay' => 'Over',
                'deviceOS' => '9.3.1',
                'version' => 'iphone_c@4.2',
                'isRemberPwd' => 'true',
                'deviceBrand' => 'iphone',
                'deviceModel' => 'iPhone'
            ]
            , ['cookie:gipgeo=38|380; mallcity=38|380;']
        );
        echo $result . PHP_EOL;
        $json = json_decode($result, true);
        return $json;

    }

    static function post($url, $post, $header)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        print_r($output);
    }

    static protected function makeGuid()
    {
        if (function_exists('com_create_guid') === true) {
            return strtolower(trim(com_create_guid(), '{}'));
        }

        return strtolower(sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        ));
    }

    static function genMobile($mobile, $randomStr)
    {
        $mobile = strval($mobile) . strval($randomStr);
        $result = '';

        for ($i = 0; $i < strlen($mobile); $i++) {
            $result .= '3' . substr($mobile, $i, 1); //将单个字符存到数组当中
        }
        return str_pad($result, 64, '0f');
    }


    static function genPassword($password, $randomStr)
    {
        $password = strval($password) . strval($randomStr);
        $result = '';
        for ($i = 0; $i < strlen($password); $i++) {
            $result .= '3' . substr($password, $i, 1); //将单个字符存到数组当中
        }
        return str_pad($result, 32, '04');
    }

}
