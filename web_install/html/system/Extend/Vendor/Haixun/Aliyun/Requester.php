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


    static private function request($uri,$method='GET',$data=null,$timeout=null)
    {

        $options = [
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $timeout ?: self::TIMEOUT,
        ];

        if (strtoupper($method) == 'POST') {

            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = http_build_query($data);

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