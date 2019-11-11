<?php

// index.php

// Normaly we want to include our composer vendor
require_once __DIR__ . '/../vendor/autoload.php';

// And include some dependencies
use GuzzleHttp\Client;


// Quick test
echo "Hello world";


// Actual composer example
$client = new Client([
		// Base URI is used with relative requests
		'base_uri' => 'php',
		// You can set any number of default request options.
		'timeout'  => 2.0,
]);
