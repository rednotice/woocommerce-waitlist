<?php
/**
 * @package woobitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

use \Inc\Base\Paths;

/**
 * Enqueues the scripts and styleseets for the plugin.
 * 
 * @since 1.0.0
 */
class Enqueue extends Paths
{
    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        add_action('admin_enqueue_scripts', array( $this, 'enqueueAdminScripts'), 10);
        add_action('wp_enqueue_scripts', array( $this, 'enqueueFrontEndScripts'), 10);
    }

    /**
	 * Enqueues all scripts and stylesheets for the admin pages.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function enqueueAdminScripts(): void
    {
        // wp_enqueue_style( 'bootstrap', $this->pluginUrl . 'assets/css/bootstrap.css' );
        wp_enqueue_style('adminStyle', $this->pluginUrl . 'assets/css/admin.css');
        // wp_enqueue_script('adminScript', $this->pluginUrl . 'assets/js/admin.js');
    }

    /**
	 * Enqueues all scripts and stylesheets for the front end.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function enqueueFrontEndScripts(): void
    {
        // wp_enqueue_style( 'bootstrap', $this->pluginUrl . 'assets/css/bootstrap.css' );
        wp_enqueue_style('waitlistFormStyle', $this->pluginUrl . 'assets/css/form.css');
        wp_enqueue_script('waitlistFormScript', $this->pluginUrl . 'assets/js/form.js');
    }
}