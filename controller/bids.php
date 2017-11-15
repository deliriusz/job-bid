<?php

require('controller/utils.php');
class Bids extends Controller {

  //ajax request
  //TODO problem with auto logging out
  function placeBid ($f3) {
    $errors = array();
    $returnData = array();
    $username = $f3->get('SESSION.username');
    $jobid = $f3->get('PARAMS.jobid');
    $bid = $f3->get('POST.bid');


    if (!preg_match('/^[0-9]{0,}[.]{0,1}[0-9]{0,2}$/', $bid)) {
      array_push($errors, 'Bid provided is not proper value');
    }

    $errorsFromCreateBid = $this->createBid($jobid, $bid, $username);
    foreach ($errorsFromCreateBid as $key => $value) {
      array_push($errors, $value);
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

    $usersMapper->load(array('username = ?', $username));
    $jobsMapper->load(array('id = ?', $jobid));
    if ($jobsMapper->loaded() === 0) {
      array_push($errors, 'No matching job found');
    } else if ($usersMapper->loaded() === 0 ) {
      array_push($errors, 'Username not found. Please logout and login again');
    } else {
      $bidsMapper->job_id = $jobsMapper->id;
      $bidsMapper->user_id = $usersMapper->id;
      $bidsMapper->time = Utils::getCurrentDateTime();
      $bidsMapper->value = $bid;
      $bidsMapper->save();
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
