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
  function afterroute() {
    echo Template::instance()->render('template.html');
  }
}
