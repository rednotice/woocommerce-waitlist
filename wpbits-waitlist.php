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
 * WC tested up to: 3.9
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
    exit();
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once( dirname(__FILE__) . '/vendor/autoload.php');
}

function activatewpbitsWaitlist() 
{
    Inc\Base\Activate::activate();
}
register_activation_hook(__FILE__, 'activatewpbitsWaitlist');

function deactivatewpbitsWaitlist() 
{
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivatewpbitsWaitlist');

if(class_exists('Inc\\Init') && in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' )))) {
    Inc\Init::registerServices();
}