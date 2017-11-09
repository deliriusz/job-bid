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

    function view ($f3) {
        $this->f3 = $f3;
        $f3->set('content', 'jobs.html');
//        echo Template::instance()->render('template.html');
        $this->getJobs();
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

    private function getJobs () {
        $jobsMapper = new DB\SQL\Mapper($this->f3->get('DB'), 'job');
        $jobsMapper->load();
        $jobArray = array();
        for ($i =  0; $i < $jobsMapper->loaded(); $i++) {
            $jobArray[$i] = $this->jobMapperToJob($jobsMapper);
        }
        var_dump($jobArray);
        return $jobArray;
    }

    private function jobMapperToJob ($m) {
        $j = new Job(
            $m->id,
            $m->userid,
            $m->name,
            $m->description,
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
    public function __construct($id, $userId, $name, $description, $creation_time, $job_start_time, $job_end_time)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->description = $description;
        $this->creation_time = $creation_time;
        $this->job_start_time = $job_start_time;
        $this->job_end_time = $job_end_time;
    }

}
