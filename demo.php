<?php

/*
* This file is part of the DynDNS library.
*
* (c) Andreas Weber <weber@webmanufaktur-weber.de>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

use DynDNS\Updater;
use DynDNS\Provider\SchlundTech as SchlundTechProvider;


// vars
$host = 'home.example.de';
$ipv4 = '123.123.123.123';

$user = 'user';
$password = 'password';
$context = '10';

// get autoloader
require __DIR__ . '/vendor/autoload.php';

// instantiate updater
$updater = new Updater(
    new SchlundTechProvider($user, $password, $context)
);

// perform update
$updater->update($host, $ipv4);

// get response
$response = $updater->getResponse();
var_dump($response);
