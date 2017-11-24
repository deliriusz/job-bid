<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 24.11.2017
 * Time: 11:00
 */

// this is controller responsible for
// maintenance of servers. Not to be used by user
class Maintenance extends Controller
{
    function checkFinishedJobs ($f3) {
        $this->doRender = false;

        $jobsMapper = new DB\SQL\Mapper($this->db, 'job');
        $jobsMapper->load(array('job_end_time < NOW() AND finished = FALSE'));
        for ($i =  0; $i < $jobsMapper->loaded(); $i++) {
            $ec = new EventController($f3);
            $ec->fireEvent($jobsMapper->id, 'job', 3);
            $jobsMapper->finished = true;
            $jobsMapper->save();
            $jobsMapper->next();
        }
    }

}