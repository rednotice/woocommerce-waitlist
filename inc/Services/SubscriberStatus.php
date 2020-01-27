<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

/**
 * Is used to register the waitlist custom post types statuses and change the status of the subscribers.
 *
 * @since 1.0.0
 */
class SubscriberStatus
{
    /**
	 * Subscriber statuses.
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $statuses;

        /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        $this->setStatuses();

        if(!empty($this->statuses)) {
            add_action('init', array($this, 'registerStatuses'), 10);
        }
    }

    /**
	 * Populates the statuses attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setStatuses(): void 
    {
        $this->statuses = [
            [
                'post_status' => 'wpbits_subscribed',
                'args' => [
                    'label' => __('Subscribed', 'wpbits-waitlist'),
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'show_in_admin_status_list' =>  true,
                    'label_count' => _n_noop(
                        'Subscribed <span class="count">(%s)</span>', 
                        'Subscribed <span class="count">(%s)</span>',
                        'wbits-waitlist'
                    ),
                ]
            ],
            [
                'post_status' => 'wpbits_unsubscribed',
                'args' => [
                    'label' => __('Unsubscribed', 'wpbits-waitlist'),
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'show_in_admin_status_list' =>  true,
                    'label_count' => _n_noop(
                        'Unsubscribed <span class="count">(%s)</span>', 
                        'Unsubscribed <span class="count">(%s)</span>',
                        'wbits-waitlist'
                    ),
                ]
            ],
            [
                'post_status' => 'wpbits_mailsent',
                'args' => [
                    'label' => __('Mail Sent', 'wpbits-waitlist'),
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'show_in_admin_status_list' =>  true,
                    'label_count' => _n_noop(
                        'Mail Sent <span class="count">(%s)</span>', 
                        'Mail Sent <span class="count">(%s)</span>',
                        'wbits-waitlist'
                    ),
                ]
            ],
            [
                'post_status' => 'wpbits_failed',
                'args' => [
                    'label' => __('Failed', 'wpbits-waitlist'),
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'show_in_admin_status_list' =>  true,
                    'label_count' => _n_noop(
                        'Failed <span class="count">(%s)</span>',
                        'Failed <span class="count">(%s)</span>',
                        'wbits-waitlist'
                    ),
                ]
            ]
        ];
    }

    /**
	 * Registers the statuses.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function registerStatuses(): void 
    {
        foreach($this->statuses as $status) {
            register_post_status($status['post_status'], $status['args']);
        }
    }

    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @param string $status
     * @return string New subscriber status.
     */
    public function updateStatus(int $subscriberId, string $status): string
    {
        // Check if $status contained in Self:statuses
        is_array($status, $this->statuses, true);

        $subscriber = array(
            'ID' => $subscriberId,
            'post_status' => 'wpbits_mailsent'
        );
        wp_update_post($subscriber);
        update_post_meta($subscriberId, '_wpbitswaitlist_mailsent_at', date('Y-m-d H:i:s'));
        return $subscriber['post_status'];
    }
}