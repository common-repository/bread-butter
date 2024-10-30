<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages\Features;

use \BreadButter_WP_Plugin\Base\BaseController;
use \BreadButter_WP_Plugin\Api\Callbacks\AdminCallbacks;

class Base extends BaseController {

    public $callbacks;
    public $settings;
    public $sections;
    public $fields;

    public function __construct() {
        $this->callbacks = new AdminCallbacks();
        $this->setSettings();
        $this->setSections();
        $this->setFields();
    }

    public function setSettings() {
        $this->settings = array();
    }

    public function setSections() {
        $this->sections = array();
    }

    public function setFields() {
        $this->fields = array();
    }

    public function getSettings() {
        return $this->settings;
    }

    public function getSections() {
        return $this->sections;
    }

    public function getFields() {
        return $this->fields;
    }
}