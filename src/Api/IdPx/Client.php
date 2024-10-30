<?php

namespace BreadButter_WP_Plugin\Api\IdPx;

use \Exception as Exception;

class Client {

    private $app_id;

    private $request;
    private $m_request;

    private $manage_request;
    private $idpx_request;

    private $api_path = 'https://api.breadbutter.io/';
    private $manage_path = 'https://manage.breadbutter.io/';
    private $app_secret;

    /*
     *  Configure API client with required settings
     *  $settins array will required the following keys
     *
     *  - api_key
     */

    public function __construct($settings) {
        if (!isset($settings['app_id'])) {
            throw new Exception("'app_id' must be provided");
        }
        $this->app_id = $settings['app_id'];

        if (isset($settings['api_path'])) {
            if (substr($settings['api_path'], -1) != '/') {
                $settings['api_path'] .= '/';
            }
            $this->api_path = $settings['api_path'];
            $this->manage_path = str_replace("api", "manage", $this->api_path);
        }

        if (isset($settings['app_secret'])) {
            $this->app_secret = $settings['app_secret'];
        }
    }

    private function connection() {
        if (!$this->request) {
            $this->request = new Connection($this->api_path, $this->app_secret);
        }
        return $this->request;
    }

    private function manage_connection() {
        if (!$this->m_request) {
            $this->m_request = new Connection($this->manage_path);
        }
        return $this->m_request;
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
            $connection = $this->manage_connection();
            $this->manage_request = new Manage($connection);
        }
        return $this->manage_request;
    }

    public function getAuthentication($token) {
        return $this->idpx()->getAuthentication($this->app_id, $token);
    }

    public function getAuthenticationCallback($token) {
        return $this->manage()->getAuthenticationCallback($token);
    }

    public function createAppEventDefinition($name, $code, $color) {
        return $this->idpx()->createAppEventDefinition($this->app_id, $name, $code, $color);
    }

    public function updateAppEventDefinition($event_definition_id, $name, $color) {
        return $this->idpx()->updateAppEventDefinition($this->app_id, $event_definition_id, $name, $color);
    }

    public function getAppEventDefinitions($page = 1) {
        return $this->idpx()->getAppEventDefinitions($this->app_id, $page);
    }

    public function createAppIntegration($name, $type, $data) {
        return $this->idpx()->createAppIntegration($this->app_id, $name, $type, $data);
    }

    public function updateAppIntegration($integration_id, $name, $data) {
        return $this->idpx()->updateAppIntegration($this->app_id, $integration_id, $name, $data);
    }

    public function getAppIntegration($integration_id) {
        return $this->idpx()->getAppIntegration($this->app_id, $integration_id);
    }

    public function createAppTrigger($event_definition_id, $name) {
        return $this->idpx()->createAppTrigger($this->app_id, $event_definition_id, $name);
    }

    public function createAppTriggerAction($trigger_id, $integration_id, $type, $data) {
        return $this->idpx()->createAppTriggerAction($this->app_id, $trigger_id, $integration_id, $type, $data);
    }

    public function updateAppTriggerAction($action_id, $trigger_id, $integration_id, $type, $data) {
        return $this->idpx()->updateAppTriggerAction($this->app_id, $action_id, $trigger_id, $integration_id, $type, $data);
    }

    public function getAppTriggers() {
        return $this->idpx()->getAppTriggers($this->app_id);
    }

    public function testAppIntegration($integration_id) {
        return $this->idpx()->testAppIntegration($this->app_id, $integration_id);
    }

    public function getApp($app_id) {
        return $this->idpx()->manageGetApp($app_id);
    }

    public function updateApp($app_id, $data) {
        return $this->idpx()->manageUpdateApp($app_id, $data);
    }

    public function updateAppOrganization($app_id, $data) {
        return $this->idpx()->updateAppOrganization($app_id, $data);
    }
}