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
        $f3->set('content', 'account.html');
    }
}