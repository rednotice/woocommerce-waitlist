<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Api;

use \Inc\Base\Paths;

class SettingsCallbacks extends Paths
{
    public function settings(): string
    {
        return require_once($this->pluginPath .'/views/settings.php');
    }

    public function upgrade(): string 
    {
        return require_once($this->pluginPath .'/views/upgrade.php');
    }

    public function sanitizeTextField(string $input): string
    {
        return sanitize_text_field($input);
    }

    public function sanitizeHtmlTextField(string $input): string 
    {
        return wp_kses_post($input);
    }

    public function sanitizeEmail(string $input): string
    {
        if($input && !is_email($input)) {
            add_settings_error(
                'wpbits_waitlist_subscription_mail_copy',
                esc_attr( 'invalid-email' ),
                'The email address to receive a copy of the subscription success mail is not valid.'
            );
        }

        return sanitize_email($input);
    }

    public function sanitizeCheckbox(?string $input): bool 
    {
        return (isset($input) ? true : false);
    }

    public function settingsSectionForm(): void
    {
        echo 'Customize how your waitlist form will be displayed to your customers.';
    }

    public function settingsSectionValidation() {
        echo 'Customize the success and error messages your subscribers will see when submitting the form.';
    }

    public function settingsSectionCheckbox(): void 
    {
        echo 'Add an I Agree-checkbox to the subscribe form.';
    }

    public function settingsSectionMail(): void 
    {
        echo 'Customize the emails sent to your subscribers. You can use HTML and these shortcodes in the subject and the message of your mails:
        <b>{product_id}</b>,
        <b>{product_name}</b>,
        <b>{product_link}</b>,
        <b>{product_image}</b>,
        <b>{subscriber_email}</b>,
        <b>{shop_name}</b>.';
    }

    public function drawInputText(array $args): void 
    {
        $value = sanitize_text_field(get_option($args['name']));
        echo '<input 
            type="text" 
            class="regular-text ' . $args['class'] . '" 
            name="' . $args['name'] . '" 
            value="' . $value . '"
            placeholder="' . ( $args['placeholder'] ?? '' ) . '"
        >';
    }

    public function drawTextarea(array $args): void 
    {
        $text = wp_kses_post(get_option($args['name']));
        echo '<textarea 
            rows="15"
            cols="50" 
            class="regular-text ' . $args['class'] . '" 
            name="' . $args['name'] . '">'
            . $text .
        '</textarea>';
    }

    public function drawCheckbox(array $args): void
    {
        $checkbox = sanitize_text_field(get_option($args['name']));
        echo '<input 
            type="checkbox" 
            name="' . $args['name'] . '"'
            . ( $checkbox ? 'checked' : '' ) . '
        >';
    }
}
