<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Base;

/**
 * Deactivation class.
 * 
 * @since 1.0.0
 */
class Deactivate
{
    /**
	 * Is triggered on the deactivation of the plugin.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public static function deactivate(): void
    {
        flush_rewrite_rules();
    }
}