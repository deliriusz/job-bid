<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 07.11.2017
 * Time: 22:20
 */

class Register
{
    function view ($f3) {
        $f3->set('content', 'register.html');
        echo Template::instance()->render('template.html');
    }

    function registerNewUser ($f3) {
        echo 'posting new user';
    }
}