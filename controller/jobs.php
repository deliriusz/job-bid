<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 07.11.2017
 * Time: 19:46
 */

require_once ('controller/bids.php');
require_once ('controller/utils.php');
require_once ('controller/event.php');
class Jobs extends Controller
{
    function viewJobsPage ($f3) {
        $isPost = count($f3->get('POST')) > 0;

        Login::handleUserShouldBeLogged($f3);
        $username = $f3->get('PARAMS.username');
        $pageToDisplay = $f3->get('GET.p') != NULL ? $f3->get('GET.p') : 1;
        $itemsPerPage = $f3->get('itemsperpage');

        $constrainsArray = NULL;
        if ($f3->exists('PARAMS.username')) {
            $constrainsArray = array('userid = ?', $this->getUserIdFromUsername($username));
        } else {
            $constrainsArray = array ('finished = FALSE');
        }

        //TODO add job type
        if ($isPost) {
            var_dump ($f3->get('POST'));
            if (strlen($f3->get('POST.price_from') > 0)) {
                $constrainsArray[0] = $constrainsArray[0] + " AND value > ?";
                array_push($constrainsArray, $f3->get('POST.price_from') * 1);
            }
        }

        $f3->set('jobs', $this->getJobs($constrainsArray, array(
            'offset' => ($pageToDisplay - 1) * $itemsPerPage,
            'limit' => $itemsPerPage
        )));

        $maxPage = ceil($this->countJobs($constrainsArray) / $itemsPerPage);
        if ($maxPage < 1) {
            $maxPage = 1;
        }

        $f3->set('pageNeighbours', Utils::getPageNeighbours($pageToDisplay, $maxPage));
        $f3->set('maxPage', $maxPage);


        if ($isPost) {
            $this->doRender = false;
            $returnData = array ();
            $returnData['success'] = true;
            $returnData['response'] = Template::instance()->render('jobs-panel.html');

            echo json_encode($returnData);
        } else {
            $this->doRender = true;
            $f3->set('content', 'jobs.html');
        }
    }

    function viewSpecificJobForUser($f3) {
        $this->viewSpecificJob($f3);
    }

    function viewSpecificJob ($f3) {
        $jobid = $f3->get('PARAMS.jobid');
        $job = $this->getJobs(array ('id = ?', $jobid), NULL);
        if (count($job)) {
            $f3->set ('job', $job[0]);
        }
        $content = 'job.html';
        $ec = new EventController($f3);

        $f3->set('isUserSubscribedToJobEvents',
            $ec->isUserSubscribedToEvent(
                $f3->get('SESSION.userid'),
                $jobid,
                'job'
            )
        );

        $f3->set('content', $content);
    }

    //ajax request
    function selectWinner ($f3) {
        //TODO add check of logged in and permissions and if job is finished
        $winner_id = $f3->get('PARAMS.userid');
        $job_id =  $f3->get('PARAMS.jobid');

        $ec = new EventController($f3);
        $ec->fireEvent($job_id, 'job', 5,
            array(
                'notify_users' => array($winner_id)
            ));
    }

    private function handleJobWonDecision ($f3, $isAccepted) {
        //TODO add check of logged in and permissions and if job is finished
        Login::handleUserShouldBeLogged($f3);
        $winner_id = $f3->get('PARAMS.userid');
        $job_id =  $f3->get('PARAMS.jobid');
        $jobMapper = new DB\SQL\Mapper($this->db, 'job');
        $jobMapper->load(array('id = ?', $job_id));
        $this->doRender = false;

        $event_type = $isAccepted ? 6 : 7;

        $ec = new EventController($f3);
        $ec->fireEvent($job_id, 'job', $event_type,
            array(
                'notify_users' => array($winner_id, $jobMapper->userid)
            ));

        //TODO implement
        //$ec->readNotification();

        $returnData = array();
        $returnData['success'] = true;

        echo json_encode($returnData);
    }

    function declineJob ($f3) {
        $this->handleJobWonDecision($f3, false);
    }

    function confirmJob ($f3) {
        $this->handleJobWonDecision($f3, true);
    }

    //ajax request
    function postNewJob ($f3) {
        Login::handleUserShouldBeLogged($f3);
        $this->doRender = false;

        $jobsMapper = new DB\SQL\Mapper($this->db, 'job');
        $currentDate = Utils::getCurrentDateTime();
        $jobName = $_POST['jobName'];
        $jobStartDate = $_POST['jobStartDate'];
        $jobEndDate = $_POST['jobEndDate'];
        $jobInitialPayment = $_POST['jobInitialPayment'];
        $jobDescription = $_POST['jobDescription'];
        $errors = array();
        $returnData = array();

        if (!Utils::validateDate($jobStartDate)) {
            array_push($errors, 'start date passed is not valid date');
        }

        if (!Utils::validateDate($jobEndDate)) {
            array_push($errors, 'end date passed is not valid date');
        }

        if ($jobInitialPayment < 1) {
            array_push($errors, 'initial payment cannot be lower than 1');
        }

        if (strlen($jobName) < 4) {
            array_push($errors, 'job name is too short');
        }

        if (empty($errors)) {
            $returnData['success'] = true;
            $jobsMapper->userid = $f3->get('SESSION.userid');
            $jobsMapper->name = $jobName;
            $jobsMapper->description = $jobDescription;
            $jobsMapper->initial_price = $jobInitialPayment;
            $jobsMapper->creation_time = $currentDate;
            $jobsMapper->job_start_time = $jobStartDate;
            $jobsMapper->job_end_time = $jobEndDate;

            $jobsMapper->save();

            $ec = new EventController($f3);
            $ec->createNewEvent($jobsMapper->id, 'job');
            $ec->subscribeNewUser($jobsMapper->id, 'job', $f3->get('SESSION.userid'));

            $returnData['rerouteurl'] = ('/PAI-proj/user/' . $f3->get('SESSION.username') . '/job/' . $jobsMapper->id);
        } else {
            $returnData['success'] = false;
            $returnData['errors'] = $errors;
        }

        echo json_encode ($returnData);
    }

    function showNewJobEditor ($f3) {
        $this->isPageLoginProtected = true;
        $f3->set('content', 'newjobeditor.html');
    }

    function countJobs ($constrainsArr = NULL) {
        $jobsMapper = new DB\SQL\Mapper($this->db, 'job');
        return $jobsMapper->count ($constrainsArr);
    }

    //f3 mapper accepts second parameter for pagination, that is:
    // e.g.
    // array(
    // 'order'=>'userID DESC',
    // 'offset'=>5,
    // 'limit'=>3
    // )
    private function getJobs ($constrainsArr = NULL, $paginationSettings = NULL) {
        $jobsMapper = new DB\SQL\Mapper($this->db, 'job');
        $jobsMapper->load($constrainsArr, $paginationSettings);
        $jobArray = array();
        for ($i =  0; $i < $jobsMapper->loaded(); $i++) {
            $jobArray[$i] = $this->jobMapperToJob($jobsMapper);
            $jobsMapper->next();
        }
        return $jobArray;
    }

    private function getUserIdFromUsername ($username) {
        $userController = new Users();
        $userController->setF3($this->f3);
        $users = $userController->getUsers(array('username = ?', $username), NULL);
        $userid = -1;
        if (count($users) > 0)
            $userid = $users[0]->id;

        return $userid;
    }

    private function jobMapperToJob ($m) {
        $bidsMapper = new DB\SQL\Mapper($this->db, 'bid');
        $usersMapper = new DB\SQL\Mapper($this->db, 'user');
        $usersMapper->load(array('id = ?', $m->userid));
        $username = $usersMapper->username;
        $bidsMapper->load(array ('job_id = ?', $m->id), NULL);
        $bidsArray = array();

        for ($i = 0; $i < $bidsMapper->loaded(); $i++) {
            $usersMapper->load(array('id = ?', $bidsMapper->user_id));
            $bidsArray[$i] = new Bid(
                $bidsMapper->id,
                $bidsMapper->job_id,
                $bidsMapper->user_id,
                $usersMapper->username,
                $bidsMapper->time,
                $bidsMapper->value
            );
            $bidsMapper->next();
        }

        $j = new Job(
            $m->id,
            $m->userid,
            $username,
            $m->name,
            $m->description,
            $m->initial_price,
            $m->creation_time,
            $m->job_start_time,
            $m->job_end_time,
            $m->finished,
            $bidsArray
        );
        return $j;
    }
}

class Job {
    public $id;
    public $userid;
    public $username;
    public $name;
    public $description;
    public $creation_time;
    public $job_start_time;
    public $job_end_time;
    public $initial_price;
    public $bids;
    public $current_price;
    public $finished;

    /**
     * Job constructor.
     * @param $id
     * @param $userId
     * @param $username
     * @param $name
     * @param $description
     * @param $creation_time
     * @param $job_start_time
     * @param $job_end_time
     * @param $finished
     * @param $bids
     */
    public function __construct($id, $userId, $username, $name, $description, $initial_price,
                                $creation_time, $job_start_time, $job_end_time, $finished, $bids)
    {
        $this->id = $id;
        $this->userid = $userId;
        $this->username = $username;
        $this->name = $name;
        $this->description = $description;
        $this->initial_price = $initial_price;
        $this->creation_time = $creation_time;
        $this->job_start_time = $job_start_time;
        $this->job_end_time = $job_end_time;
        $this->bids = $bids;
        $this->finished = $finished === 1;
        $bidValues =
            array_map(function($bid){
                return $bid->value;
            }, $bids);

        if (count($bidValues) > 0) {
            $this->current_price = min($bidValues);
        } else {
            $this->current_price = $initial_price;
        }
    }

}
