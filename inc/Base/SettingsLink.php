<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Base;

use \Inc\Base\Paths;

class SettingsLink extends Paths
{
    public function register(): void 
    {
        add_filter('plugin_action_links_' . $this->plugin, array($this, 'generateSettingsLink'));
    }

    public function generateSettingsLink($links): array
    {
        $settingsLink = '<a href="admin.php?page=wpbits_waitlist">Settings</a>';
        $links[] = $settingsLink;
        return $links;
    }

}