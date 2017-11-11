<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 07.11.2017
 * Time: 19:46
 */

class Jobs
{
    private $f3;

    function viewJobsPage ($f3) {
        $this->f3 = $f3;
        $pageToDisplay = $f3->get('GET.p') != NULL ? $f3->get('GET.p') - 1 : 0;
        $itemsPerPage = $f3->get('itemsperpage');

        $f3->set('jobs', $this->getJobs(NULL, array(
            'offset' => $pageToDisplay * $itemsPerPage,
            'limit' => $itemsPerPage
        )));
        $f3->set('content', 'jobs.html');
        echo Template::instance()->render('template.html');
    }

    function viewUserJobsPage ($f3)
    {
        $this->f3 = $f3;
        $jobid = $f3->get('PARAMS.jobid');
        $username = $f3->get('PARAMS.username');
        $job = $this->getJobs(array('id = ?', $jobid), NULL);
        $content = '';
        if (count($job)) {
            $f3->set('job', $job[0]);
            $content = 'job.html';
        }
        $f3->set('content', $content);
        echo Template::instance()->render('template.html');
    }

    function viewSpecificJobForUser ($f3)
    {
        $this->f3 = $f3;
        $jobid = $f3->get('PARAMS.jobid');
        $username = $f3->get('PARAMS.username');
        $job = $this->getJobs(array('id = ?', $jobid), NULL);
        $content = '';
        if (count($job)) {
            $f3->set('job', $job[0]);
            $content = 'job.html';
        }
        $f3->set('content', $content);
        echo Template::instance()->render('template.html');
    }

    function viewSpecificJob ($f3) {
        $this->f3 = $f3;
        $jobid = $f3->get('PARAMS.jobid');
        $job = $this->getJobs(array ('id = ?', $jobid), NULL);
        $content = '';
        if (count($job)) {
            $f3->set ('job', $job[0]);
            $content = 'job.html';
        }

        $f3->set('content', $content);
        echo Template::instance()->render('template.html');
    }

    function donotusenow ($f3) { //TODO change to something useful
        $this->f3 = $f3;
        if (! (isset($_POST['inputUsername']) && isset($_POST['inputPassword'])) ) {
            die ('Not passed all login parameters');
        }
        $user = $this->getUserData($_POST['inputUsername']);
        $calculatedPass = hash('sha256', $user['salt'] . $_POST['inputPassword']);

        if ($user['username'] === $_POST['inputUsername'] &&
            $user['password'] === $calculatedPass) {

            $f3->set('SESSION.username', $user['username']);

            $f3->reroute('/register/welcome');
        } else {
            echo 'username not matched!!';
        }
    }

    //f3 mapper accepts second parameter for pagination, that is:
    // e.g.
    // array(
    // 'order'=>'userID DESC',
    // 'offset'=>5,
    // 'limit'=>3
    // )
    private function getJobs ($constrainsArr = NULL, $paginationSettings = NULL) {
        $jobsMapper = new DB\SQL\Mapper($this->f3->get('DB'), 'job');
        $jobsMapper->load($constrainsArr, $paginationSettings);
        $jobArray = array();
        for ($i =  0; $i < $jobsMapper->loaded(); $i++) {
            $jobArray[$i] = $this->jobMapperToJob($jobsMapper);
            $jobsMapper->next();
        }
        return $jobArray;
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
