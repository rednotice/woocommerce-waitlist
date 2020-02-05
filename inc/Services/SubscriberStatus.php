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
     * Constructor
     * 
     * @since 1.0.0
     */
    public function __construct() 
    {
        $this->setStatuses();
    }

    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
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
                'name' => 'wpbits_subscribed',
                'label' => 'Subscribed', 
                'textdomain' => 'wpbits-waitlist',
                'color' => 'blue'
            ],
            [
                'name' => 'wpbits_unsubscribed',
                'label' => 'Unsubscribed', 
                'textdomain' => 'wpbits-waitlist',
                'color' => 'grey'
            ],
            [
                'name' => 'wpbits_mailsent',
                'label' => 'Mail Sent', 
                'textdomain' => 'wpbits-waitlist',
                'color' => 'green'
            ],
            [
                'name' => 'wpbits_failed',
                'label' => 'Failed', 
                'textdomain' => 'wpbits-waitlist',
                'color' => 'red'
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
            $args = [
                'label' => __($status['label'], $status['textdomain']),
                'public' => true,
                'exclude_from_search' => false,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'show_in_admin_status_list' =>  true,
                'label_count' => _n_noop(
                    $status['label'] . ' <span class="count">(%s)</span>', 
                    $status['label'] . ' <span class="count">(%s)</span>',
                    $status['textdomain']
                ),
            ];

            register_post_status($status['name'], $args);
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
        if(!in_array($status, array_column($this->statuses, 'name'), true)) {
            status_header(404);
            nocache_headers();
            include(get_query_template('404'));
            exit();
        }

        // $subscriber = array(
        //     'ID' => $subscriberId
        // );
        // wp_update_post($subscriber);

        update_post_meta($subscriberId, '_wpbitswaitlist_status', $status);

        if($status === 'wpbits_subscribed') {
            update_post_meta($subscriberId, '_wpbitswaitlist_subscribed_at', date('Y-m-d H:i:s'));
        }

        if($status === 'wpbits_mailsent') {
            update_post_meta($subscriberId, '_wpbitswaitlist_mailsent_at', date('Y-m-d H:i:s'));
        }

        return $status;
    }
}
