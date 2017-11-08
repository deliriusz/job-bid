<?php

// Kickstart the framework
    $f3=require('lib/base.php');

    $f3->config('config.ini');
    $f3->config('routes.ini');

    $f3->set('DB', new DB\SQL('mysql:host=localhost;port=3306;dbname=pai',
    'root'));


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
