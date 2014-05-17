<?php namespace Kutkabouter;

set_time_limit(60 * 60); // 1 hour

require_once realpath(__DIR__ . '/../vendor/autoload.php');

$grabber = new DataGrabber(new Db());
$grabber->grabEtenEnDrinken();
$grabber->grabHotels();

echo 'Done.'.PHP_EOL;