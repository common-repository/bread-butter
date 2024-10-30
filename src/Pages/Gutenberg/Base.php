<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages\GutenBerg;

use \BreadButter_WP_Plugin\Base\BaseController;

class Base extends BaseController {

    public $meta;

    public function __construct() {
        $this->setMeta();
    }

    public function setMeta() {
        $this->meta = array(

        );
    }

    public function getMeta() {
        return $this->meta;
    }
}