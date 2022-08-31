<?php

/**
 * @package ApocalypstarPlugin
 */

 /*
 Plugin Name: Apocalypstar Plugin
 Plugin URI: http://localhost
 Description: This is the plugin for apocalypstar website.
 Version: 1.0.0
 Author: Apocalypstar
 Author URI: http://localhost
 License: GPLv2 or later
 Text Domain: Apocalypstar Plugin
 */

defined('ABSPATH') or die('Access Denied!');

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}


function activate_apocalypstar_plugin() {
    Inc\Base\Activate::activate();
}

function deactivation_apocalypstar_plugin() {
    Inc\Base\Deactivate::deactivate();
}

register_activation_hook(__FILE__, 'activate_apocalypstar_plugin');
register_deactivation_hook(__FILE__, 'deactivation_apocalypstar_plugin');

if (class_exists('Inc\\Init')) {
    Inc\Init::register_services();
}