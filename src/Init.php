<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin;

final class Init {

    public static function get_services() {
        return [
            Base\Session::class,
            Pages\Admin::class,
            Pages\LoginForm::class,
            Pages\LoginTemplate::class,
            Pages\LoginAuthorize::class,
            Pages\Block::class,
            Base\GatingContent::class,
            Base\Newsletter::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Base\Shortcode::class,
            Base\Ajax::class,
            Base\ContactUs::class
        ];

    }

    public static function unregister_services() {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'unregister')) {
                $service->unregister();
            }
        }
    }

    public static function register_services() {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    private static function instantiate($class) {
        $service = new $class();
        return $service;
    }
}