<?php

class Requester
{
    const TIMEOUT = 60;

    static public function get($url,$data=null,$timeout=null)
    {
        return self::request($url,'GET',$data,$timeout);
    }


    static public function post($url,$data=null,$timeout=null)
    {
        return self::request($url,'POST',$data,$timeout);
    }

    static public function json($url,$data=null,$timeout=null)
    {
        return self::request($url,'JSON',$data,$timeout);
    }


    static private function request($uri,$method='GET',$data=null,$timeout=null)
    {

        $options = [
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $timeout ?: self::TIMEOUT,
            CURLOPT_HTTPHEADER => array(),
        ];

        if (parse_url($uri, PHP_URL_SCHEME) == 'https') {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }

        if (strtoupper($method) == 'POST') {

            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = http_build_query($data);

        }elseif(strtoupper($method) == 'JSON'){
            $options[CURLOPT_POST] = 1;
            if (!empty($data)) {
                $options[CURLOPT_POSTFIELDS] = is_array($data) ? json_encode($data) : $data;
            }
            array_push($options[CURLOPT_HTTPHEADER],
                'Content-Type: application/json; charset=utf-8',
                'Content-Length:' . strlen($options[CURLOPT_POSTFIELDS])
            );

        }else if(strtoupper($method) == 'GET' && !is_null($data)) {
            $uri = self::parse_data_get_uri($uri,$data);
        }

        $ch = curl_init($uri);
        curl_setopt_array($ch,$options);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;

    }
    

    static private function parse_data_get_uri($uri,$data)
    {

        $param = is_array($data) ? http_build_query($data) : $data;

        if (strpos($uri, '?') === false) {
            return $uri.'?'.$param;
        } else {
            return $uri.'&'.$param;
        }

    }



}