<?php
/**
 * @package pixelbaseWaitlist
 * 
 * @since 1.0.0
 */

 /**
 * Plugin Name: PixelBase Waitlist
 * Plugin URI: https://pixelbase.co/plugins/waitlist
 * Description: Back in  stock email notifications for WooCommerce.
 * Version: 1.0.0
 * Author: pxb
 * Author URI: https://pixelbase.co
 * Text Domain: pxb-waitlist
 * Domain Path: /languages
 * WC requires at least: 2.2.0
 * WC tested up to: 3.9
 * 
 * @package     pixelbaseWaitlist
 * @author      PixelBase
 * @copyright   2020 PixelBase
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

function activatePxbWaitlist() 
{
    PixelBase\Base\Activate::activate();
}
register_activation_hook(__FILE__, 'activatePxbWaitlist');

function deactivatePxbWaitlist() 
{
    PixelBase\Base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivatePxbWaitlist');

if( class_exists('PixelBase\\Init') && in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ))) ) {
    PixelBase\Init::registerServices();
}