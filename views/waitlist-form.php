<section class="woobits-waitlist">
    <h3>
        <?php echo sanitize_text_field(get_option('woobits_waitlist_title')); ?>
    </h3>

    <form 
        id="woobits-waitlist-form"
        action="#" 
        method="POST" 
        data-url="<?php echo admin_url( 'admin-ajax.php' ); ?>"
    >
        <div id="woobits-waitlist-container">
            <div class="form-group email-group">
                <input 
                    type="email" 
                    class="form-field"
                    name="email" 
                    placeholder="<?php echo sanitize_text_field(get_option('woobits_waitlist_email' )); ?>"
                    form="woobits-waitlist-form"
                    aria-label="Email address"
                >
                <small class="field-msg error" data-error="invalidEmail">
                    <?php echo sanitize_text_field(get_option('woobits_waitlist_email_error')); ?>
                </small>
            </div>

            <?php if(sanitize_text_field(get_option('woobits_waitlist_confirmation')))
            echo '
            <div class="form-group">
                <div class="checkbox-group">
                    <input 
                        type="checkbox" 
                        class="form-field"
                        name="confirmation"
                        id="woobits-confirmation"
                        form="woobits-waitlist-form"
                    >
                    <label for="woobits-confirmation">' . sanitize_text_field(get_option('woobits_waitlist_confirmation_text')) . '</label>
                </div>
                <small class="field-msg error" data-error="invalidConfirmation">'
                   . sanitize_text_field(get_option('woobits_waitlist_confirmation_error')) .
                '</small>
            </div>
            ' ; ?>

            

            <button type="submit" name="submit" form="woobits-waitlist-form"><?php echo sanitize_text_field(get_option('woobits_waitlist_subscribe')); ?></button>
            <small class="field-msg js-form-submission"><?php echo sanitize_text_field(get_option('woobits_waitlist_submission')); ?></small>
            <small class="field-msg success js-form-success"><?php echo sanitize_text_field(get_option('woobits_waitlist_success')); ?></small>
            <small class="field-msg error js-form-error"><?php echo sanitize_text_field(get_option('woobits_waitlist_error')); ?></small>
            <small class="field-msg error js-already-subscribed-error"><?php echo sanitize_text_field(get_option( 'woobits_waitlist_already_subscribed_error')); ?></small>

            <input type="hidden" name="productId" value="<?php echo $product->get_id()?>" form="woobits-waitlist-form">
            <input type="hidden" name="variationId" form="woobits-waitlist-form">
            <input type="hidden" name="action" value="woobits_submit_subscriber" form="woobits-waitlist-form">
        </div>
    </form>
</section>


