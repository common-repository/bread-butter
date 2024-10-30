<?php

namespace BreadButter_WP_Plugin\Api\IdPx;

use \BreadButter_WP_Plugin\Base\BaseController;
use \Exception as Exception;

class ManageClient extends BaseController {

    private $request;
    private $m_request;

    private $manage_request;
    private $idpx_request;

    private $api_path = 'https://api.breadbutter.io/';
    private $manage_path = 'https://manage.breadbutter.io/';
    private $session_token;

    /*
     *  Configure API client with required settings
     *  $settins array will required the following keys
     *
     *  - api_key
     */

    public function __construct($settings) {
        if (isset($settings['api_path'])) {
            if (substr($settings['api_path'], -1) != '/') {
                $settings['api_path'] .= '/';
            }
            $this->api_path = $settings['api_path'];
            $this->manage_path = str_replace("api", "manage", $this->api_path);
        }

        if (isset($settings['session_token'])) {
            $this->session_token = $settings['session_token'];
        }
        if (isset($settings['session'])) {
            $this->session = $settings['session'];
        }
    }

    private function connection() {
        if (!$this->request) {
            $this->request = new Connection($this->api_path, false, $this->session_token);
        }
        return $this->request;
    }

    private function connection_manage() {
        if (!$this->request) {
            $this->request = new Connection($this->manage_path, false, $this->session);
        }
        return $this->request;
    }

    private function idpx() {
        if (!$this->idpx_request) {
            $connection = $this->connection();
            $this->idpx_request = new IDPX($connection);
        }
        return $this->idpx_request;
    }

    private function manage() {
        if (!$this->manage_request) {
            $connection = $this->connection_manage();
            $this->manage_request = new IDPX($connection);
        }
        return $this->manage_request;
    }

    public function manageRegisterDevice() {
        return $this->idpx()->manageRegisterDevice();
    }

    public function bbPageView($app_id, $device_id, $data = null, $referrer_url = null) {
        //page_view
        return $this->idpx()->bbPageView($app_id, $device_id, $data, $referrer_url);
    }
    public function bbEngagement($app_id, $device_id, $page_view_id, $t, $c, $m, $s, $ga_data, $segment_anonymous_id) {
        //page_view
        return $this->idpx()->bbEngagement($app_id, $device_id, $page_view_id, $t, $c, $m, $s, $ga_data, $segment_anonymous_id);
    }


    public function manageCreateEvent($device_id, $code, $data = null, $page_view_id = null) {
        return $this->idpx()->manageCreateEvent($device_id, $code, $data, $page_view_id);
    }

    public function getApps() {
        return $this->idpx()->manageGetApps();
    }

    public function getApp($app_id) {
        return $this->idpx()->manageGetApp($app_id);
    }

    public function updateApp($app_id, $data) {
        return $this->idpx()->manageUpdateApp($app_id, $data);
    }

    public function getWebsiteDomains($app_id) {
        return $this->idpx()->manageGetWebsiteDomains($app_id);
    }

    public function createWebsiteDomain($app_id, $domain) {
        return $this->idpx()->manageCreateWebsiteDomain($app_id, $domain);
    }

    public function createAppSecret($app_id, $name) {
        return $this->idpx()->manageCreateAppSecret($app_id, $name);
    }

    public function setPrimaryAppWebsiteDomain($app_id, $domain_id) {
        return $this->idpx()->manageSetPrimaryAppWebsiteDomain($app_id, $domain_id);
    }

    public function getAppProviders($app_id) {
        return $this->idpx()->manageGetAppProviders($app_id);
    }

    public function enableProvider($app_id, $provider_id) {
        return $this->idpx()->manageEnableProvider($app_id, $provider_id);
    }

    public function createApp($name, $gateway_id) {
        return $this->manage()->manageCreateApp($name, $gateway_id);
    }

    public function getProfile() {
        return $this->manage()->manageGetProfile();
    }
}