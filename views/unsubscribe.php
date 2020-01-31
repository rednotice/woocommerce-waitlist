<?php get_header(); ?>

<div class="wrap wpbits-waitlist-unsubscribe" style="text-align: center;">
    <h1><?php echo sanitize_text_field(get_option('wpbits_waitlist_unsubscribe_title')); ?></h1>
    <p><?php echo get_option('wpbits_waitlist_unsubscribe_message'); ?></p>
</div>

<?php get_footer(); ?>