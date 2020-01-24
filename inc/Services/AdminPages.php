<?php
/**
 * @package woobitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

use \Inc\Api\SettingsApi;
use \Inc\Api\SettingsCallbacks;

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
                'page_title' => 'woobits Waitlist', 
                'menu_title' => 'woobits Waitlist', 
                'capability' => 'manage_options', 
                'menu_slug' => 'woobits_waitlist',
                'icon_url' => 'dashicons-clipboard',
                'position' => 110 
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
                'parent_slug' => 'woobits_waitlist',
                'page_title' => __('Settings', 'woobits-waitlist'),
                'menu_title' => __('Settings', 'woobits-waitlist'),
                'capability' => 'manage_options',
                'menu_slug' => 'woobits_settings',
                'callback' => array( $this->callbacks, 'settings')
            ]
        ];
    }
}
