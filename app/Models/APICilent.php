<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class APICilent extends Model
{
    // APIをJSON形式で返す
    public static function APIgetJSON($url, $method, $option=null)
    {
        $client = new Client();
        ($method != 'POST')?
            $response = $client->request($method, $url):
            $response = $client->request($method, $url, $option);

        return json_decode($response->getBody(), true);
    }
}
