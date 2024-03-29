<?php
/**
 * @package pixelbaseWaitlist
 * 
 * @since 1.0.0
 */

namespace PixelBase\Services;

use \PixelBase\Base\Paths;
use \PixelBase\Api\SettingsApi;
use \PixelBase\Api\SettingsCallbacks;

/**
 * Creates the admin pages.
 *
 * @since 1.0.0
 */
class AdminPages
{
    /**
	 * Instance of the SettingsApi class.
	 *
	 * @since 1.0.0
     * 
	 * @var object
	 */
    public $settings;

    /**
	 * Instance of the SettingsCallbacks class.
	 *
	 * @since 1.0.0
     * 
	 * @var object
	 */
    public $callbacks;

    /**
	 * Admin pages.
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $pages = array();

    /**
	 * Admin subpages.
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $subpages = array();

    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        $this->settings = new SettingsApi();
        $this->callbacks = new SettingsCallbacks();

        $this->setPages();
        $this->setSubpages();

        $this
            ->settings
            ->setAdminPages($this->pages)
            ->setAdminSubpages($this->subpages)
            ->register();

        add_action('admin_enqueue_scripts', array( $this, 'enqueueAdminScripts'), 10);
    }

    /**
	 * Populates the pages attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setPages(): void
    {
        $this->pages = [
            [
                'page_title' => 'Waitlist', 
                'menu_title' => 'Waitlist', 
                'capability' => 'manage_options', 
                'menu_slug' => 'pxb_waitlist',
                'icon_url' => 'dashicons-clipboard',
                'position' => 56 
            ]
        ];  
    }

    /**
	 * Populates the subpages attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setSubpages(): void
    {
        $this->subpages = [
            [
                'parent_slug' => 'pxb_waitlist',
                'page_title' => __('Settings', 'pxb-waitlist'),
                'menu_title' => __('Settings', 'pxb-waitlist'),
                'capability' => 'manage_options',
                'menu_slug' => 'pxb_settings',
                'callback' => array( $this->callbacks, 'settings')
            ]
        ];
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
        $paths = new Paths();
        wp_enqueue_style('adminStyle', $paths->pluginUrl . 'assets/css/admin.css');
    }
}
