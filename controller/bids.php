<?php

require_once('controller/utils.php');
class Bids extends Controller {

    //ajax request
    function placeBid ($f3)
    {
        $errors = array();
        $returnData = array();
        $username = $f3->get('SESSION.username');
        $jobid = $f3->get('POST.job_id');
        $bid = $f3->get('POST.bid');

        if (!preg_match('/^-{0,1}[0-9]{1,}[.]{0,1}[0-9]{0,2}$/', $bid)) {
            array_push($errors, 'Bid provided is not proper value');
        } else {
            if ($bid < 1) {
                array_push($errors, 'You cannot bid beneath 1');
            }
        }

        if (count($errors) === 0) {
            $errorsFromCreateBid = $this->createBid($jobid, $bid, $username);
            foreach ($errorsFromCreateBid as $key => $value) {
                array_push($errors, $value);
            }
        }

        $this->doRender = false;
        $isSuccess = empty($errors);

        $returnData['success'] = $isSuccess;

        if (!$isSuccess) {
            $returnData['errors'] = $errors;
            echo json_encode ($returnData);
        } else {
            echo json_encode ($returnData);
        }
    }

    private function createBid ($jobid, $bid, $username) {
        $jobsMapper = new DB\SQL\Mapper($this->db, 'job');
        $bidsMapper = new DB\SQL\Mapper($this->db, 'bid');
        $usersMapper = new DB\SQL\Mapper($this->db, 'user');
        $errors = array();

        $jobsMapper->load(array('id = ?', $jobid));

        $bidsMapper->load(array('job_id  = ?', $jobid), array(
            'order' => 'value ASC',
            'limit' => '1'
        ));

        $usersMapper->load(array('username = ?', $username));

        if ($jobsMapper->loaded() === 0) {
            array_push($errors, 'No matching job found');
        } else if ($usersMapper->loaded() === 0 ) {
            array_push($errors, 'Username not found. Please logout and login again');
        } else if ($bidsMapper->loaded() > 0 && $bidsMapper->value <= $bid){
            array_push($errors, 'Your offer is not the lowest one');
        } else if ($jobsMapper->initial_price < $bid) {
            array_push($errors, 'You cannot bid for higher value than initial price');
        } else {
            $bidsMapper->reset();
            $bidsMapper->job_id = $jobid;
            $bidsMapper->user_id = $usersMapper->id;
            $bidsMapper->time = Utils::getCurrentDateTime();
            $bidsMapper->value = $bid;
            $bidsMapper->save();
            $ec = new EventController($this->f3);
            $ec->fireEvent($jobid, 'job', 3); // lower bid
        }

        return $errors;
    }
}


class Bid {
    public $id,
        $job_id,
        $user_id,
        $time,
        $value;

    public function __construct ($id, $job_id, $user_id, $time, $value){
        $this->id = $id;
        $this->job_id = $job_id;
        $this->user_id = $user_id;
        $this->time = $time;
        $this->value = $value;
    }
}
