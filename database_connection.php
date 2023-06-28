<?php

require "vendor/autoload.php";

use Dotenv\Dotenv;

$path = dirname(__FILE__);

$dotenv = Dotenv::createImmutable($path);

$dotenv->load();

try {

    $databaseConnection = new mysqli($_ENV['hostName'], $_ENV['hostUser'], $_ENV['hostPass'], $_ENV['hostDatabase']);


} catch(Exception $e) {
    print $e;
}





?>