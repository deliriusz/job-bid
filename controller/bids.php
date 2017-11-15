<?php

class Bids extends Controller {
  function viewBidsForUser () {

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
