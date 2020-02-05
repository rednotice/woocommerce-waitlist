<section class="wpbits-waitlist">
    <h4 class="wpbits-waitlist-title">
        <?php echo sanitize_text_field(get_option('wpbits_waitlist_title')); ?>
    </h4>

    <form 
        id="wpbits-waitlist-form"
        action="#" 
        method="POST" 
        data-url="<?php echo admin_url('admin-ajax.php'); ?>"
    >
        <div id="wpbits-waitlist-container">
            <div class="form-group email-group">
                <input 
                    id="wpbits-waitlist-email"
                    type="email" 
                    class="form-field"
                    name="email" 
                    placeholder="<?php echo sanitize_text_field(get_option('wpbits_waitlist_email' )); ?>"
                    form="wpbits-waitlist-form"
                    aria-label="Email address"
                >
                <small class="field-msg error" data-error="invalidEmail">
                    <?php echo sanitize_text_field(get_option('wpbits_waitlist_email_error')); ?>
                </small>
            </div>

            <?php if(sanitize_text_field(get_option('wpbits_waitlist_confirmation')))
            echo '
            <div class="form-group">
                <div class="checkbox-group">
                    <input 
                        type="checkbox" 
                        class="form-field"
                        name="confirmation"
                        id="wpbits-confirmation"
                        form="wpbits-waitlist-form"
                    >
                    <label for="wpbits-confirmation">' . get_option('wpbits_waitlist_confirmation_text') . '</label>
                </div>
                <small class="field-msg error" data-error="invalidConfirmation">'
                   . sanitize_text_field(get_option('wpbits_waitlist_confirmation_error')) .
                '</small>
            </div>
            ' ; ?>

            <button id="wpbits-waitlist-submit" type="submit" name="submit" form="wpbits-waitlist-form"><?php echo sanitize_text_field(get_option('wpbits_waitlist_subscribe')); ?></button>
            <small class="field-msg js-form-submission"><?php echo sanitize_text_field(get_option('wpbits_waitlist_submission')); ?></small>
            <small class="field-msg success js-form-success"><?php echo sanitize_text_field(get_option('wpbits_waitlist_success')); ?></small>
            <small class="field-msg error js-form-error"><?php echo sanitize_text_field(get_option('wpbits_waitlist_error')); ?></small>
            <small class="field-msg error js-already-subscribed-error"><?php echo sanitize_text_field(get_option( 'wpbits_waitlist_already_subscribed_error')); ?></small>

            <input type="hidden" name="productId" value="<?php echo $product->get_id()?>" form="wpbits-waitlist-form">
            <input type="hidden" name="variationId" value="<?php echo (isset($variation) ? $variation->get_id() : null) ?>" form="wpbits-waitlist-form">
            <input type="hidden" name="action" value="wpbits_submit_subscriber" form="wpbits-waitlist-form">
        </div>
    </form>
</section>


