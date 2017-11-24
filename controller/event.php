<?php

class EventController {
    private $f3, $db;

    public function __construct ($f3){
        $this->f3 = $f3;
        $this->db = $f3->get('DB');
    }

    public function createNewEvent ($source_id, $source_type) {
        $eventMapper = new DB\SQL\Mapper($this->db, 'event');
        $eventMapper->reset();
        $eventMapper->source_id = $source_id;
        $eventMapper->type = $source_type;
        $eventMapper->save();
    }

    public function subscribeNewUser ($source_id, $source_type, $user_id) {
        $eventMapper = new DB\SQL\Mapper($this->db, 'event');
        $eventSubscriberMapper = new DB\SQL\Mapper($this->db, 'event_subscriber');
        $eventMapper->load (array('source_id = ? AND type = ?', $source_id, $source_type));

        if ($this->isUserSubscribedToEvent($user_id, $source_id, $source_type)) {
            $eventSubscriberMapper->erase(
                array ('event_id = ? and user_id = ?', $eventMapper->id, $user_id)
            );
        } else {
            $eventSubscriberMapper->reset();
            $eventSubscriberMapper->user_id = $user_id;
            $eventSubscriberMapper->event_id = $eventMapper->id;

            $eventSubscriberMapper->save();
        }
    }

    public function fireEvent ($source_id, $source_type, $event_type) {
        $eventMapper = new DB\SQL\Mapper($this->db, 'event');
        $notificationMapper = new DB\SQL\Mapper($this->db, 'notification');
        $eventSubscriberMapper = new DB\SQL\Mapper($this->db, 'event_subscriber');
        $eventMapper->load (array('source_id = ? AND type = ?', $source_id, $source_type));

        $eventSubscriberMapper->load(array('event_id = ?', $eventMapper->id));

        for ($i =  0; $i < $eventSubscriberMapper->loaded(); $i++) {
            $notificationMapper->reset();
            $notificationMapper->user_id = $eventSubscriberMapper->user_id;
            $notificationMapper->event_id = $eventSubscriberMapper->event_id;
            $notificationMapper->event_type = $event_type;
            $notificationMapper->is_read = 0;
            $notificationMapper->save();

            $eventSubscriberMapper->next();
        }
    }

    public function getNotificationCountForUser($user_id)
    {
        $notificationMapper = new DB\SQL\Mapper($this->db, 'notification');
        return $notificationMapper->count(array('user_id = ?', $user_id));

    }

    public function getUnreadNotificationCountForUser($user_id)
    {
        $notificationMapper = new DB\SQL\Mapper($this->db, 'notification');
        return $notificationMapper->count(array('user_id = ? AND is_read = 0', $user_id));

    }

    public function getNotificationsForUser($user_id) {
        $notificationMapper = new DB\SQL\Mapper($this->db, 'notification');
        $jobsMapper = new DB\SQL\Mapper($this->db, 'job');
        $bidsMapper = new DB\SQL\Mapper($this->db, 'bid');
        $notificationMapper->load (array('user_id = ?', $user_id));
        $eventMapper = new DB\SQL\Mapper($this->db, 'event');
        $notifications = array();

        for ($i =  0; $i < $notificationMapper->loaded(); $i++) {
            $n = new Notification();
            $n->id = $notificationMapper->id;
            $n->user_id = $notificationMapper->user_id;
            $n->event_id = $notificationMapper->event_id;
            $n->event_type = $notificationMapper->event_type;
            $n->is_read = $notificationMapper->is_read;

            $eventMapper->load(array('id = ?', $notificationMapper->event_id));
            $sourceOfEventMapper = new DB\SQL\Mapper($this->db, $eventMapper->type);
            $sourceOfEvent = NULL;

            $sourceOfEventMapper->load(array('id = ?', $eventMapper->source_id));

            $message = '';
            $postmessage = '';
            switch ($notificationMapper->event_type) {
                case 1: //job created
                    $message = sprintf('User %s created job %s', $sourceOfEventMapper->userid, $sourceOfEventMapper->name);
                    break;

                case 2: //job updated
                    $message = sprintf('User %s updated job %s', $sourceOfEventMapper->userid, $sourceOfEventMapper->name);
                    break;

                case 3: //job finished
                    $jobName = $sourceOfEventMapper->name;
                    $n->url = sprintf('/PAI-proj/job/%d', $sourceOfEventMapper->id);
                    $message = 'Job ';
                    $postmessage = ' finished';
                    $n->name = $jobName;
                    break;

                case 4: //lower bid
                    $jobName = $sourceOfEventMapper->name;
                    $message = 'New bid id in job ';
                    $n->url = sprintf('/PAI-proj/job/%d', $sourceOfEventMapper->id);
                    $n->name = $jobName;
                    break;

                default:
                    //do nothing
            }

            $n->premessage = $message;
            $n->postmessage = $postmessage;

            array_push($notifications, $n);

            $notificationMapper->next();
        }

        return $notifications;
    }

    public function deleteNotification($id) {
        $notificationMapper = new DB\SQL\Mapper($this->db, 'notification');
        $notificationMapper->erase(array('id = ?', $id));
    }

    public function isUserSubscribedToEvent ($userid, $eventid, $eventType) {
        $eventMapper = new DB\SQL\Mapper($this->db, 'event');
        $eventMapper->load(
                array('source_id = ? AND type = ?', $eventid, $eventType)
            );
        $eventSubscriberMapper = new DB\SQL\Mapper($this->db, 'event_subscriber');
        $subscriptionCount = $eventSubscriberMapper->count(
            array('event_id = ? AND user_id = ?',
                $eventMapper->id,
                $userid)
        );

        return $subscriptionCount > 0;
    }

}

class Notification {
    public $id, $user_id, $event_id, $event_type, $is_read, $premessage, $postmessage, $name, $url;

    public function __construct (){}
}
