<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

/**
 * Lets subscribers unsubcribe from the waitlist.
 * 
 * @since 1.0.0
 */
class Unsubscribe
{
    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        add_action('wp_ajax_nopriv_wpbits_unsubscribe', array($this, 'submitSubscriber'));
        add_action('wp_ajax_wpbits_unsubscribe', array($this, 'submitSubscriber'));
    }

}