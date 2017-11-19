<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 19.11.2017
 * Time: 11:55
 */

class Account extends Controller
{
    function view ($f3) {
        $userController = new Users();
        $userController->setF3($this->f3);
        $users = $userController->getUsers(array('username = ?', $f3->get('SESSION.username')), NULL);

        $f3->set('user', $users[0]);

        $f3->set('content', 'account.html');
    }
}