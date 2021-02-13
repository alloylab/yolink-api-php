<?php

namespace YoLink;

use \GuzzleHttp\Client as GuzzleClient;

class Client
{
    public static function post($url, $header, $post_data)
    {
        try {
            $client = new GuzzleClient($header);
            $response = $client->request('POST', $url, ['body' => $post_data]);
        
            if ($response->getStatusCode() < 300) {
                $result = $response->getBody()->getContents();
            } else {
                throw new \Exception(__FUNCTION__ . ': ' . $response->getStatusCode() . ' - invalid reponse from api');
            }
        } catch (Exception $e) {
            throw new \Exception(__FUNCTION__ . $e);
        }

        return $result;
    }

    public static function get($url, $header)
    {
        try {
            $client = new GuzzleClient($header);
            $response = $client->request('GET', $url);

            if ($response->getStatusCode() < 300) {
                $result = $response->getBody()->getContents();
            } else {
                throw new \Exception(__FUNCTION__ . ': ' . $response->getStatusCode() . ' - invalid reponse from api');
            }
        } catch (Exception $e) {
            throw new \Exception(__FUNCTION__ . $e);
        }

        return $result;
    }
}
