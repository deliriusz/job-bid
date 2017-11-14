<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 08.11.2017
 * Time: 20:23
 */

// base controller
class Controller
{
  protected $doRender = true;

  function afterroute() {
    if ($this->doRender) {
      echo Template::instance()->render('template.html');
    }
  }
}
