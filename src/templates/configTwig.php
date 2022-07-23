<?php

include_once('../vendor/autoload.php');

$allow_cache = false;
if ($allow_cache == true) {
  $cache = ['cache' => __DIR__ . '/tmp'];
} else {
  $cache = [];
}

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);
$twig = new \Twig\Environment($loader, $cache);
$template = $twig->load('base.html.twig');

?>