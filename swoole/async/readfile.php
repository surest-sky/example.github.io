<?php

// swoole_async_readfile(__DIR__ . "/text.txt", function($filename, $content){
//   echo "$filename ----- $content";
// });

Swoole\Async::readFile(__DIR__ . "/text.txt", function($filename, $content){
  echo "$filename ----- $content";
});
