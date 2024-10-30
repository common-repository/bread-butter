<?php

namespace BreadButter_WP_Plugin\Api\IdPx;
use \Exception as Exception;

/*
 *  Logon Labs API Client
 */

class Connection {
    private $api_url = 'https://api.logonlabs.com/';
    private $app_secret = false;

    private $session_token = false;

    private $curl;
    private $headers = array();
    private $request = array();
    private $additional_headers = [];

    private $response = array();

    const JSON = 'application/json';
    const URLENCODED = 'application/x-www-form-urlencoded';

    public function __construct($api_path, $app_secret = false, $session_token = false) {
        if ($api_path) {
            $this->api_url = $api_path;
        }
        if ($app_secret) {
            $this->app_secret = $app_secret;
        }
        if ($session_token) {
            $this->session_token = $session_token;
        }
    }

    private function handleHeaders() {
        $this->request['headers'] = $this->headers;
    }

    public function get($cmd, $query = false) {
        $url = $this->api_url . $cmd;

        $this->initCall();
        $this->handleHeaders();

        if (is_array($query)) {
            $query = http_build_query($query);
            $url = sprintf("%s?%s", $url, $query);
        }

        $this->request['method'] = 'GET';
        $this->sendRequest($url);

        return $this->handleResponse($url, $query);
    }

    public function post($cmd, $params = [], $headers = []) {
        $url = $this->api_url . $cmd;

        if (!empty($headers)) {
            $this->headers = array_merge($this->headers, $headers);
        }
        $this->initCall();
        $this->handleHeaders();

        if (!empty($params)) {
            $this->request['body'] = json_encode($params);
        }

        $this->request['method'] = 'POST';
        $this->sendRequest($url);

        return $this->handleResponse($url, $params);
    }

    public function patch($cmd, $params = []) {
        $url = $this->api_url . $cmd;

        $this->initCall();
        $this->handleHeaders();

        if (!empty($params)) {
            $this->request['body'] = json_encode($params);
        }

        $this->request['method'] = 'PATCH';
        $this->sendRequest($url);

        return $this->handleResponse($url, $params);
    }

    public function put($cmd, $params = []) {
        $url = $this->api_url . $cmd;

        $this->initCall();
        $this->handleHeaders();

        if (!empty($params)) {
            $this->request['body'] = json_encode($params);
        }

        $this->request['method'] = 'PUT';
        $this->sendRequest($url);

        return $this->handleResponse($url, $params);
    }

    public function redirect($cmd, $query = false) {
        $url = $this->api_url . $cmd;

        if (is_array($query)) {
            $query = http_build_query($query);
            $url = sprintf("%s?%s", $url, $query);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        if (preg_match('~Location: (.*)~i', $result, $match)) {
            $location = trim($match[1]);
        }
        return $location;
    }

    private function sendRequest($url) {
        $response = wp_remote_request($url, $this->request);
        
        $this->response['response'] = $response;
        $this->response['code'] = wp_remote_retrieve_response_code($response);
        $this->response['message'] = wp_remote_retrieve_response_message($response);

        $this->response['body'] = wp_remote_retrieve_body($response);
        $this->response['headers'] = wp_remote_retrieve_headers($response);
    }

    private function handleResponse($url, $data) {
        $raw_body = $this->response['body'];
        try {
            $body = json_decode($raw_body, true);
        } catch (Exception $e) {
            $body = false;
        }


        $output = array(
            'url' => $url,
            'request' => $data,
            'code' => $this->response['code'],
            'message' => $this->response['message'],
            'body' => $body,
            'headers' => $this->response['headers'],
            'raw' => $raw_body
        );

        return $output;
    }

    private function initCall() {
        $this->request = array('timeout' => 30);
        $this->headers = array();
        $this->headers['Accept'] = self::JSON;
        $this->headers['Content-Type'] = self::JSON;
        $this->headers['bb-wpp-version'] = $this->getVersion();
        if ($this->app_secret) {
            $this->headers['X-App-Secret'] = $this->app_secret;
        }
        if ($this->session_token) {
            $this->headers['X-Session-Token'] = $this->session_token;
        }
        if ($this->additional_headers && !empty($this->additional_headers)) {
            $this->headers = array_merge($this->headers, $this->additional_headers);
            // Cleanup additional_headers after adding them to main headers.
            $this->additional_headers = [];
        }
    }

    public function setAdditionalHeader($headers) {
        $this->additional_headers = array_merge($this->additional_headers, $headers);
    }

    protected function getVersion() {
        $path = plugin_dir_path(dirname(__FILE__, 3));
        $file_lines = file($path . 'breadbutter-connect.php');
        foreach($file_lines as $line) {
            preg_match('/^Version\:\s([\d\.]+)/', $line, $matches);
            if (!empty($matches) && isset($matches[1])) {
                return $matches[1];
            }
        }
        return '';
    }
}