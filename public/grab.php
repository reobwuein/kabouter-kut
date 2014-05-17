<?php namespace Kutkabouter;

require_once realpath(__DIR__ . '/../vendor/autoload.php');

$grabber = new DataGrabber(new Db());
//$grabber->grabEtenEnDrinken();

echo 'Done.';