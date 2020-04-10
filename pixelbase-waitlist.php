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
 * Version: 1.0.1
 * Author: PixelBase
 * Author URI: https://pixelbase.co
 * Text Domain: pxb-waitlist
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

// Plugin update checker
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/rednotice/pixelbase-waitlist',
	__FILE__,
	'pixelbase-waitlist'
);

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('403ac03febdfd126a493108f163cc4db98e6fc69');

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');