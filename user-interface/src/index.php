<?php

use App\load\Loader;

require("load/Loader.php");

$loader = new Loader("http://localhost", 8080);

//$date = new DateTime("19951115");
echo "I'm still alive";

//echo $loader->get("/Members");
/*$loader->postMember("Robert",
    "Zondervan",
    "rjzondervan@gmail.com",
    "19951115",
    "rjzondervan",
    "adsfsadfsadf",
    array(1,2,3),
    array(2,3,4,5,6)
);*/

var_dump();
