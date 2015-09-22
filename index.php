<?php
use Klein\App;
use Klein\Klein;
use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

require_once __DIR__ . '/vendor/autoload.php';

$klein = new Klein();

$klein->respond( [
  "GET",
  "POST"
], "/[:controller]?/?[:action]?/?[i:id]?", function ( Request $request, Response $response, ServiceProvider $service, App $app ) use ( $klein ) {
  $controller = "Bit0\\ToDo\\Controllers\\HomeController";
  $action     = "index";
  if ( isset( $request->controller ) ) {
    $controller = "Bit0\\ToDo\\Controllers\\" . ucfirst( $request->controller ) . "Controller";
  }
  if ( isset( $request->action ) ) {
    $action = $request->action;
  }

  $params = $request->params();

  $id = null;
  if (array_key_exists("id", $params))
    $id = $params['id'];

  $body = call_user_func_array( [
    new $controller( $response, $app, $service ),
    $action
  ], [ $id ] );

  $response->body($body);
}
);

$klein->onHttpError( function ( $code, $router ) {
  switch ( $code ) {
    case 404:
      /** @var \Klein\Klein $router */
      $router->response()->body( 'Oops! These are not the droids you are looking for.' );
      break;
  }
} );

$klein->dispatch();