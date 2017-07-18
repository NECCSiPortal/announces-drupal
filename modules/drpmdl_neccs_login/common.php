<?php

/**
 * Send a Get Request.
 */
function http_get_request($url, $headers) {
    $obj = _get_curl_obj($url, $headers);
    return _http_response($obj);
}


/**
 * Send a Post Request.
 */
function http_post_request($url, $headers, $content) {
    $obj = _get_curl_obj($url, $headers);
    curl_setopt($obj, CURLOPT_POSTFIELDS, $content);
    return _http_response($obj);
}


/**
 * Create Curl Object.
 */
function _get_curl_obj($url, $headers) {
    watchdog(
        'drpmdl_neccs_login',
        'Curl object: [%url, %header]',
        array('%url'=>$url,
              '%header'=>implode("'", $headers)),
        WATCHDOG_NOTICE,
        NULL
    );
    $obj = curl_init($url);
    curl_setopt($obj, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($obj, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($obj, CURLOPT_HEADER, true);
    return $obj;
}


/**
 * Get HTTP Response.
 */
function _http_response($obj) {
    $result = curl_exec($obj);
    $status_code = curl_getinfo($obj, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($obj, CURLINFO_HEADER_SIZE);
    curl_close($obj);
    _check_status($status_code);
    $headers = _get_response_headers($result, $header_size);
    $body = _get_response_body($result, $header_size);
    $response['header'] = $headers;
    $response['body'] = $body;
    return $response;
}


/**
 * Check Status Code of HTTP Response.
 * if Status Code not equals 200 and 201 then Exception.
 */
function _check_status($status_code) {
    if ($status_code !== 200 && $status_code !== 201) {
        throw new ErrorException('An error is occurred on establishing connection. Please contact to system manager.');
    }
}


/**
 * Get Header of the HTTP Response.
 */
function _get_response_headers($response, $header_size)
{
    $headers = array();
    $header_text = substr($response, 0, $header_size);
    $header_text = preg_replace("/\r\n|\r/","\n",$header_text);

    foreach (explode("\n", $header_text) as $i => $line) {
        if ($i === 0) {
            $headers['http_code'] = $line;
        } else {
            if ($line != "") {
                list ($key, $value) = explode(': ', $line);
                if ($key != "") {
                    $headers[$key] = $value;
                }
            }
        }
    }

    return $headers;
}

/**
 * Get Body of the HTTP Response.
 */
function _get_response_body($response, $header_size)
{
    $headers = array();
    $body = substr($response, $header_size);


    return $body;
}
