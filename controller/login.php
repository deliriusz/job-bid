<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 07.11.2017
 * Time: 19:46
 */

class Login
{
    private $f3;

    function lostPassword ($f3) {
        $this->f3 = $f3;
        $f3->set('content', 'lostpassword.html');
        echo Template::instance()->render('template.html');
    }

    function view ($f3) {
        $this->f3 = $f3;
        $f3->set('content', 'login.html');
        echo Template::instance()->render('template.html');
    }

    function handleLogin ($f3) {
        $this->f3 = $f3;
        if (! (isset($_POST['inputUsername']) && isset($_POST['inputPassword'])) ) {
            die ('Not passed all login parameters'); //TODO change this to some more meaningful error handling
        }
        $user = $this->getUserData($_POST['inputUsername']);
        $calculatedPass = hash('sha256', $user['salt'] . $_POST['inputPassword']);

        if ($user['username'] === $_POST['inputUsername'] &&
            $user['password'] === $calculatedPass) {

            $f3->set('SESSION.username', $user['username']);

            $f3->reroute('/register/welcome');
        } else {
            echo 'username not matched!!';
        }
    }

    function handleLogout ($f3)  {
        $f3->clear('SESSION');
        $f3->reroute('/');
    }

    private function getUserData ($username) {
        $userMapper = new DB\SQL\Mapper($this->f3->get('DB'), 'user');
        return $userMapper->load(array('username=?', $username));
    }
}
