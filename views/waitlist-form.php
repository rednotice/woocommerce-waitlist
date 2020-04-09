<section class="pxb-waitlist">
    <h4 class="pxb-waitlist-title">
        <?php echo sanitize_text_field(get_option('pxb_waitlist_title')); ?>
    </h4>

    <form 
        id="pxb-waitlist-form"
        action="#" 
        method="POST" 
        data-url="<?php echo admin_url('admin-ajax.php'); ?>"
    >
        <div id="pxb-waitlist-container">
            <div class="pxb-waitlist-form-group">
                <input 
                    id="pxb-waitlist-email"
                    type="email" 
                    class=""
                    name="email" 
                    placeholder="<?php echo sanitize_text_field(get_option('pxb_waitlist_email' )); ?>"
                    form="pxb-waitlist-form"
                    aria-label="Email address"
                >
                <small class="field-msg error" data-error="invalidEmail">
                    <?php echo sanitize_text_field(get_option('pxb_waitlist_email_error')); ?>
                </small>
            </div>

            <?php if( sanitize_text_field(get_option( 'pxb_waitlist_confirmation' ) ) ) : ?>
                <div class="pxb-waitlist-checkbox-group">
                    <input 
                        type="checkbox"
                        name="confirmation"
                        id="pxb-confirmation"
                        form="pxb-waitlist-form"
                    >
                    <label for="pxb-confirmation"><?php echo stripslashes(wp_kses_post(addslashes(get_option('pxb_waitlist_confirmation_text')))); ?></label>
                    <small class="field-msg error" data-error="invalidConfirmation">
                        <?php echo sanitize_text_field(get_option('pxb_waitlist_confirmation_error')); ?>
                    </small>
                </div>
            <?php endif; ?>

            <button id="pxb-waitlist-submit" type="submit" name="submit" form="pxb-waitlist-form"><?php echo sanitize_text_field(get_option('pxb_waitlist_subscribe')); ?></button>
            <small class="field-msg js-form-submission"><?php echo sanitize_text_field(get_option('pxb_waitlist_submission')); ?></small>
            <small class="field-msg success js-form-success"><?php echo sanitize_text_field(get_option('pxb_waitlist_success')); ?></small>
            <small class="field-msg error js-form-error"><?php echo sanitize_text_field(get_option('pxb_waitlist_error')); ?></small>
            <small class="field-msg error js-already-subscribed-error"><?php echo sanitize_text_field(get_option( 'pxb_waitlist_already_subscribed_error')); ?></small>

            <input type="hidden" name="productId" value="<?php echo $product->get_id()?>" form="pxb-waitlist-form">
            <input type="hidden" name="variationId" value="<?php echo (isset($variation) ? $variation->get_id() : null) ?>" form="pxb-waitlist-form">
            <input type="hidden" name="action" value="pxb_submit_subscriber" form="pxb-waitlist-form">
        </div>
    </form>
</section>


