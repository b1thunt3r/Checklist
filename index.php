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
], "/?[:controller]?/[**:trail]?", function ( Request $request, Response $response, ServiceProvider $service, App $app ) use ( $klein ) {
  $controller = ucfirst( strtolower( $request->param( 'controller', 'home' ) ) );
  $trailRaw   = $request->param( 'trail', [ ] );
  $trail      = explode( '/', $trailRaw );

  if ( $controller == "Static" ) {
    $name = end( $trail );
    $ext  = end( explode( ".", $name ) );

    $mime = "text/plain";
    switch ( $ext ) {
      case "css":
        $mime = "text/css";
        break;
      case "js":
        $mime = "text/javascript";
        break;
      case "jpg":
        $mime = "image/jpeg";
        break;
      case "png":
        $mime = "image/png";
        break;
    }

    $response->file( realpath( "./src/Checklist/Views/Static/" . $trailRaw ), $name, $mime );

    return;
  }

  $controller = str_replace( 'Home', ucfirst( strtolower( $request->param( 'controller', 'home' ) ) ), HomeController::class );

  if ( count( $trail ) > 0 ) {
    $action = array_shift( $trail );
  } else {
    $action = "index";
  }

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