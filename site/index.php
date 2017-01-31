<?php

$start = time();

require '../vendor/autoload.php';

$config = require '../config.php';
$helper = new \Sta\Cld2Php\IndexHelper();

register_shutdown_function(
    function () use ($start, $config, $helper) {
        $helper->trackTimeOnAnalytics($config, time() - $start);
    }
);

$helper->trackRequestOnAnalytics($config);

if (strpos($helper->getRequestUri(), $helper->getBasePath('/detect')) === 0) {
    require 'detect.php';
} else {
    require 'site.php';
}
