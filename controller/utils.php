<?php

class Utils {
  static function getCurrentDateTime ($format = "Y-m-d H:i:s") {
    $currentTime = time();
    return date($format,$currentTime);
  }
}
