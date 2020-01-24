<?php
/**
 * @package woobitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

use \Inc\Api\SettingsApi;
use \Inc\Api\SettingsCallbacks;

/**
 * This class allows the user to customize the mails sent to the subscribers.
 * 
 * @since 1.0.0
 */
class MailSettings
{
    /**
	 * Options.
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $options;

    /**
	 * Instance of the SettingsApi class.
	 *
	 * @since 1.0.0
     * 
	 * @var object
	 */
    public $settingsApi;

    /**
	 * Instance of the SettingsCallbacks class.
	 *
	 * @since 1.0.0
     * 
	 * @var object
	 */
    public $callbacks;

    /**
	 * Mail settings. 
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $settings;

    /**
	 * Sections for the mail settings admin page.
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $sections;

    /**
	 * Fields for the mail settings admin page.
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $fields;

     /**
	 * Populates the options attribute.
	 *
	 * @since 1.0.0
	 */
    public function __construct() {
        $this->setOptions();
    }

    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register() {
        $this->settingsApi = new SettingsApi();
        $this->callbacks = new SettingsCallbacks();

        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this
        ->settingsApi
        ->setSettings($this->settings)
        ->setSections($this->sections)
        ->setFields($this->fields)
        ->register();
    }

    /**
	 * Populates the options attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setOptions() {
        $this->options = [
            // Instock mail options.
            [
                'name' => 'woobits_waitlist_enable_instock_mail',
                'title' => __('Enable Automatic Instock Mail', 'woobits-waitlist'),
                'defaultValue' => 1,
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_mail',
                'callback' => 'drawCheckbox'
            ],
            [
                'name' => 'woobits_waitlist_instock_mail_subject',
                'title' => __('Instock Mail Subject', 'woobits-waitlist'),
                'defaultValue' => __('{product_name} is back in stock', 'woobits-waitlist'),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_mail',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'woobits_waitlist_instock_mail_message',
                'title' => __('Instock Mail Message', 'woobits-waitlist'),
                'defaultValue' => __(
                    'Hello subscriber_email},{line_break}{line_break}
                    the product "{product_name}" you have been waiting for is available again.
                    {line_break}{line_break}
                    You can buy it here:{line_break}
                    {product_link}
                    {line_break}Best wishes,
                    {line_break}your {shop_name} team',
                    'woobits-waitlist'
                ),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_mail',
                'callback' => 'drawTextarea'
            ],
            // Subscription success mail options.
            [
                'name' => 'woobits_waitlist_enable_subscription_mail',
                'title' => __('Enable Success Subscription Mail', 'woobits-waitlist'),
                'defaultValue' => 1,
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_mail',
                'callback' => 'drawCheckbox'
            ],
            [
                'name' => 'woobits_waitlist_subscription_mail_subject',
                'title' => __('Success Subscription Mail Subject', 'woobits-waitlist'),
                'defaultValue' => __('You have successfully subscribed to {product_name}', 'woobits-waitlist'),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_mail',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'woobits_waitlist_subscription_mail_message',
                'title' => __('Success Subscription Mail Message', 'woobits-waitlist'),
                'defaultValue' => __(
                    'Hello {subscriber_email},{line_break}
                    you have successfully subscribed to {product_name}. 
                    We will email you when the product is available again.{line_break}
                    Best wishes,{line_break}
                    your {shop_name} team',
                    'woobits-waitlist'
                ),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_mail',
                'callback' => 'drawTextarea'
            ],
            [
                'name' => 'woobits_waitlist_subscription_mail_copy',
                'title' => __('Send Copy of Success Subscription Mail to this Email Address Email', 'woobits-waitlist'),
                'defaultValue' => '',
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_mail',
                'callback' => 'drawInputText',
                'placeholder' => 'sample@example.com'
            ]
        ];
    }

    /**
	 * Populates the settings attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setSettings() {
        $this->settings = [
            // Instock mail settings.
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_enable_instock_mail',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeCheckbox')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_instock_mail_subject',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_instock_mail_message',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            // Success subscription mail settings.
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_enable_subscription_mail',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeCheckbox')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_subscription_mail_subject',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_subscription_mail_message',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeHtmlTextField')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_subscription_mail_copy',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeEmail')
                ]
            ]
        ];
    }

     /**
	 * Populates the sections attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setSections() {
        $this->sections = [
            [
                'id' => 'woobits_waitlist_settings_mail',
                'title' => __('Mail Settings', 'woobits-waitlist'),
                'callback' => array($this->callbacks, 'settingsSectionMail'),
                'page' => 'woobits_settings'
            ],
        ];
    }

    /**
	 * Populates the fields attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setFields() {
        $this->fields = [];

        foreach($this->options as $option) {
            $this->fields[] = [
                'id' => $option['name'],
                'title' => $option['title'],
                'callback' => array($this->callbacks, $option['callback']),
                'page' => $option['page'],
                'section' => $option['section'],
                'args' => array(
                    'name' => $option['name'],
                    'label_for' => $option['name'],
                    'class' => ( $option['class'] ?? ''),
                    'placeholder' => ( $option['placeholder'] ?? ''),
                )
            ];
        }
    }
}
