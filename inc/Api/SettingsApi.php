<?php
/**
 * @package pixelbaseWaitlist
 * 
 * @since 1.0.0
 */

namespace PixelBase\Api;

/**
 * Class SettingsApi.
 *
 * @since 1.0.0
 */
class SettingsApi
{
    /**
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $adminPages = array();

    /**
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $adminSubpages = array();

    /**
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $settings = array();

    /**
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $sections = array();

    /**
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $fields = array();

    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        if(!empty($this->adminPages) || !empty($this->adminSubpages)) {
            add_action('admin_menu', array($this, 'addAdminMenu'));
        }

        if(!empty($this->settings)) {
            add_action('admin_init', array($this, 'registerSettings'));
        }

        if(!empty( $this->sections)) {
            add_action('admin_init', array($this, 'registerSections'));
        }

        if(!empty( $this->fields)) {
            add_action('admin_init', array($this, 'registerFields'));
        }
    }

    /**
     * Populates the adminPages attribute.
     * 
     * @param array $pages
     * @return object $this
     */
    public function setAdminPages(array $pages): object
    {
        $this->adminPages = $pages;
        return $this;
    }

    /**
     * Populates the adminSubpages attribute.
     * 
     * @param array $pages
     * @return object $this
     */
    public function setAdminSubpages(array $pages): object
    {
        $this->adminSubpages = array_merge($this->adminSubpages, $pages);
        return $this;
    }

    /**
     * Populates the settings attribute.
     * 
     * @param array $settings
     * @return object $this
     */
    public function setSettings(array $settings): object
    {
        $this->settings = array_merge($this->settings, $settings);
        return $this;
    }

    /**
     * Populates the sections attribute.
     * 
     * @param array $sections
     * @return object $this
     */
    public function setSections(array $sections): object
    {
        $this->sections = array_merge($this->sections, $sections);
        return $this;
    }

    /**
     * Populates the fields attribute.
     * 
     * @param array $fields
     * @return object $this
     */
    public function setFields(array $fields): object
    {
        $this->fields = array_merge($this->fields, $fields);
        return $this;
    }

    /**
     * Adds a subpage to an admin page.
     * 
     * @param string $title (default: null)
     * @return object $this
     */
    public function withSubpage(string $title = null): object
    {
        if(empty( $this->adminPages ) ) {
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

    /**
     * Generates the admin menu.
     * 
     * @return void
     */
    public function addAdminMenu(): void
    {
        foreach($this->adminPages as $page) {
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

        foreach($this->adminSubpages as $page) {
            add_submenu_page(
                $page['parent_slug'],
                $page['page_title'],
                $page['menu_title'],
                $page['capability'],
                $page['menu_slug'],
                (isset($page['callback']) ? $page['callback'] : '')
            );
        }
    }

    /**
     * Registers the settings.
     * 
     * @return void
     */
    public function registerSettings(): void
    {
        foreach($this->settings as $setting) {
            register_setting( 
                $setting['option_group'], 
                $setting['option_name'],
                $setting['args']
            );
        }
    }

    /**
     * Registers the settings sections.
     * 
     * @return void
     */
    public function registerSections(): void
    {
        foreach($this->sections as $section) {
            add_settings_section( 
                $section['id'], 
                $section['title'], 
                (isset( $section['callback']) ? $section['callback'] : '' ),
                $section['page']
            );
        }
    }

    /**
     * Registers the settings fields.
     * 
     * @return void
     */
    public function registerFields() {
        foreach($this->fields as $field) {
            add_settings_field( 
                $field['id'],   
                $field['title'],    
                (isset($field['callback']) ? $field['callback'] : '' ),
                $field['page'], 
                $field['section'],  
                $field['args']
            );
        }
    }
}