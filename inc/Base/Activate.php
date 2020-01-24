<?php
/**
 * @package woobitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Base;

use \Inc\Services\FormSettings;
use \Inc\Services\MailSettings;

/**
 * Activation class.
 * 
 * @since 1.0.0
 */
class Activate
{
    /**
	 * Is triggered on the activation of the plugin.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public static function activate(): void
    {
        flush_rewrite_rules();
        self::saveDefaultOptions();
    }

    /**
	 * Stores the default form settings and mail settings
     * into the database on activattion.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public static function saveDefaultOptions(): void 
    {
        $formSettings = new FormSettings;
        $mailSettings = new MailSettings;

        foreach($formSettings->options as $option) {
            if (!get_option($option['name'])) {
                update_option($option['name'], $option['defaultValue']);
            }
        }

        foreach( $mailSettings->options as $option) {
            if (!get_option( $option['name'])) {
                update_option( $option['name'], $option['defaultValue']);
            }
        }
    }
}