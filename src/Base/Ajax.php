<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Base;

use \BreadButter_WP_Plugin\Api\IdPx\Client;
use \BreadButter_WP_Plugin\Api\IdPx\ManageClient;
use \BreadButter_WP_Plugin\Base\BaseController;

class Ajax extends BaseController {

    public static $isUserLoggedIn = 'is_user_logged_in';
    public static $createAppEventDefinition = 'create_app_event_definition';
    public static $createAppIntegration = 'create_app_integration';
    public static $createAppTrigger = 'create_app_trigger';
    public static $createAppTriggerAction = 'create_app_trigger_action';

    public static $updateAppEventDefinition = 'update_app_event_definition';
    public static $updateAppIntegration = 'update_app_integration';
    public static $updateAppTriggerAction = 'update_app_trigger_action';

    public static $testAppIntegration = 'test_app_integration';

    public static $getAppEventDefinitions = 'get_app_event_definitions';

    public static $getApp = 'get_app';
    public static $updateApp = 'update_app';


    public static $uploadImage = 'upload_image';

    public static $manageGetApps = 'manage_get_apps';
    public static $manageGetApp = 'manage_get_app';
    public static $manageGetProfile = 'manage_get_profile';
    public static $manageGetWebsiteDomains = 'manage_get_website_domains';
    public static $manageUpdateApp = 'manage_update_app';
    public static $updateAppOrganization = 'update_app_organization';
    public static $manageCreateWebsiteDomain = 'manage_create_website_domain';
    public static $manageCreateAppSecret = 'manage_create_app_secret';
    public static $manageSetPrimaryAppWebsiteDomain = 'manage_set_primary_app_website_domain';
    public static $manageCreateApp = 'manage_create_app';
    public static $manageGetAppProviders = 'manage_get_app_providers';
    public static $manageEnableProvider = 'manage_enable_provider';

    public static $manageCreateEvent = 'manage_create_event';
    public static $manageRegisterDevice = 'manage_register_device';

    public static $bbPageView = 'bb_page_view';
    public static $bbEngagement = 'bb_engagement';

    public function register() {
        add_action('init', array($this, 'initAjax'), 1);
//        add_action('wp_logout', array($this, 'endSession'), 1);
//        add_action('wp_login', array($this, 'endSession'), 1);
    }

    public function initAjax() {
        add_action('wp_ajax_' . self::$isUserLoggedIn, array($this, 'ajaxIsUserLoggedIn'));
        add_action('wp_ajax_' . self::$createAppEventDefinition, array($this, 'createAppEventDefinition'));
        add_action('wp_ajax_' . self::$createAppIntegration, array($this, 'createAppIntegration'));
        add_action('wp_ajax_' . self::$createAppTrigger, array($this, 'createAppTrigger'));
        add_action('wp_ajax_' . self::$createAppTriggerAction, array($this, 'createAppTriggerAction'));


        add_action('wp_ajax_' . self::$updateAppEventDefinition, array($this, 'updateAppEventDefinition'));
        add_action('wp_ajax_' . self::$updateAppIntegration, array($this, 'updateAppIntegration'));
        add_action('wp_ajax_' . self::$updateAppTriggerAction, array($this, 'updateAppTriggerAction'));

        add_action('wp_ajax_' . self::$testAppIntegration, array($this, 'testAppIntegration'));

        add_action('wp_ajax_' . self::$getAppEventDefinitions, array($this, 'getAppEventDefinitions'));

        add_action('wp_ajax_' . self::$uploadImage, array($this, 'uploadImage'));

        add_action('wp_ajax_nopriv_' . self::$isUserLoggedIn, array($this, 'ajaxIsUserLoggedIn'));

        add_action('wp_ajax_' . self::$manageGetApps, array($this, 'manageGetApps'));
        add_action('wp_ajax_' . self::$manageGetApp, array($this, 'manageGetApp'));
        add_action('wp_ajax_' . self::$manageGetProfile, array($this, 'manageGetProfile'));
        add_action('wp_ajax_' . self::$manageGetWebsiteDomains, array($this, 'manageGetWebsiteDomains'));
        add_action('wp_ajax_' . self::$manageUpdateApp, array($this, 'manageUpdateApp'));
        add_action('wp_ajax_' . self::$updateAppOrganization, array($this, 'updateAppOrganization'));
        add_action('wp_ajax_' . self::$manageCreateWebsiteDomain, array($this, 'manageCreateWebsiteDomain'));
        add_action('wp_ajax_' . self::$manageCreateAppSecret, array($this, 'manageCreateAppSecret'));
        add_action('wp_ajax_' . self::$manageSetPrimaryAppWebsiteDomain, array($this, 'manageSetPrimaryAppWebsiteDomain'));
        add_action('wp_ajax_' . self::$manageCreateApp, array($this, 'manageCreateApp'));
        add_action('wp_ajax_' . self::$manageGetAppProviders, array($this, 'manageGetAppProviders'));
        add_action('wp_ajax_' . self::$manageEnableProvider, array($this, 'manageEnableProvider'));
        add_action('wp_ajax_' . self::$manageCreateEvent, array($this, 'manageCreateEvent'));
        add_action('wp_ajax_' . self::$manageRegisterDevice, array($this, 'manageRegisterDevice'));
        add_action('wp_ajax_' . self::$bbPageView, array($this, 'bbPageView'));
        add_action('wp_ajax_' . self::$bbEngagement, array($this, 'bbEngagement'));
        add_action('wp_ajax_nopriv_' . self::$bbPageView, array($this, 'bbPageView'));
        add_action('wp_ajax_nopriv_' . self::$bbEngagement, array($this, 'bbEngagement'));


        add_action('wp_ajax_' . self::$getApp, array($this, 'getApp'));
        add_action('wp_ajax_' . self::$updateApp, array($this, 'updateApp'));
    }

    public function ajaxIsUserLoggedIn() {
        echo is_user_logged_in()? 1 : 0;
        wp_die();
    }

    public function checkAdmin() {
        if (!current_user_can( 'manage_options' )) {
            echo 0;
            wp_die();
        }
    }

    public function createAppEventDefinition() {
        $this->checkAdmin();
        $name = $_POST['name'];
        $code = $_POST['code'];
        $color = $_POST['color'];

        $client = $this->getClient();
        $response = $client->createAppEventDefinition($name, $code, $color);
        echo json_encode($response);
        wp_die();
    }

    public function createAppIntegration() {
        $this->checkAdmin();
        $name = $_POST['name'];
        $type = $_POST['type'];
        $data = array();
        if (!empty($_POST['zapier_endpoint'])) {
            $data['zapier_endpoint'] = $_POST['zapier_endpoint'];
        }
        if (!empty($_POST['api_key'])) {
            $data['api_key'] = $_POST['api_key'];
        }
        $client = $this->getClient();
        $response = $client->createAppIntegration($name, $type, $data);
        echo json_encode($response);
        wp_die();
    }

    public function createAppTrigger() {
        $this->checkAdmin();
        $name = $_POST['name'];
        $event_definition_id = $_POST['event_definition_id'];

        $client = $this->getClient();
        $response = $client->createAppTrigger($event_definition_id, $name);
        echo json_encode($response);
        wp_die();
    }

    public function createAppTriggerAction() {
        $this->checkAdmin();
        $trigger_id = $_POST['trigger_id'];
        $integration_id = $_POST['integration_id'];
        $type = $_POST['type'];
        $data = array();

        if (!empty($_POST['audience_id'])) {
            $data['audience_id'] = $_POST['audience_id'];
        }
        if (!empty($_POST['audience_action'])) {
            $data['audience_action'] = $_POST['audience_action'];
        }

        $client = $this->getClient();
        $response = $client->createAppTriggerAction($trigger_id, $integration_id, $type, $data);
        echo json_encode($response);
        wp_die();
    }

    public function getAppEventDefinitions() {
        $this->checkAdmin();
        $client = $this->getClient();
        $response = $client->getAppEventDefinitions();
        echo json_encode($response);
        wp_die();
    }

    public function updateAppEventDefinition() {
        $this->checkAdmin();
        $id = $_POST['id'];
        $name = $_POST['name'];
        $color = $_POST['color'];

        $client = $this->getClient();
        $response = $client->updateAppEventDefinition($id, $name, $color);
        echo json_encode($response);
        wp_die();
    }

    public function updateAppIntegration() {
        $this->checkAdmin();
        $id = $_POST['id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $data = array();
        if (!empty($_POST['zapier_endpoint'])) {
            $data['zapier_endpoint'] = $_POST['zapier_endpoint'];
        }
        if (!empty($_POST['api_key'])) {
            $data['api_key'] = $_POST['api_key'];
        }
        $client = $this->getClient();
        $response = $client->updateAppIntegration($id, $name, $data);
        echo json_encode($response);
        wp_die();
    }

    public function updateAppTriggerAction() {
        $this->checkAdmin();
        $id = $_POST['id'];
        $trigger_id = $_POST['trigger_id'];
        $integration_id = $_POST['integration_id'];
        $type = $_POST['type'];
        $data = array();

        if (!empty($_POST['audience_id'])) {
            $data['audience_id'] = $_POST['audience_id'];
        }
        if (!empty($_POST['audience_action'])) {
            $data['audience_action'] = $_POST['audience_action'];
        }

        $client = $this->getClient();
        $response = $client->updateAppTriggerAction($id, $trigger_id, $integration_id, $type, $data);
        echo json_encode($response);
        wp_die();
    }

    public function uploadImage() {
        $this->checkAdmin();
        $file = $_FILES['file'];
//        echo json_encode($response);

        $type = $file['type'];
        $name = $file['name'];
        $image_url = $file['tmp_name'];

        $upload_dir = wp_upload_dir();

        $image_data = file_get_contents( $image_url );

        $filename = basename( $name );

        if ( wp_mkdir_p( $upload_dir['path'] ) ) {
            $file = $upload_dir['path'] . '/' . $filename;
        }
        else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }

        file_put_contents( $file, $image_data );

        $wp_filetype = wp_check_filetype( $filename, null );

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name( $filename ),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment( $attachment, $file );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        $url = wp_get_attachment_url($attach_id);
        echo $url;
        wp_die();
    }

    public function testAppIntegration() {
        $this->checkAdmin();
        $id = $_POST['id'];

        $client = $this->getClient();
        $response = $client->testAppIntegration($id);
        echo json_encode($response);
        wp_die();
    }

    public function getClient() {
        $app_id = get_option('logon_app_id');
        $app_secret = get_option('logon_app_secret');
        $api_path = get_option('logon_api_path');


        return new Client(array(
            'api_path' => $api_path,
            'app_id' => $app_id,
            'app_secret' => $app_secret
        ));
    }

    public function getGatewayClient($session = false) {
        $api_path = get_option('logon_api_path');

        $data = array(
            'api_path' => $api_path
        );

        if ($session) {
            $data['session_token'] = $session;
        }

        return new ManageClient($data);
    }

    public function getManageClient($session) {
        $api_path = get_option('logon_api_path');

        return new ManageClient(array(
            'api_path' => $api_path,
            'session' => $session
        ));
    }

    public function manageGetApps() {
        $this->checkAdmin();
        $session = $_POST['session_token'];
        $client = $this->getGatewayClient($session);
        $response = $client->getApps();
        echo json_encode($response);
        wp_die();
    }

    public function manageGetApp() {
        $this->checkAdmin();
        $session = $_POST['session_token'];
        $app_id = $_POST['app_id'];
        $client = $this->getGatewayClient($session);
        $response = $client->getApp($app_id);
        echo json_encode($response);
        wp_die();
    }

    public function manageRegisterDevice() {
        $this->checkAdmin();
        $client = $this->getGatewayClient();
        $response = $client->manageRegisterDevice();
        echo json_encode($response);
        wp_die();
    }

    public function manageCreateEvent() {
        $this->checkAdmin();
        $device_id = $_POST['device_id'];
        $code = $_POST['code'];
        $data = $_POST['data'];
        $page_view_id = $_POST['page_view_id'];
        $client = $this->getGatewayClient();
        $response = $client->manageCreateEvent($device_id, $code, $data, $page_view_id);
        echo json_encode($response);
        wp_die();
    }

    public function getApp() {
        $this->checkAdmin();
        $client = $this->getClient();
        $app_id = get_option('logon_app_id');
        $response = $client->getApp($app_id);
        echo json_encode($response);
        wp_die();
    }

    public function updateApp() {
        $this->checkAdmin();
        $client = $this->getClient();
        $app_id = get_option('logon_app_id');
        $app = $_POST['app'];
        $response = $client->updateApp($app_id, $app);
        echo json_encode($response);
        wp_die();
    }

    public function manageUpdateApp() {
        $this->checkAdmin();
        $this->logs(print_r($_POST, 1));
        $session = $_POST['session_token'];
        $app_id = $_POST['app_id'];
        $app = $_POST['app'];
        $client = $this->getGatewayClient($session);
        $response = $client->updateApp($app_id, $app);
        echo json_encode($response);
        wp_die();
    }
    public function updateAppOrganization() {
        $this->checkAdmin();
        $this->logs(print_r($_POST, 1));

        $app_id = get_option('logon_app_id');
        $organization_id = $_POST['organization_id'];
        $app = array(
            'organization_code' => $organization_id
        );
        $client = $this->getClient();
        $response = $client->updateAppOrganization($app_id, $app);
        echo json_encode($response);
        wp_die();

}

    public function manageGetWebsiteDomains() {
        $this->checkAdmin();
        $session = $_POST['session_token'];
        $app_id = $_POST['app_id'];
        $client = $this->getGatewayClient($session);
        $response = $client->getWebsiteDomains($app_id);
        echo json_encode($response);
        wp_die();
    }

    public function manageCreateWebsiteDomain() {
        $this->checkAdmin();
        $session = $_POST['session_token'];
        $app_id = $_POST['app_id'];
        $domain = $_POST['domain'];
        $client = $this->getGatewayClient($session);
        $response = $client->createWebsiteDomain($app_id, $domain);
        echo json_encode($response);
        wp_die();
    }

    public function manageGetAppProviders() {
        $this->checkAdmin();
        $session = $_POST['session_token'];
        $app_id = $_POST['app_id'];
        $client = $this->getGatewayClient($session);
        $response = $client->getAppProviders($app_id);
        echo json_encode($response);
        wp_die();
    }
    public function manageEnableProvider() {
        $this->checkAdmin();
        $session = $_POST['session_token'];
        $app_id = $_POST['app_id'];
        $identity_provider_id = $_POST['identity_provider_id'];
        $client = $this->getGatewayClient($session);
        $response = $client->enableProvider($app_id, $identity_provider_id);
        echo json_encode($response);
        wp_die();
    }

    public function manageCreateAppSecret() {
        $this->checkAdmin();
        $session = $_POST['session_token'];
        $app_id = $_POST['app_id'];
        $name = $_POST['name'];
        $client = $this->getGatewayClient($session);
        $response = $client->createAppSecret($app_id, $name);
        echo json_encode($response);
        wp_die();
    }

    public function manageSetPrimaryAppWebsiteDomain() {
        $this->checkAdmin();
        $session = $_POST['session_token'];
        $app_id = $_POST['app_id'];
        $domain_id = $_POST['website_domain_id'];
        $client = $this->getGatewayClient($session);
        $response = $client->setPrimaryAppWebsiteDomain($app_id, $domain_id);
        echo json_encode($response);
        wp_die();
    }

    public function manageCreateApp(){
        $this->checkAdmin();
        $session = $_POST['session'];
        $name = $_POST['name'];
        $gateway_id = $_POST['gateway_id'];
        $client = $this->getManageClient($session);
        $response = $client->createApp($name, $gateway_id);
        echo json_encode($response);
        wp_die();
    }

    public function manageGetProfile() {
        $this->checkAdmin();
        $session = $_POST['session'];
        $client = $this->getManageClient($session);
        $response = $client->getProfile();
        echo json_encode($response);
        wp_die();
    }

    public function bbPageView() {
        $this->logs('bbPageView');
        $json = file_get_contents('php://input');
        $json = json_decode($json, true);
        $app_id = $json['app_id'];
        $device_id = $json['device_id'];
        $data = $json['data'];
        $referrer_url = $json['referrer_url'];

        $client = $this->getGatewayClient();
        $this->logs($_SERVER['HTTP_USER_AGENT']);
        $response = $client->bbPageView($app_id, $device_id, $data, $referrer_url);
        echo json_encode($response['body']);
        wp_die();
    }
    public function bbEngagement() {
        $json = file_get_contents('php://input');
        $json = json_decode($json, true);
        $app_id = $json['app_id'];
        $device_id = $json['device_id'];
        $page_view_id = $json['page_view_id'];
        $t = $json['t'];
        $c = $json['c'];
        $m = $json['m'];
        $s = $json['s'];
        $ga_data = $json['ga_data'];
        $segment_anonymous_id = $json['segment_anonymous_id'];
        $client = $this->getGatewayClient();
        $response = $client->bbEngagement($app_id, $device_id, $page_view_id, $t, $c, $m, $s, $ga_data, $segment_anonymous_id);
        echo json_encode($response['body']);
        wp_die();
    }

}