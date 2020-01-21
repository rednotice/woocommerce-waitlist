<?php
/**
 * @package wpbitsWaitlist
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
                'name' => 'wpbits_waitlist_enable_instock_mail',
                'title' => 'Enable Automatic Instock Mail',
                'defaultValue' => 1,
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_mail',
                'callback' => 'drawCheckbox'
            ],
            [
                'name' => 'wpbits_waitlist_instock_mail_subject',
                'title' => 'Instock Mail Subject',
                'defaultValue' => 'Product {product_name} is back in stock',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_mail',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'wpbits_waitlist_instock_mail_message',
                'title' => 'Instock Mail Message',
                'defaultValue' => 'Some email text....',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_mail',
                'callback' => 'drawTextarea'
            ],
            // Subscription success mail options.
            [
                'name' => 'wpbits_waitlist_enable_subscription_mail',
                'title' => 'Enable Success Subscription Mail',
                'defaultValue' => 1,
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_mail',
                'callback' => 'drawCheckbox'
            ],
            [
                'name' => 'wpbits_waitlist_subscription_mail_subject',
                'title' => 'Success Subscription Mail Subject',
                'defaultValue' => 'You have successfully subscribed',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_mail',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'wpbits_waitlist_subscription_mail_message',
                'title' => 'Success Subscription Mail Message',
                'defaultValue' => 'Some email text....',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_mail',
                'callback' => 'drawTextarea'
            ],
            [
                'name' => 'wpbits_waitlist_subscription_mail_copy',
                'title' => 'Send Copy of Success Subscription Mail to this Email Address Email',
                'defaultValue' => '',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_mail',
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
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_enable_instock_mail',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeCheckbox')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_instock_mail_subject',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_instock_mail_message',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            // Success subscription mail settings.
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_enable_subscription_mail',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeCheckbox')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_subscription_mail_subject',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_subscription_mail_message',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeHtmlTextField')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_subscription_mail_copy',
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
                'id' => 'wpbits_waitlist_settings_mail',
                'title' => 'Mail Settings',
                'callback' => array($this->callbacks, 'settingsSectionMail'),
                'page' => 'wpbits_settings'
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
