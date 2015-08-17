<?php

require_once('lib/Nischenspringer/CLI.php');
require_once('lib/Nischenspringer/APT.php');

use Nischenspringer\APT, Nischenspringer\CLI;

// The script must be called with the new relic key, e.g. "$ php apt-check.php -k YOUR_NEW_RELIC_KEY"
if (!$newRelicKey = CLI::param('k')) {
    throw new \Exception('No New Relic key given!');
}

$info = APT::retrieveUpdates();

$metrics = array();

foreach ($info as $type => $updates) {
    $c = count($updates);
    echo "\n$type: $c";
    $metrics["Component/APT/Updates/" . $type . "[updates]"] = $c;
}

$data = array(
    "agent" => array(
        "host" => gethostname(),
        "pid" => getmypid(),
        "version" => "0.0.1"
    ),
    "components" => array(array(
        "name" => "APT Updates",
        "guid" => 'de.nischenspringer.apt.check',
        "duration" => 60 * 60,
        "metrics" => $metrics
    ))
);

$ch = curl_init('https://platform-api.newrelic.com/platform/v1/metrics');
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "X-License-Key: " . $newRelicKey,
    "Content-Type: application/json",
    "Accept: application/json"
));

$curl_result = curl_exec($ch);

echo "\n\nResponse:\n".$curl_result."\n";
