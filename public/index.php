<?php

use Kutkabouter\Db;

require_once realpath(__DIR__ . '/../vendor/autoload.php');

$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');

$app->get('/review/:long/:lat', function($long, $lat) use ($app) {
    $db = new Db();
    $stmt = $db->prepare("select review1, review2, review3 from reviews where longtitude = ? and latitude = ?");
    $stmt->bind_param('dd', $long, $lat);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    $reviews = array_filter($row);
    $review = $reviews[array_rand($reviews)];

    $app->response->write(json_encode($review));
});
$app->run();