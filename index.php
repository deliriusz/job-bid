<?php

    //TODO expire session
    //TODO check if user is logged in to perform operation

    $f3=require('lib/base.php');

    $f3->config('config.ini');
    $f3->config('../config.ini');
    $f3->config('routes.ini');

    $f3->set('DB', new DB\SQL($f3->get('dburl'),
		$f3->get('dbuser'),
		$f3->get('dbpassword')) );

    //new DB\SQL\Session($f3->get('DB'));

    $f3->route ('GET /',
        function ($f3) {
					// 'order'=>'userID DESC',
// 'offset'=>5,
// 'limit'=>3
$jobsController = new Jobs ();
$jobs = $jobsController->getJobs (
	NULL, array (
		'order' => 'initial_price DESC',
		'limit' => 5
		)
);
$f3->set('jobs', $jobs);
            $f3->set('content', 'start.html');
            echo Template::instance()->render('template.html');
        }
    );

$f3->run();
