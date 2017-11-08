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

        if (! (isset($_POST['inputUsername']) && isset($_POST['inputPassword'])&& isset($_POST['inputEmail'])) ) {
            die("Not passed all required params"); //TODO change handling in this case
        }
        $saltLength = random_int(170, 180);
        $saltForUser = bin2hex(random_bytes($saltLength));
        $pass = $_POST['inputPassword'];
        $user = $_POST['inputUsername'];
        $email = $_POST['inputEmail'];
        $calculatedPass = hash('sha256', $saltForUser . $pass);

        $f3->get ('DB')->exec ('INSERT INTO user (username, password, email, salt) values (?, ?, ?, ?)',
            array (
                $user,
                $calculatedPass,
                $email,
                $saltForUser
            ));

        $f3->reroute('/register/welcome');

    }

    function welcome ($f3) {
        $f3->set('content', 'start.html');
        echo Template::instance()->render('template.html');
    }
}