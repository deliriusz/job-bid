<?php

class Utils {
    static function getCurrentDateTime ($format = "Y-m-d H:i:s") {
        $currentTime = time();
        return date($format,$currentTime);
    }

    static function getDateTimeFromTimestamp ($timestamp, $format = "Y-m-d H:i:s") {
        return date($format, $timestamp);
    }

    static function getTimestampFromDateTime ($datetimestring, $format = "Y-m-d H:i:s") {
        $dtime = DateTime::createFromFormat($format, $datetimestring);
        return $dtime->getTimestamp();
    }

    static function validateDateTime ($datetimestring, $format = "Y-m-d H:i:s") {
        return !(!(DateTime::createFromFormat($format, $datetimestring))); // nasty workaround
    }

    static function validateDate ($datetimestring, $format = "Y-m-d") {
        return !(!(DateTime::createFromFormat($format, $datetimestring))); // nasty workaround
    }

    static function getDateTimeDifference ($from, $to) {
        return self::getTimestampFromDateTime($from) - self::getTimestampFromDateTime($to);
    }
}
