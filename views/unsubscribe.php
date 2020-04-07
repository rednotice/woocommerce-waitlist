<?php get_header(); ?>

<div class="wrap pxb-waitlist-unsubscribe" style="text-align: center;">
    <h3><?php echo sanitize_text_field(get_option('pxb_waitlist_unsubscribe_title')); ?></h3>
    <p><?php echo get_option('pxb_waitlist_unsubscribe_message'); ?></p>
</div>

<?php get_footer(); ?>