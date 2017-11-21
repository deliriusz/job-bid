<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 21.11.2017
 * Time: 20:03
 */

class Notifications extends Controller
{
    function view($f3) {
        Login::handleUserShouldBeLogged($f3);

        $f3->set('content', 'notifications.html');
    }


}