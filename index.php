<?php

// Kickstart the framework
$f3=require('lib/base.php');
require('controller/login.php');
require('controller/register.php');

    $f3->config('config.ini');

    $f3->route ('GET @register: /register',
        function ($f3) {
            $f3->set('content', 'register.html');
            echo Template::instance()->render('template.html');
        }
    );


    $f3->route ('GET @login: /login',
        function ($f3) {
            $f3->set('content', 'login.html');
            echo Template::instance()->render('template.html');
        }
    );

    $f3->route ('GET /',
        function ($f3) {
            $f3->set('content', 'start.html');
            echo Template::instance()->render('template.html');
        }
    );

    $f3->route ('GET /about', function ($f3) {
        $f3->set('content', 'about.html');
        echo Template::instance()->render('template.html');
    });

$f3->run();
