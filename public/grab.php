<?php namespace Kutkabouter;

set_time_limit(60 * 60); // 1 hour

require_once realpath(__DIR__ . '/../vendor/autoload.php');

$grabber = new DataGrabber(new Db());
// commented these lines to not accidentally import again (we're all tired)
//$grabber->grabEtenEnDrinken();
//$grabber->grabHotels();
$grabber->grabYelpReviews();

echo 'Done.'.PHP_EOL;