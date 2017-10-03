<?php

function getvisitorlocation($ip) {
	$details = json_decode(file_get_contents("http://www.freegeoip.net/json/{$ip}"));
	return $details;
}

?>