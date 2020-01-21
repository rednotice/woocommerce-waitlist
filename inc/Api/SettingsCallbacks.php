<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Api;

use \Inc\Base\Paths;

/**
 * Class contains all settings callback functions.
 *
 * @since 1.0.0
 */
class SettingsCallbacks extends Paths
{
    /**
     * @since 1.0.0
     * 
     * @return string Settings view.
     */
    public function settings(): string
    {
        return require_once($this->pluginPath .'/views/settings.php');
    }

    /**
     * @since 1.0.0
     * 
     * @return string Upgrade view.
     */
    public function upgrade(): string 
    {
        return require_once($this->pluginPath .'/views/upgrade.php');
    }

    /**
     * @since 1.0.0
     * 
     * @param string $input
     * @return string $sanitizedInput 
     */
    public function sanitizeTextField(string $input): string
    {
        return sanitize_text_field($input);
    }

    /**
     * @since 1.0.0
     * 
     * @param string $input
     * @return string $sanitizedInput 
     */
    public function sanitizeHtmlTextField(string $input): string 
    {
        return wp_kses_post($input);
    }

    /**
     * @since 1.0.0
     * 
     * @param string $input
     * @return string $sanitizedInput 
     */
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

    /**
     * @since 1.0.0
     * 
     * @param string|null $input
     * @return bool
     */
    public function sanitizeCheckbox(?string $input): bool 
    {
        return (isset($input) ? true : false);
    }

    /**
     * @since 1.0.0
     * 
     * @return void
     */
    public function settingsSectionForm(): void
    {
        echo 'Customize how your waitlist form will be displayed to your customers.';
    }

    /**
     * @since 1.0.0
     * 
     * @return void
     */
    public function settingsSectionValidation() {
        echo 'Customize the success and error messages your subscribers will see when submitting the form.';
    }

    /**
     * @since 1.0.0
     * 
     * @return void
     */
    public function settingsSectionCheckbox(): void 
    {
        echo 'Add an I Agree-checkbox to the subscribe form.';
    }

    /**
     * @since 1.0.0
     * 
     * @return void
     */
    public function settingsSectionMail(): void 
    {
        echo 'Customize the emails sent to your subscribers. You can use these shortcodes in the subject and the message of your mails:
        <b>{line_break}</b>,
        <b>{product_id}</b>,
        <b>{product_name}</b>,
        <b>{product_link}</b>,
        <b>{product_image}</b>,
        <b>{subscriber_email}</b>,
        <b>{shop_name}</b>.';
    }

    /**
     * @since 1.0.0
     * 
     * @param array $args
     * @return void
     */
    public function drawInputText(array $args): void 
    {
        $value = sanitize_text_field(get_option($args['name']));
        $placeholder = (isset($args['placeholder']) ? 'placeholder="' . $args['placeholder'] . '"' : '');

        echo '<input 
            id="' . $args['name'] . '" 
            name="' . $args['name'] . '" 
            type="text" 
            class="regular-text ' . $args['class'] . '" 
            value="' . $value . '"'
            . $placeholder .
        '>';
    }

    /**
     * @since 1.0.0
     * 
     * @param array $args
     * @return void
     */
    public function drawTextarea(array $args): void 
    {
        // $text = wp_kses_post(get_option($args['name']));
        $text = get_option($args['name']);
        echo '<textarea 
            id="' . $args['name'] . '" 
            rows="15"
            cols="50" 
            class="regular-text ' . $args['class'] . '" 
            name="' . $args['name'] . '"
        >' . $text . '
        </textarea>';
    }

    /**
     * @since 1.0.0
     * 
     * @param array $args
     * @return void
     */
    public function drawCheckbox(array $args): void
    {
        $checkbox = sanitize_text_field(get_option($args['name']));
        echo '<input 
            type="checkbox" 
            id="' . $args['name'] . '" 
            name="' . $args['name'] . '"'
            . ( $checkbox ? 'checked' : '' ) . '
        >';
    }
}
