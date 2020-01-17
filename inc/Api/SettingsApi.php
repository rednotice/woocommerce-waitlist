<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Api;

class SettingsApi
{
    public $adminPages = array();

    public $adminSubpages = array();

    public $settings = array();

    public $sections = array();

    public $fields = array();

    public function register() {
        if( ! empty( $this->adminPages ) || ! empty( $this->adminSubpages ) ) {
            add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
        }

        if( ! empty( $this->settings ) ) {
            add_action( 'admin_init', array( $this, 'registerSettings' ) );
        }

        if( ! empty( $this->sections ) ) {
            add_action( 'admin_init', array( $this, 'registerSections' ) );
        }

        if( ! empty( $this->fields ) ) {
            add_action( 'admin_init', array( $this, 'registerFields' ) );
        }
    }

    public function setAdminPages( array $pages ) {
        $this->adminPages = $pages;
        return $this;
    }

    public function setAdminSubpages( array $pages ) {
        $this->adminSubpages = array_merge( $this->adminSubpages, $pages );
        return $this;
    }

    public function setSettings( array $settings ) {
        $this->settings = array_merge( $this->settings, $settings );
        return $this;
    }

    public function setSections( array $sections ) {
        $this->sections = array_merge( $this->sections, $sections );
        return $this;
    }

    public function setFields( array $fields ) {
        $this->fields = array_merge( $this->fields, $fields );
        return $this;
    }

    public function withSubpage ( string $title = null ) {
        if( empty( $this->adminPages ) ) {
            return $this;
        }

        $adminPage = $this->adminPages[0];

        $subpages = [
            [
                'parent_slug' => $adminPage['menu_slug'],
                'page_title' => $adminPage['page_title'],
                'menu_title' => ($title) ? $title : $adminPage['menu_title'], 
                'capability' => $adminPage['capability'],
                'menu_slug' => $adminPage['menu_slug'],
                'callback' => $adminPage['callback'],
            ]
        ];

        $this->adminSubpages = $subpages;
        return $this;
    }

    public function addAdminMenu() {
        foreach( $this->adminPages as $page ) {
            add_menu_page(
                $page['page_title'],
                $page['menu_title'],
                $page['capability'],
                $page['menu_slug'],
                ( isset( $page['callback'] ) ? $page['callback'] : '' ),
                $page['icon_url'],
                $page['position']
            );
        }

        foreach( $this->adminSubpages as $page ) {
            add_submenu_page(
                $page['parent_slug'],
                $page['page_title'],
                $page['menu_title'],
                $page['capability'],
                $page['menu_slug'],
                ( isset( $page['callback'] ) ? $page['callback'] : '' )
            );
        }
    }

    public function registerSettings() {
        foreach( $this->settings as $setting ) {
            register_setting( 
                $setting['option_group'], 
                $setting['option_name'],
                $setting['args']
            );
        }
    }

    public function registerSections() {
        foreach( $this->sections as $section ) {
            add_settings_section( 
                $section['id'], 
                $section['title'], 
                ( isset( $section['callback'] ) ? $section['callback'] : '' ),
                $section['page']
            );
        }
    }

    public function registerFields() {
        foreach( $this->fields as $field ) {
            add_settings_field( 
                $field['id'],   
                $field['title'],    
                ( isset( $field['callback'] ) ? $field['callback'] : '' ),
                $field['page'], 
                $field['section'],  
                $field['args']
            );
        }
    }
}