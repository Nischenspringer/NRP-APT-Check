<?php

namespace Nischenspringer;

/**
 * Class APT
 * @package Nischenspringer
 *
 * Small class to grab the list of available upgrades.
 */
class APT
{
    const STABLE = 'Stable', SECURITY = 'Security', OTHER = 'Other', RELEASE = 'release', PKG = 'pkg', VER = 'ver';

    static function retrieveUpdates()
    {
        if (!function_exists('exec')) {
            throw new \Exception("Mandatory function 'exec' is not available!");
        }

        $output = array();
        exec('/usr/bin/apt-get -s upgrade', $output);

        $result = array(self::SECURITY => array(), self::STABLE => array(), self::OTHER => array());

        foreach ($output as $line) {
            if (preg_match('/Inst (?P<' . self::PKG . '>.*?) .*\((?P<' . self::VER . '>.*?) (?P<' . self::RELEASE . '>.*?)\)/', $line, $regs)) {
                if (preg_match('/security/i', $regs[self::RELEASE])) {
                    $result[self::SECURITY][$regs[self::PKG]] = $regs[self::VER];
                } else if (preg_match('/stable$/', $regs[self::RELEASE])) {
                    $result[self::STABLE][$regs[self::PKG]] = $regs[self::VER];
                } else {
                    $result[self::OTHER][$regs[self::PKG]] = $regs[self::VER];
                }
            }
        }

        return $result;
    }

}