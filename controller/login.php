<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 07.11.2017
 * Time: 19:46
 */

namespace Controller;


//$f3=require('lib/base.php');

use View;

class Login
{
    function view () {
        echo  View::instance()->render('template.html');

    }
}