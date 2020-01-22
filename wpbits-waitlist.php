<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

 /**
 * Plugin Name: wpbits Waitlist
 * Plugin URI: https://wpbits.com/plugins/waitlist
 * Description: Back in  stock  email notifications for WooCommerce.
 * Version: 1.0.0
 * Author: wpbits
 * Author URI: https://wpbits.com
 * Text Domain: wpbits-waitlist
 * Domain Path: /languages
 * WC requires at least: 2.2.0
 * WC tested up to: 3.8
 * 
 * @package     wpbitsWaitlist
 * @author      wpbits
 * @copyright   2020 wpbits
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
