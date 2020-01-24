<div class="wrap">
    <h1>Settings</h1>
    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php 
            settings_fields( 'woobits_waitlist_option_group' ); 
            do_settings_sections( 'woobits_settings' );
            submit_button();
        ?>
    </form>
</div>