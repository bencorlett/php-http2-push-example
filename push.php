<?php
$callback = function($data) {
	$args = func_get_args();
	var_dump($args);
};

$mh = curl_multi_init();

curl_multi_setopt($mh, CURLMOPT_PUSHFUNCTION, $callback);
$data = [];
curl_multi_setopt($mh, CURLMOPT_PUSHDATA, $data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://0.0.0.0:8080/curl');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

curl_multi_add_handle($mh, $ch);

$active = null;
//execute the handles
do {
    $mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);

while ($active && $mrc == CURLM_OK) {
    if (curl_multi_select($mh) != -1) {
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
}

curl_multi_close($mh);
