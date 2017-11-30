<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 21.11.2017
 * Time: 20:03
 */
require_once ('controller/event.php');
class Notifications extends Controller
{
    function view($f3) {
        Login::handleUserShouldBeLogged($f3);
        $userid = $f3->get('SESSION.userid');
        $f3->set('notifications', $this->getAllNotificationsForUser($userid));

        $f3->set('content', 'notifications.html');
    }

    //ajax request
    function setEventSubscriber ($f3) {
        $ec = new EventController($f3);
        $this->doRender = false;
        $ec->subscribeNewUser($f3->get('POST.notification-subscribe-id'), $f3->get('POST.notification-subscribe-type'), $f3->get('SESSION.userid'));

        $returnData = array();
        $returnData['success'] = true;

        echo json_encode ($returnData);
    }

    function getAllNotificationsForUser ($userid) {
        Login::handleUserShouldBeLogged($this->f3);
        $ec = new EventController($this->f3);
        return $ec->getNotificationsForUser($userid);
    }

    //ajax
    function deleteNotification ($f3) {
        Login::handleUserShouldBeLogged($f3);
        $ec = new EventController($f3);
        $this->doRender = false;
        $deleteAll = !$f3->exists('PARAMS.notificationid');

        if ($deleteAll) {
            $notificationsForUser = $ec->getNotificationsForUser($f3->get('SESSION.userid'));
            foreach ($notificationsForUser as $n) {
                if ($n->event_type != 5 && $n->is_read == 0) //job win event
                    $ec->deleteNotification($n->id);
            }
        } else {
            $ec->deleteNotification($f3->get('PARAMS.notificationid'));
        }

        $returnData = array();
        $returnData['success'] = true;

        echo json_encode ($returnData);
    }

    //ajax
    //TODO finish implementation
    function readNotification ($f3) {
        Login::handleUserShouldBeLogged($f3);
        $ec = new EventController($f3);
        $this->doRender = false;
        $readAll = !$f3->exists('PARAMS.notificationid');

        if ($readAll) {
            $notificationsForUser = $ec->getNotificationsForUser($f3->get('SESSION.userid'));
            foreach ($notificationsForUser as $n) {
                if ($n->event_type != 5) //job win event
                    $ec->readNotification($n->id);
            }
        } else {
            $ec->readNotification($f3->get('PARAMS.notificationid'));
        }

        $returnData = array();
        $returnData['success'] = true;

        echo json_encode ($returnData);
    }

}
