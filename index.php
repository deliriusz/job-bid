<?php

// Kickstart the framework
$f3=require('lib/base.php');

$f3->set('DEBUG',1);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');

// Load configuration
$f3->config('config.ini');

    $f3->route ('GET /',
        function () {
            echo View::instance()->render('index_template.html');
        }
    );

    $f3->route ('GET /about', function () {
       echo 'This is about page';
    });

$f3->run();
