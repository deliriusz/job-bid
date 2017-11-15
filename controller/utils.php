<?php

class Utils {
  static function getCurrentDateTime ($format = "Y-m-d H:M:S") {
    $currentTime = time();
    return date($format,$currentTime);
  }
}
