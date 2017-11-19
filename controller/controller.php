<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 08.11.2017
 * Time: 20:23
 */

// base controller
require('logger.php');
class Controller
{
  protected $doRender = true;
  protected $f3, $db;

  protected function logData ($data) {
      Logger::log($data);
  }

  function beforeroute ($f3) {
    $this->f3 = $f3;
    $this->db = $f3->get('DB');
  }

  function afterroute() {
    if ($this->doRender) {
      echo Template::instance()->render('template.html');
    }
  }
}
