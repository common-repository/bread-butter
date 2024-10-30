<?php
/**
 * Created by PhpStorm.
 * User: hlee
 * Date: 2019-02-25
 * Time: 2:17 PM
 */

namespace BreadButter_WP_Plugin\Api\IdPx;
use \BreadButter_WP_Plugin\Base\BaseController;

class IDPX extends BaseController {

    private $connection;

    private $options;

    const ROUTE_VALIDATE = 'validate';
    const ROUTE_REDIRECT = 'redirect';
    const ROUTE_START = 'start';
    const ROUTE_PING = 'ping';
    const ROUTE_PROVIDERS = 'providers';
    const ROUTE_AUDIT = 'audit';
    const ROUTE_VALIDATE_LOCAL = 'event';
    const ROUTE_APPS = 'apps';
    const ROUTE_AUTHENTICATION = 'authentications';
    const ROUTE_EVENT_DEFINITIONS = 'event_definitions';
    const ROUTE_INTEGRATIONS = 'integrations';
    const ROUTE_TRIGGERS = 'triggers';
    const ROUTE_ACTIONS = 'actions';
    const ROUTE_PROFILE = 'profile';

    const ROUTE_ENGAGEMENT = 'prerelease/page_engagement';

    const ROUTE_DEVICE = 'devices';

    const ROUTE_EVENT = 'events';

    private function getManageAppId() {
        $api_path = get_option('logon_api_path', 'https://api.breadbutter.io');
        $app_id_mapping = [
            'https://api-devlab.breadbutter.io' => '60a5637a50471a35730148de',
            'https://api-stable.breadbutter.io' => '61200aeca12cb4cc7757aed8',
            'https://api.breadbutter.io' => '6128183c5a20bd2cb74d3998',
        ];
        return $app_id_mapping[$api_path];
    }

    public function __construct($connection, $options = array()) {
        if (!$this->connection) {
            $this->connection = $connection;
        }
        $this->options = $options;
    }

    public function getAuthentication($app_id, $authentication_token) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_AUTHENTICATION .
            '/' . $authentication_token;
        return $this->connection->get($cmd);
    }

    public function createAppEventDefinition($app_id, $name, $code, $color) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_EVENT_DEFINITIONS;
        return $this->connection->post($cmd, array(
            'name' => $name,
            'code' => $code,
            'color' => $color
        ));
    }

    public function getAppEventDefinitions($app_id, $page = 1) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_EVENT_DEFINITIONS;
        $query = array(
            'page' => $page
        );
        return $this->connection->get($cmd, $query);
    }

    public function updateAppEventDefinition($app_id, $event_definition_id, $name, $color) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_EVENT_DEFINITIONS .
            '/' . $event_definition_id;
        return $this->connection->patch($cmd, array(
            'name' => $name,
            'color' => $color
        ));
    }

    public function createAppIntegration($app_id, $name, $type, $data) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_INTEGRATIONS;

        return $this->connection->post($cmd, array(
            'name' => $name,
            'type' => $type,
            'data' => $data
        ));
    }

    public function getAppIntegration($app_id, $integration_id) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_INTEGRATIONS .
            '/' . $integration_id;
        return $this->connection->get($cmd);
    }

    public function updateAppIntegration($app_id, $integration_id, $name, $data) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_INTEGRATIONS .
            '/' . $integration_id;
        return $this->connection->patch($cmd, array(
            'name' => $name,
            'data' => $data
        ));
    }

    public function createAppTrigger($app_id, $event_definition_id, $name) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_TRIGGERS;
        return $this->connection->post($cmd, array(
            'name' => $name,
            'event_definition_id' => $event_definition_id
        ));
    }

    public function getAppTriggers($app_id) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_TRIGGERS;
        return $this->connection->get($cmd);
    }

    public function createAppTriggerAction($app_id, $trigger_id, $integration_id, $type, $data) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_TRIGGERS .
            '/' . $trigger_id .
            '/' . self::ROUTE_ACTIONS;
        return $this->connection->post($cmd, array(
            'integration_id' => $integration_id,
            'type' => $type,
            'data' => $data
        ));
    }

    public function updateAppTriggerAction($app_id, $action_id, $trigger_id,$integration_id, $type, $data) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_TRIGGERS .
            '/' . $trigger_id .
            '/' . self::ROUTE_ACTIONS .
            '/' . $action_id;
        return $this->connection->put($cmd, array(
            'integration_id' => $integration_id,
            'type' => $type,
            'data' => $data
        ));
    }

    public function testAppIntegration($app_id, $integration_id) {
        $cmd = self::ROUTE_APPS .
            '/' . $app_id .
            '/' . self::ROUTE_INTEGRATIONS .
            '/' . $integration_id .
            '/test';
        return $this->connection->post($cmd);
    }

    public function manageGetApps() {
        $cmd = self::ROUTE_APPS;
        return $this->connection->get($cmd);
    }
    public function manageGetApp($app_id) {
        $cmd = self::ROUTE_APPS . '/' . $app_id;
        return $this->connection->get($cmd);
    }

    public function manageUpdateApp($app_id, $data) {
        $cmd = self::ROUTE_APPS . '/' . $app_id;
        return $this->connection->patch($cmd, $data);
    }

    public function manageGetWebsiteDomains($app_id) {
        $cmd = self::ROUTE_APPS . '/' . $app_id . '/website_domains';
        return $this->connection->get($cmd);
    }

    public function manageCreateWebsiteDomain($app_id, $domain) {
        $cmd = self::ROUTE_APPS . '/' . $app_id . '/website_domains';
        return $this->connection->post($cmd, array(
            'domain' => $domain
        ));
    }

    public function manageCreateAppSecret($app_id, $name) {
        $cmd = self::ROUTE_APPS . '/' . $app_id . '/secrets';
        return $this->connection->post($cmd, array(
            'name' => $name
        ));
    }

    public function manageSetPrimaryAppWebsiteDomain($app_id, $domain_id) {
        $cmd = self::ROUTE_APPS . '/' . $app_id . '/website_domains/' . $domain_id . '/primary';
        return $this->connection->post($cmd);
    }

    public function manageGetAppProviders($app_id) {
        $cmd = self::ROUTE_APPS . '/' . $app_id . '/' . self::ROUTE_PROVIDERS;
        return $this->connection->get($cmd);
    }
    public function manageEnableProvider($app_id, $provider_id) {
        $cmd = self::ROUTE_APPS . '/' . $app_id . '/' . self::ROUTE_PROVIDERS . '/' . $provider_id .'/enable';
        return $this->connection->post($cmd);
    }

    public function manageCreateApp($name, $gateway_id) {
        $cmd = self::ROUTE_APPS;
        return $this->connection->post($cmd, array(
            'name' => $name,
            'gateway_id' => $gateway_id
        ));
    }

    public function manageRegisterDevice() {
        $app_id = $this->getManageAppId();
        $cmd = self::ROUTE_APPS . '/' . $app_id . '/' . self::ROUTE_DEVICE;
        return $this->connection->post($cmd);
    }

    public function manageCreateEvent($device_id, $code, $data = null, $page_view_id = null) {
        $app_id = $this->getManageAppId();
        $cmd = self::ROUTE_APPS . '/' . $app_id . '/' . self::ROUTE_EVENT;
        $this->logs($cmd);
        $params = array(
            'device_id' => $device_id,
            'code' => $code
        );
        if (!empty($data)) {
            $params['data'] = $data;
        }
        if (!empty($page_view_id)) {
            $params['page_view_id'] = $page_view_id;
        }

        return $this->connection->post($cmd, $params);
    }


    public function bbPageView($app_id, $device_id, $data = null, $referrer_url = null) {
        $cmd = self::ROUTE_APPS . '/' . $app_id . '/' . self::ROUTE_EVENT;
        $this->logs($cmd);
        $params = array(
            'device_id' => $device_id,
            'code' => 'page_view'
        );
        if (!empty($data)) {
            $params['data'] = $data;
        }
        if (!empty($referrer_url)) {
            $params['referrer_url'] = $referrer_url;
        }

//        $header = array(
//            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
//        );
        return $this->connection->post($cmd, $params);
    }
    public function bbEngagement($app_id, $device_id, $page_view_id, $t, $c, $m, $s, $ga_data, $segment_anonymous_id) {
        $cmd = self::ROUTE_APPS . '/' . $app_id . '/' . self::ROUTE_ENGAGEMENT;
        $this->logs($cmd);
        $params = array(
            'device_id' => $device_id,
            'page_view_id' => $page_view_id,
            't' => $t,
            'c' => $c,
            'm' => $m,
            's' => $s,
            'ga_data' => $ga_data,
            'segment_anonymous_id' => $segment_anonymous_id
        );
//        $header = array(
//            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
//        );
        return $this->connection->post($cmd, $params);
    }


    public function manageGetProfile() {
        $cmd = self::ROUTE_PROFILE;
        return $this->connection->get($cmd);
    }

    public function updateAppOrganization($app_id, $data) {
        $cmd = self::ROUTE_APPS . '/' . $app_id . '/organization';
        return $this->connection->patch($cmd, $data);
    }
}