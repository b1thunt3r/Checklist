<?php
require_once __DIR__ . '/vendor/autoload.php';

$klein = new \Klein\Klein();

//$klein->respond("GET", "/swati", function () {
//  return "Hello Swati!";
//});

$klein->respond('GET', '/[:controller]?/?[:action]?/?[i:id]?', function($request, $response, $service, $app) use($klein) {
  var_dump($request);
});

$klein->onHttpError(function($code, $router) {
  switch ($code) {
    case 404:
      $router->response()->body('U lost lad?');
      break;
  }
});

$klein->dispatch();