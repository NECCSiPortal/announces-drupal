<?php

/**
 * Constant New Line Code of HTTP Reponse.
 */
define("CRLF","\r\n");

/**
 * Create Curl Object.
 */
function get_curl_obj($url, $headers) {
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
function http_response($obj) {
    $result = curl_exec($obj);
    $status_code = curl_getinfo($obj, CURLINFO_HTTP_CODE);
    curl_close($obj);
    check_status($status_code);
    $headers = get_response_headers($result);
    $body = get_response_body($result);
    $response['header'] = $headers;
    $response['body'] = $body;
    return $response;
}

/**
 * Send a Get Request.
 */
function http_get_request($url, $headers) {
    $obj = get_curl_obj($url, $headers);
    return http_response($obj);
}

/**
 * Send a Post Request.
 */
function http_post_request($url, $headers, $content) {
    $obj = get_curl_obj($url, $headers);
    curl_setopt($obj, CURLOPT_POSTFIELDS, $content);
    return http_response($obj);
}

/**
 * Check Status Code of HTTP Response.
 * if Status Code not equals 200 and 201 then Exception.
 */
function check_status($staus_code) {
    if ($staus_code !== 200 && $staus_code !== 201) {
        throw new ErrorException("An error is occured on establishing connection. \n Please contact to system manager.");
    }
}

/**
 * Get Header of the HTTP Response.
 */
function get_response_headers($response)
{
    $headers = array();
    $header_text = substr($response, 0, strpos($response, CRLF . CRLF));
    foreach (explode(CRLF, $header_text) as $i => $line) {
        if ($i === 0) {
            $headers['http_code'] = $line;
        } else {
            list ($key, $value) = explode(': ', $line);
            $headers[$key] = $value;
        }
    }
    return $headers;
}

/**
 * Get Body of the HTTP Response.
 */
function get_response_body($response)
{
    $headers = array();
    $body = substr($response, strpos($response, CRLF . CRLF) + 3);
    return $body;
}