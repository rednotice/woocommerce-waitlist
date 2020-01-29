<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

use \Inc\Base\Paths;

/**
 * Adds a link to the settings on the plugin page.
 * 
 * @since 1.0.0
 */
class SettingsLink
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
        add_filter('plugin_action_links_' . $this->paths->plugin, array($this, 'generateSettingsLink'));
    }

    /**
	 * Generates the setting link and attaches it to the links array.
	 *
	 * @since 1.0.0
     * 
     * @param array $links All plugin action links.
	 * @return array $links Plugin action links including the settings link.
	 */
    public function generateSettingsLink(array $links): array
    {
        $label = __('Settings', 'wpbits-waitlist');
        $settingsLink = '<a href="admin.php?page=wpbits_waitlist">' . $label . '</a>';
        $links[] = $settingsLink;
        return $links;
    }

}