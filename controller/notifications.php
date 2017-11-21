<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 21.11.2017
 * Time: 20:03
 */

class Notifications extends Controller
{
    function view($f3) {
        Login::handleUserShouldBeLogged($f3);
        $userid = $f3->get('SESSION.userid');
        $f3->set('notifications', $this->getAllNotificationsForUser($userid));

        $f3->set('content', 'notifications.html');
    }

    function getAllNotificationsForUser ($userid) {
        return $this->getNotifications(array('user_id = ?', $userid), array('ORDER BY event_id DESC'));
    }

    private function getNotifications ($constrainsArr = NULL, $paginationSettings = NULL) {
        $notificationsMapper = new DB\SQL\Mapper($this->db, 'event_observer');
        $notificationsMapper->load($constrainsArr, $paginationSettings);
        $notificationsArray = array();
        for ($i =  0; $i < $notificationsMapper->loaded(); $i++) {
            $notificationsArray[$i] = $this->eventMapperToNotifications($notificationsMapper);
            $notificationsMapper->next();
        }
        return $notificationsArray;
    }

    private function eventMapperToNotifications ($m) {
        $eventMapper = new DB\SQL\Mapper($this->db, 'event');
        $eventMapper->load(array('id = ?', $m->event_id));

        $n = new Notification(
            $m->id,
            $eventMapper->type,
            'test name', //TODO change to something useful
            $m->is_read
        );
        return $n;
    }
}
class Notification {
    public $id,
    $type,
    $name,
    $is_read;

    /**
     * Notification constructor.
     * @param $id
     * @param $type
     * @param $name
     */
    public function __construct($id, $type, $name, $is_read)
    {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->is_read = $is_read;
    }
}