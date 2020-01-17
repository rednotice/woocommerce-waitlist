<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Base;

use \Inc\Base\Paths;

class Enqueue extends Paths
{
    public function register()
    {
        add_action('admin_enqueue_scripts', array( $this, 'enqueueAdminScripts'), 10);
        add_action('wp_enqueue_scripts', array( $this, 'enqueueFrontEndScripts'), 10);
    }

    public function enqueueAdminScripts()
    {
        // wp_enqueue_style( 'bootstrap', $this->pluginUrl . 'assets/css/bootstrap.css' );
        wp_enqueue_style('adminStyle', $this->pluginUrl . 'assets/css/admin.css');
        wp_enqueue_script('adminScript', $this->pluginUrl . 'assets/js/admin.js');
    }

    public function enqueueFrontEndScripts()
    {
        // wp_enqueue_style( 'bootstrap', $this->pluginUrl . 'assets/css/bootstrap.css' );
        wp_enqueue_style('waitlistFormStyle', $this->pluginUrl . 'assets/css/form.css');
        wp_enqueue_script('waitlistFormScript', $this->pluginUrl . 'assets/js/form.js');
    }
}