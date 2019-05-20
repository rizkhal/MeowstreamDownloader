<?php

require "src/Meow.php";

use Meow\Meow;

$url    = "https://meowstream.com/nonton/gesenki-sub-indo/";
$st     = new Meow($url);
$result = $st->exec();

var_dump($result);