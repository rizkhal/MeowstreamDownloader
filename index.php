<?php

require "src/Meow.php";

use Meow\Meow;

$url    = "https://meowstream.com/nonton/kino-wana-sub-indo/";
$st     = new Meow($url);
$result = $st->exec();