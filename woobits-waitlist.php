<?php
/**
 * @package woobitsWaitlist
 * 
 * @since 1.0.0
 */

 /**
 * Plugin Name: woobits Waitlist
 * Plugin URI: https://woobits.com/plugins/waitlist
 * Description: Back in  stock  email notifications for WooCommerce.
 * Version: 1.0.0
 * Author: woobits
 * Author URI: https://woobits.com
 * Text Domain: woobits-waitlist
 * Domain Path: /languages
 * WC requires at least: 2.2.0
 * WC tested up to: 3.8
 * 
 * @package     woobitsWaitlist
 * @author      woobits
 * @copyright   2020 woobits
 * @license     GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
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

function activatewoobitsWaitlist() 
{
    Inc\Base\Activate::activate();
}
register_activation_hook(__FILE__, 'activatewoobitsWaitlist');

function deactivatewoobitsWaitlist() 
{
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivatewoobitsWaitlist');

if (class_exists('Inc\\Init') ) {
    Inc\Init::registerServices();
}
