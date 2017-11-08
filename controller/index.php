<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 07.11.2017
 * Time: 21:27
 */

class Index
{
    function view ($f3) {

    }

    function error($f3) {
        $log=new Log('error.log');
        $log->write($f3->get('ERROR.text'));
        foreach ($f3->get('ERROR.trace') as $frame)
            if (isset($frame['file'])) {
                // Parse each backtrace stack frame
                $line='';
                $addr=$f3->fixslashes($frame['file']).':'.$frame['line'];
                if (isset($frame['class']))
                    $line.=$frame['class'].$frame['type'];
                if (isset($frame['function'])) {
                    $line.=$frame['function'];
                    if (!preg_match('/{.+}/',$frame['function'])) {
                        $line.='(';
                        if (isset($frame['args']) && $frame['args'])
                            $line.=$f3->csv($frame['args']);
                        $line.=')';
                    }
                }
                // Write to custom log
                $log->write($addr.' '.$line);
            }
        $f3->set('inc','error.htm');
    }

}