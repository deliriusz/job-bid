<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 08.11.2017
 * Time: 20:23
 */

// base controller
require_once('logger.php');
require_once('controller/event.php');
class Controller
{
    protected $doRender = true;
    protected $isPageLoginProtected = false;
    protected $f3, $db;

    protected function logData ($data) {
        Logger::log($data);
    }

    function beforeroute ($f3) {
        $this->f3 = $f3;
        $this->db = $f3->get('DB');
    }

    function afterroute($f3) {
        if ($this->isPageLoginProtected) {
            Login::handleUserShouldBeLogged($f3);
        }
        if ($this->doRender) {
            if ($f3->exists('SESSION.userid')) {
                $ec = new EventController($this->f3);

                $f3->set('SESSION.unreadNotificationCount', $ec->getUnreadNotificationCountForUser($f3->get('SESSION.userid')));
            }
            echo Template::instance()->render('template.html');
        }
    }
}
