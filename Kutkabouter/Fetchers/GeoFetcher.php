<?php namespace Kutkabouter\Fetchers; 

class GeoFetcher
{
    /**
     * @param $postcode
     * @return array
     */
    public function fetch($postcode)
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json';
        $address = urlencode($postcode.' Nederland');
        $params = '?address='.$address.'&sensor=false&key=AIzaSyD4ApvWH4r64uFU9Xj7utuM1zNQJeFys4w';

        $json = file_get_contents($url.$params);
        $result = json_decode($json);

        $geo = $result->results[0]->geometry->location;

        return array('latitude' => $geo->lat, 'longtitude' => $geo->lng);
    }
}