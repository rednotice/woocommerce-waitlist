<?php
/**
 * @package pixelbaseWaitlist
 * 
 * @since 1.0.0
 */

namespace PixelBase\Services;

use \PixelBase\Base\Paths;

/**
 * Enqueues the scripts and styleseets for the plugin.
 * 
 * @since 1.0.0
 */
class Enqueue
{
    /**
     * Instance of the Paths class.
     * 
     * @since 1.0.0
     * 
     * @var object
     */
    public $paths;

    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        $this->paths = new Paths();

        add_action('admin_enqueue_scripts', array( $this, 'enqueueAdminScripts'), 10);
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
        wp_enqueue_style('adminStyle', $this->paths->pluginUrl . 'assets/css/admin.css');
    }
}