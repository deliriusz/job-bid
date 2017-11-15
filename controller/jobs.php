<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 07.11.2017
 * Time: 19:46
 */

class Jobs extends Controller
{
    function viewJobsPage ($f3) {
        $pageToDisplay = $f3->get('GET.p') != NULL ? $f3->get('GET.p') - 1 : 0;
        $itemsPerPage = $f3->get('itemsperpage');

        $f3->set('jobs', $this->getJobs(NULL, array(
            'offset' => $pageToDisplay * $itemsPerPage,
            'limit' => $itemsPerPage
        )));
        $f3->set('content', 'jobs.html');
    }

    function viewUserJobsPage ($f3)
    {
        $username = $f3->get('PARAMS.username');
        $jobs = $this->getJobs(
            array('userid = ?', $this->getUserIdFromUsername($username)),
            NULL
        );

        if (count($jobs)) {
            $f3->set('jobs', $jobs);
        }

        $content = 'jobs.html';
        $f3->set('content', $content);
    }

    function viewSpecificJobForUser ($f3)
    {
        //Login::handleUserShouldBeLogged($f3);
        $jobid = $f3->get('PARAMS.jobid');
        $username = $f3->get('PARAMS.username');
        $job = $this->getJobs(
            array('id = ? AND userid = ?', $jobid,
                $this->getUserIdFromUsername($username)),
            NULL
        );

        if (count($job)) {
            $f3->set('job', $job[0]);
        }

        $content = 'job.html';
        $f3->set('content', $content);
    }

    function viewSpecificJob ($f3) {
        $jobid = $f3->get('PARAMS.jobid');
        $job = $this->getJobs(array ('id = ?', $jobid), NULL);
        if (count($job)) {
            $f3->set ('job', $job[0]);
        }
        $content = 'job.html';

        $f3->set('content', $content);
    }

    function postNewJob ($f3) {
        $jobsMapper = new DB\SQL\Mapper($this->db, 'job');
        $currentTime = time();
        $currentDate = date("Y-m-d H:M:S",$currentTime);
        $jobName = $_POST['jobName'];
        $jobStartDate = $_POST['jobStartDate'];
        $jobEndDate = $_POST['jobEndDate'];
        $jobInitialPayment = $_POST['jobInitialPayment'];
        $jobDescription = $_POST['jobDescription'];

        $jobsMapper->userid = $f3->get('SESSION.userid');
        $jobsMapper->name = $jobName;
        $jobsMapper->description = $jobDescription;
        $jobsMapper->initial_price = $jobInitialPayment;
        $jobsMapper->creation_time = $currentDate;
        $jobsMapper->job_start_time = $jobStartDate;
        $jobsMapper->job_end_time = $jobEndDate;

        $jobsMapper->save();

        $f3->reroute('/user/' . $f3->get('SESSION.username') . '/job/' . $jobsMapper->id);
    }

    function showNewJobEditor ($f3) {
        $f3->set('content', 'newjobeditor.html');
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
        $j = new Job(
            $m->id,
            $m->userid,
            $m->name,
            $m->description,
            $m->initial_price,
            $m->creation_time,
            $m->job_start_time,
            $m->job_end_time
        );
        return $j;
    }
}

class Job {
    public $id;
    public $userId;
    public $name;
    public $description;
    public $creation_time;
    public $job_start_time;
    public $job_end_time;
    public $initial_price;

    /**
     * Job constructor.
     * @param $id
     * @param $userId
     * @param $name
     * @param $description
     * @param $creation_time
     * @param $job_start_time
     * @param $job_end_time
     */
    public function __construct($id, $userId, $name, $description, $initial_price,
                                $creation_time, $job_start_time, $job_end_time)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->description = $description;
        $this->initial_price = $initial_price;
        $this->creation_time = $creation_time;
        $this->job_start_time = $job_start_time;
        $this->job_end_time = $job_end_time;
    }

}
