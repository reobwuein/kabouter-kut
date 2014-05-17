<?php

use Kutkabouter\Db;

require_once realpath(__DIR__ . '/../vendor/autoload.php');

$app = new \Slim\Slim();
$app->get('/review/:long/:lat', function($long, $lat) {
    $db = new Db();
    $stmt = $db->prepare("select review1, review2, review3 from reviews where longtitude = ? and latitude = ?");
    $stmt->bind_param('dd', $long, $lat);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    $reviews = array_filter(array($row['review1'], $row['review2'], $row['review3']));
    
    return json_encode($reviews);
});
$app->run();