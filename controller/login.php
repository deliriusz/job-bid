<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 07.11.2017
 * Time: 19:46
 */

class Login
{
    function view ($f3) {
        $f3->set('content', 'login.html');
        echo Template::instance()->render('template.html');
    }
}