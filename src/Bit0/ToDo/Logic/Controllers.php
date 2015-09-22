<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2015-09-22
 * Time: 22:52
 */

namespace Bit0\ToDo\Logic {


  use Klein\App;
  use Klein\Response;
  use Klein\ServiceProvider;
  use Twig_Environment;
  use Twig_Loader_Filesystem;

  /**
   * Class Controllers
   * @package Bit0\ToDo\Logic
   *
   * @property Response $Response
   * @property App $App
   * @property ServiceProvider $Service
   */

  class Controllers {
    var $Response;
    var $App;
    var $Service;
    var $ViewPath;

    public function __construct( Response $response, App $app, ServiceProvider $service, $viewPath ) {
      $this->Response = $response;
      $this->App      = $app;
      $this->Service  = $app;
      $this->ViewPath = $viewPath;
    }

    public function view( $view, array $model = [] ) {
      if ( strpos( $view, "/" ) === false ) {
        preg_match(
          '/Bit0\/ToDo\/Controllers\/(?<controller>\w+)Controller$/',
          str_replace( "\\", "/", get_class( $this ) ),
          $matches );
        $view = $matches['controller']."/".$view.".twig";
      }

      /** @var \Klein\App $app */
      $twig = new Twig_Environment( new Twig_Loader_Filesystem( $this->ViewPath ) );

      $template = $twig->loadTemplate($view);
      return $template->render($model);
    }
  }


}

