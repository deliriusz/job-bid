<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 19.11.2017
 * Time: 14:13
 */

class Logger
{
    static function log ($data) {
        $log=new Log('error.log');
        $log->write($data);
    }
}