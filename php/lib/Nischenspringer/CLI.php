<?php

namespace Nischenspringer;

/**
 * Class CLI
 * @package Nischenspringer
 *
 * Tiny helper class to grab a parameter from the command line.
 */
class CLI
{
    static function param($param)
    {
        GLOBAL $argv;
        for ($i = 0, $l = count($argv); $i < $l; $i++) {
            if ($argv[$i] == '-' . $param && $i + 1 < $l) {
                return $argv[$i + 1];
            }
        }
        return null;
    }
}
