<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 23.11.2017
 * Time: 16:07
 */

class SimplePageController extends Controller
{
    function viewAbout ($f3) {
        $f3->set('content', 'about.html');
    }

    function viewHowItWorks ($f3) {
        $f3->set('content', 'howitworks.html');
    }

}