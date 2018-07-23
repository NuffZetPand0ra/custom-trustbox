<?php
/**
 * Fetches data directly from trustpilot
 * 
 * @param string $businessUnitId The business unit id assigned by trustpilot.
 * @return object The data object with assigned cache time in epoch time.
 */
function getNew($businessUnitId = "484ab760000064000502a593"){
    $url = "https://widget.trustpilot.com/base-data?businessUnitId=".$businessUnitId;
    $data = json_decode(file_get_contents($url));
    $data->cached = time();
    file_put_contents("cachedData.json", json_encode($data));
    return $data;
}

/**
 * Fetches the cached json object
 * 
 * @return object The cached data object.
 */
function getCached(){
    return json_decode(file_get_contents("cachedData.json"));
}

/**
 * Determines wether a given timestamp is older than the limit.
 * 
 * @param int $unixtime The time to determine.
 * @param string $limit The limit that determines whether the unix string is too old. Takes "hour", "minute" and "tenSeconds".
 * @return bool True if the unix string is older than the limit. False if not.
 */
function isOld($unixtime, $limit = "hour"){
    $times = array(
        "hour" => 60*60,
        "minute" => 60,
        "tenSeconds" => 10
    );
    return $unixtime + $times[$limit] < time();
}
