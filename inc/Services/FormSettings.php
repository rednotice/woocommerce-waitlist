<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Services;

use \Inc\Api\SettingsApi;
use \Inc\Api\Callbacks\SettingsCallbacks;

class FormSettings
{
    public $options;

    public $callbacks;

    public $settingsApi;

    public $settings;

    public $sections;

    public $fields;

    public function __construct()
    {
        $this->setOptions();
    }

    public function register(): void 
    {
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

    public function setOptions(): void 
    {
        $this->options = [
            // Front end form options.
            [
                'name' => 'wpbits_waitlist_title_label',
                'title' => 'Title for Subscribe Form',
                'defaultValue' => 'Email me when back in stock',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_form',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'wpbits_waitlist_email_label',
                'title' => 'Placeholder for Email Field',
                'defaultValue' => 'Your email address',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_form',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'wpbits_waitlist_subscribe_label',
                'title' => 'Text for Subscribe Button',
                'defaultValue' => 'Subscribe',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_form',
                'callback' => 'drawInputText'
            ],
            // Validation options.
            [
                'name' => 'wpbits_waitlist_submission_label',
                'title' => 'During Submission Message',
                'defaultValue' => 'Please wait.',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'wpbits_waitlist_success_label',
                'title' => 'Success Message',
                'defaultValue' => 'Your subscription was successful. We will email you when this product is available again.',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'wpbits_waitlist_error_label',
                'title' => 'Error Message',
                'defaultValue' => 'There was an error. Please try again.',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'wpbits_waitlist_email_error_label',
                'title' => 'Invalid Email Error Message',
                'defaultValue' => 'Your email is required.',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'wpbits_waitlist_already_subscribed_error_label',
                'title' => 'Already Subscribed Error Message',
                'defaultValue' => 'You have already subscribed to this product. We will email you when it is available again..',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            // I agree-checkbox.
            [
                'name' => 'wpbits_waitlist_confirmation_label',
                'title' => 'Enable I Agree in Subscribe Form',
                'defaultValue' => '',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_checkbox',
                'callback' => 'drawCheckbox'
            ],
            [
                'name' => 'wpbits_waitlist_confirmation_text_label',
                'title' => 'Text to Appear Next to the Checkbox',
                'defaultValue' => 'I Agree to the <a href="#">terms</a> and <a href="">privacy policy</a>',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_checkbox',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'wpbits_waitlist_confirmation_error_label',
                'title' => 'Checkbox Error Message',
                'defaultValue' => 'Please accept our terms and privacy policy.',
                'page' => 'wpbits_settings',
                'section' => 'wpbits_waitlist_settings_checkbox',
                'callback' => 'drawInputText'
            ]
        ];
    }

    public function setSettings(): void
    {
        $this->settings = [
            // Front end form settings
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_title_label',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeOption')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_email_label',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeOption')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_subscribe_label',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeOption')
                ]
            ],
            // Validation settings
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_submission_label',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeOption')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_success_label',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeOption')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_error_label',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeOption')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_email_error_label',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeOption')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_already_subscribed_error_label',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeOption')
                ]
            ],
            // Checkbox settings
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_confirmation_label',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeCheckbox')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_confirmation_text_label',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeOption')
                ]
            ],
            [
                'option_group' => 'wpbits_waitlist_option_group',
                'option_name' => 'wpbits_waitlist_confirmation_error_label',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeOption')
                ]
            ]
        ];
    }

    public function setSections(): void
    {
        $this->sections = [
            [
                'id' => 'wpbits_waitlist_settings_form',
                'title' => 'Subscribe Form',
                'callback' => array($this->callbacks, 'settingsSectionForm'),
                'page' => 'wpbits_settings'
            ],
            [
                'id' => 'wpbits_waitlist_settings_validation',
                'title' => 'Validation Messages',
                'callback' => array($this->callbacks, 'settingsSectionValidation'),
                'page' => 'wpbits_settings'
            ],
            [
                'id' => 'wpbits_waitlist_settings_checkbox',
                'title' => 'I Agree Checkbox in Subscribe Form',
                'callback' => array($this->callbacks, 'settingsSectionCheckbox'),
                'page' => 'wpbits_settings'
            ]
        ];
    }

    public function setFields(): void 
    {
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
                    'class' => ''
                )
            ];
        }
    }
}