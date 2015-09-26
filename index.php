<?php
use Bit0\Checklist\Controllers\HomeController;
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
], "/?[:controller]?/[:action]?/[**:trail]?", function ( Request $request, Response $response, ServiceProvider $service, App $app ) use ( $klein ) {
  $controller = str_replace( 'Home', ucfirst( $request->param('controller', 'home') ) , HomeController::class );
  $action     = $request->param('action', 'index');
  $trail      = explode('/', $request->param('trail', null));

  $body = call_user_func_array( [
    new $controller( $response, $app, $service ),
    $action
  ], $trail );

  $response->body( $body );
}
);

$klein->onHttpError( function ( $code, Klein $router ) {
  switch ( $code ) {
    case 404:
      $router->response()->body( 'Oops! These are not the droids you are looking for.' );
      $router->response()->dump( $router );
      break;
  }
} );

$klein->dispatch();

// TODO: Add DI
// TODO: object(className)?
// TODO: Add db connection (may fs based db)
// TODO: Add Angular