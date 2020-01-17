<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Services;

use \Inc\Api\SettingsApi;
use \Inc\Api\Callbacks\SettingsCallbacks;

class AdminPages
{
    public $settings;

    public $callbacks;

    public $pages = array();

    public $subpages = array();

    public function register() {
        $this->settings = new SettingsApi();
        $this->callbacks = new SettingsCallbacks();

        $this->setPages();
        $this->setSubpages();

        $this
            ->settings
            ->setAdminPages( $this->pages )
            ->setAdminSubpages( $this->subpages )
            ->register();
    }

    public function setPages() {
        $this->pages = [
            [
                'page_title' => 'wpbits Waitlist', 
                'menu_title' => 'wpbits Waitlist', 
                'capability' => 'manage_options', 
                'menu_slug' => 'wpbits_waitlist',
                'icon_url' => 'dashicons-clipboard',
                'position' => 110 
            ]
        ];  
    }

    public function setSubpages() {

        $this->subpages = [
            [
                'parent_slug' => 'wpbits_waitlist',
                'page_title' => 'Settings',
                'menu_title' => 'Settings',
                'capability' => 'manage_options',
                'menu_slug' => 'wpbits_settings',
                'callback' => array( $this->callbacks, 'settings')
            ]
        ];
    }

}