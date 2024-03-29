<?php
/**
 * @package pixelbaseWaitlist
 * 
 * @since 1.0.0
 */

namespace PixelBase\Api;

use \PixelBase\Base\Paths;

/**
 * Class contains all settings callback functions.
 *
 * @since 1.0.0
 */
class SettingsCallbacks
{
    /**
     * Instance of the Paths class.
     * 
     * @since 1.0.0
     * 
     * @var object
     */
    public $paths;

    /**
     * Constructor.
     * 
     * @since 1.0.0
     * 
     */
    public function __construct()
    {
        $this->paths = new Paths();
    }

    /**
     * @since 1.0.0
     * 
     * @return string Settings view.
     */
    public function settings(): string
    {
        return require_once($this->paths->pluginPath .'/views/settings.php');
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
        return stripslashes(wp_kses_post(addslashes($input)));
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
                'pxb_waitlist_subscription_mail_copy',
                esc_attr( 'invalid-email' ),
                __(
                    'The email address to receive a copy of the subscription success mail is not valid.',
                    'pxb-waitlist'
                )
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
        _e('Customize how your waitlist form will be displayed to your customers.', 'pxb-waitlist');
    }

    /**
     * @since 1.0.0
     * 
     * @return void
     */
    public function settingsSectionValidation() {
        _e('Customize the success and error messages your subscribers will see when submitting the form.', 'pxb-waitlist');
    }

    /**
     * @since 1.0.0
     * 
     * @return void
     */
    public function settingsSectionCheckbox(): void 
    {
        _e(
            '<p>Add an I Agree-checkbox to the subscribe form.</p>

            <p>The custom text next to the checkbox accepts HTML tags, 
            so you can link to your terms and privacy policy:
            <b>&lta href="#">terms&lt/a&gt; and &lta href="#">privacy policy&lt/a&gt;</b></p>', 
            'pxb-waitlist'
        );
    }

    /**
     * @since 1.0.0
     * 
     * @return void
     */
    public function settingsSectionMail(): void 
    {
        _e(
            '<p>Customize the emails sent to your subscribers. 
            You can use these shortcodes in the subject and the message of your mails:
            <b>{product_id}</b>,
            <b>{product_name}</b>,
            <b>{product_link}</b>,
            <b>{product_image}</b>,
            <b>{subscriber_email}</b>,
            <b>{shop_name}</b>,
            <b>{unsubscribe_link}</b>.</p> 
            
            <p>The mail messages also accept all HTML tags which may be used in posts. 
            Use this snippet to link to a product: 
            <b>&lt;a href="{product_link}"&gt;{product_name}&lt;/a&gt;</b></p>',
            'pxb-waitlist'
        );
    }

    /**
     * @since 1.0.0
     * 
     * @return void
     */
    public function settingsSectionUnsubscribe(): void 
    {
        _e(
            '<p>Customize the title and the message of  the unsubscribe confirmation page. 
            The unsubscribe link in a success subscription mail leads to this page.</p>

            <p>The unsubscribe message accepts all HTML tags which may be used in posts.</p>',
            'pxb-waitlist'
        );
    }

    /**
     * @since 1.0.0
     * 
     * @param array $args
     * @return void
     */
    public function drawInputText(array $args): void 
    {
        $value = htmlentities(get_option($args['name']));
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
        $text = stripslashes(wp_kses_post(addslashes(get_option($args['name']))));
        echo '<textarea id="' . $args['name'] . '" 
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
