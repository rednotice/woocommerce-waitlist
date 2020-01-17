<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Base;

use \Inc\Services\FormSettings;
use \Inc\Services\MailSettings;

class Activate
{
    public static function activate(): void
    {
        flush_rewrite_rules();
        self::saveDefaultOptions();
    }

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