<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2015-09-22
 * Time: 22:16
 */

namespace Bit0\ToDo\Controllers {
  use Bit0\ToDo\Logic\Controllers;


  /**
   * Class HomeController
   * @package Bit0\ToDo\Controllers
   */
  class HomeController extends Controllers {
    public function index($id = null) {
      $model = [
        'Id' => $id
      ];

      return $this->view("index", $model);
    }
  }
}
