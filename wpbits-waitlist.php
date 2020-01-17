<?php
/**
 * @package wpbitsWaitlist
 */

 /*
 Plugin Name: wpbits Waitlist
 Plugin URI: https://wpbits.com/plugins/waitlist
 Description: Back in  stock  email notifications for WooCommerce.
 Version: 1.0.0
 Author: wpbits
 Author URI: https://wpbits.com
 License: GPLv2 or later
 Text Domain: wpbits-waitlist
 */

 /*
 INSERT LICENSE TEXT HERE
 */

if (!defined('ABSPATH')) {
    exit;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once( dirname(__FILE__) . '/vendor/autoload.php');
}

function activateWpbitsWaitlist() 
{
    Inc\Base\Activate::activate();
}
register_activation_hook(__FILE__, 'activateWpbitsWaitlist');

function deactivateWpbitsWaitlist() 
{
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivateWpbitsWaitlist');

if (class_exists('Inc\\Init') ) {
    Inc\Init::registerServices();
}