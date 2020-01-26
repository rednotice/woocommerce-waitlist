<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

use \Inc\Base\Paths;
use \Inc\Base\Helpers;

/**
 * Lets subscribers unsubcribe from the waitlist.
 * 
 * @since 1.0.0
 */
class Unsubscribe extends Paths
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
        add_action('init', array($this, 'unsubscribe'), 10);
        add_filter('init', array($this, 'displayConfirmationPage'), 10);
    }

    /**
	 * Generates the url to unsubscribe.
	 *
	 * @since 1.0.0
     * 
     * @param string $subscriberEmail
	 * @return string Unsubscribe url.
	 */
    public static function generateUrl(string $subscriberEmail): string
    {
        $nonce = wp_create_nonce('goodbye_' . $subscriberEmail);
        return get_site_url() . "/?post_type=wpbitswaitlist&action=wpbits_user_unsubscribe&email={$subscriberEmail}&_wpnonce={$nonce}";
    }

    /**
	 * Is triggered when a subscriber clicks on the unsubscribe link in a mail. 
	 *
	 * @since 1.0.0
     * 
	 * @return bool
	 */
    public function unsubscribe(): bool
    {
        if(!isset($_GET['action']) || $_GET['action'] !== 'wpbits_user_unsubscribe') {
            return false;
        }

        $subscriberEmail = $_GET['email'];
        $nonce = $_GET['_wpnonce'];
        if(!wp_verify_nonce($nonce, 'goodbye_' . $subscriberEmail)) {
            return false;
        }

        $subscriberIds = Helpers::getSubscribersByEmail($subscriberEmail, 'wpbits_subscribed', 'ids');
        
        if(!$subscriberIds) {
            return false;
        }

        foreach($subscriberIds as $subscriberId) {
            Helpers::setStatusToUnsubscribed($subscriberId);
        }

        wp_redirect('/?post_type=wpbitswaitlist&wpbits_goodbye');
        exit();
    }

    /**
	 * The subscriber is redirected here after he was unsubscribed successfully. 
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function displayConfirmationPage(): void
    {
        if(isset($_GET['wpbits_goodbye'])) {
            require $this->pluginPath . '/views/unsubscribe.php';
            exit();
        }
    }
}