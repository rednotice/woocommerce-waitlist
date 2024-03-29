<?php

/**
 * This file is triggered on uninstall.
 * 
 * @package pxbWaitlist
 * 
 * @since 1.0.0
 */

 if (!defined('WP_UNINSTALL_PLUGIN')) {
     exit;
 }

 // Clear database.
$waitlistSubscribers = get_posts( array( 'post_type' => 'pxb_waitlist', 'numberposts' => -1 ) );

foreach ($waitlistSubscribers as $waitlistSubscriber) {
    wp_delete_post($waitlistSubscriber->ID, true);
}
