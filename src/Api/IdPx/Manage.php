<?php
/**
 * Created by PhpStorm.
 * User: hlee
 * Date: 2019-02-25
 * Time: 2:17 PM
 */

namespace BreadButter_WP_Plugin\Api\IdPx;

class Manage {

    private $connection;

    private $options;

    const ROUTE_AUTHENTICATIONCALLBACK = 'authcallback';


    public function __construct($connection, $options = array()) {
        if (!$this->connection) {
            $this->connection = $connection;
        }
        $this->options = $options;
    }

    public function getAuthenticationCallback($authentication_token) {
        $cmd = self::ROUTE_AUTHENTICATIONCALLBACK;
        return $this->connection->redirect($cmd, array(
            "authentication_token" => $authentication_token
        ));
    }
}