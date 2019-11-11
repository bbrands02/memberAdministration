<?php

use App\load\Loader;

require("load/Loader.php");
echo "<pre>";
$loader = new Loader("http://localhost", 8080);

//$date = new DateTime("19951115");
echo "I'm still alive\n";

//$getTest = $loader->get("/Members");
//echo $loader->get("/Members");
//$loader->postMember("/Members/1", "Robert",
//    "Zondervan",
//    "rjzondervan@gmail.com",
//    "19951115",
//    "rjzondervan",
//    "adsfsadfsadf",
//    array(1,2,3),
//    array(2,3,4,5,6),
//);
$loader->patchMember("/Members/1", 1,null,
    null,
    "Robert@conduction.nl",
    null,
    null,
    null,
    null,
    null
);
//var_dump($getTest);

