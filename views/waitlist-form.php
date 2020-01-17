<section class="wpbits-waitlist">
    <h3>
        <?php echo get_option( 'wpbits_waitlist_title_label' ); ?>
    </h3>

    <form id="wpbits-waitlist-form" action="#" method="POST" data-url="<?php echo admin_url( 'admin-ajax.php' ); ?>">
        <div id="wpbits-waitlist-container">
            <div class="form-group email-group">
                <input 
                    type="email" 
                    class="form-field"
                    name="email" 
                    placeholder="<?php echo get_option( 'wpbits_waitlist_email_label' ); ?>"
                    form="wpbits-waitlist-form"
                >
                <small class="field-msg error" data-error="invalidEmail">
                    <?php echo get_option( 'wpbits_waitlist_email_error_label' ); ?>
                </small>
            </div>

            <?php if( get_option( 'wpbits_waitlist_confirmation_label' ) )
            echo '
            <div class="form-group">
                <div class="checkbox-group">
                    <input 
                        type="checkbox" 
                        class="form-field"
                        name="confirmation"
                        form="wpbits-waitlist-form"
                    >
                    <label for="confirmation">' . get_option( 'wpbits_waitlist_confirmation_text_label' ) . '</label>
                </div>
                <small class="field-msg error" data-error="invalidConfirmation">'
                   . get_option( 'wpbits_waitlist_confirmation_error_label' ) .
                '</small>
            </div>
            ' ; ?>

            

            <button type="submit" name="submit" form="wpbits-waitlist-form"><?php echo get_option( 'wpbits_waitlist_subscribe_label' ); ?></button>
            <small class="field-msg js-form-submission"><?php echo get_option( 'wpbits_waitlist_submission_label' ); ?></small>
            <small class="field-msg success js-form-success"><?php echo get_option( 'wpbits_waitlist_success_label' ); ?></small>
            <small class="field-msg error js-form-error"><?php echo get_option( 'wpbits_waitlist_error_label' ); ?></small>

            <input type="hidden" name="productId" value="<?php echo $product->get_id()?>" form="wpbits-waitlist-form">
            <input type="hidden" name="variationId" form="wpbits-waitlist-form">
            <input type="hidden" name="action" value="wpbits_submit_subscriber" form="wpbits-waitlist-form">
</div>
    </form>
</section>


