<?php
/**
 * @package breadbutter-connect
 */
/*
Plugin Name: Bread & Butter IO
Plugin URI: https://breadbutter.io
Description: Add SSO login options to your WordPress website
Version: 7.4.857
Author: Bread & Butter IO Inc.
License: GPLv2 or later
Text Domain: breadbutter

*/

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

defined('ABSPATH') or die('Warning, restricted area.');

if (!class_exists('BreadButter_WP_Plugin\\Init')) {
    if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
        require_once(dirname(__FILE__) . '/vendor/autoload.php');
    }

    register_activation_hook(__FILE__, array('BreadButter_WP_Plugin\\Base\\Activate', 'activate'));
    register_deactivation_hook(__FILE__, array('BreadButter_WP_Plugin\\Base\\Deactivate', 'deactivate'));


    if (class_exists('BreadButter_WP_Plugin\\Init')) {
        BreadButter_WP_Plugin\Init::register_services();
    }
}