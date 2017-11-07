<?php

// Kickstart the framework
$f3=require('lib/base.php');
require('controller/login.php');
require('controller/register.php');

$f3->set('DEBUG',3);
    if ((float)PCRE_VERSION<7.9)
        trigger_error('PCRE version is out of date');

    $f3->config('config.ini');

    $f3->set('AUTOLOAD', 'controller/'); // new dirs after ; sign

    $f3->route ('GET @login: /login', 'Controller\Login->view');
    $f3->route ('GET @register: /register', 'Controller\Register->view');

    $f3->route ('GET / /index.php',
        function ($f3) {

            $f3->set('content', 'start.html');

            echo View::instance()->render('template.html');
        }
    );

    $f3->route ('GET /about', function () {
       echo 'This is about page';
    });

$f3->run();
