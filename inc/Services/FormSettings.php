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
 * This class allows the user to customize the waitlist form.
 * 
 * @since 1.0.0
 */
class FormSettings
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
	 * Form settings. 
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $settings;

    /**
	 * Sections for the form settings admin page.
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $sections;

    /**
	 * Fields for the form settings admin page.
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
    public function __construct()
    {
        $this->setOptions();
    }

    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
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

    /**
	 * Populates the options attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setOptions(): void 
    {
        $this->options = [
            // Front end form options.
            [
                'name' => 'woobits_waitlist_title',
                'title' => __('Title for Subscribe Form', 'woobits-waitlist'),
                'defaultValue' => __('Email me when back in stock', 'woobits-waitlist'),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_form',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'woobits_waitlist_email',
                'title' => __('Placeholder for Email Field', 'woobits-waitlist'),
                'defaultValue' => __('Your email address', 'woobits-waitlist'),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_form',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'woobits_waitlist_subscribe',
                'title' => __('Text for Subscribe Button', 'woobits-waitlist'),
                'defaultValue' => __('Subscribe', 'woobits-waitlist'),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_form',
                'callback' => 'drawInputText'
            ],
            // Validation options.
            [
                'name' => 'woobits_waitlist_submission',
                'title' => __('During Submission Message', 'woobits-waitlist'),
                'defaultValue' => __('Please wait.', 'woobits-waitlist'),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'woobits_waitlist_success',
                'title' => __('Success Message', 'woobits-waitlist'),
                'defaultValue' => __(
                    'Your subscription was successful. 
                    We will email you when this product is available again.',
                    'woobits-waitlist'
                ),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'woobits_waitlist_error',
                'title' => __('Error Message', 'woobits-waitlist'),
                'defaultValue' => __('There was an error. Please try again.', 'woobits-waitlist'),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'woobits_waitlist_email_error',
                'title' => __('Invalid Email Error Message', 'woobits-waitlist'),
                'defaultValue' => __('Your email is required.', 'woobits-waitlist'),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'woobits_waitlist_already_subscribed_error',
                'title' => __('Already Subscribed Error Message', 'woobits-waitlist'),
                'defaultValue' => __(
                    'You have already subscribed to this product. 
                    We will email you when it is available again.',
                    'woobits-waitlist'
                ),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            // I agree-checkbox.
            [
                'name' => 'woobits_waitlist_confirmation',
                'title' => __('Enable I Agree in Subscribe Form', 'woobits-waitlist'),
                'defaultValue' => '',
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_checkbox',
                'callback' => 'drawCheckbox'
            ],
            [
                'name' => 'woobits_waitlist_confirmation_text',
                'title' => __('Text to Appear Next to the Checkbox', 'woobits-waitlist'),
                'defaultValue' => __(
                    'I Agree to the <a href="#">terms</a> and <a href="">privacy policy</a>.',
                    'woobits-waitlist'
                ),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_checkbox',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'woobits_waitlist_confirmation_error',
                'title' => __('Checkbox Error Message', 'woobits-waitlist'),
                'defaultValue' => __('Please accept our terms and privacy policy.', 'woobits-waitlist'),
                'page' => 'woobits_settings',
                'section' => 'woobits_waitlist_settings_checkbox',
                'callback' => 'drawInputText'
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
    public function setSettings(): void
    {
        $this->settings = [
            // Front end form settings
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_title',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_email',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_subscribe',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            // Validation settings
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_submission',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_success',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_error',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_email_error',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_already_subscribed_error',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            // Checkbox settings
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_confirmation',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeCheckbox')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_confirmation_text',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'woobits_waitlist_option_group',
                'option_name' => 'woobits_waitlist_confirmation_error',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
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
    public function setSections(): void
    {
        $this->sections = [
            [
                'id' => 'woobits_waitlist_settings_form',
                'title' => __('Subscribe Form', 'woobits-waitlist'),
                'callback' => array($this->callbacks, 'settingsSectionForm'),
                'page' => 'woobits_settings'
            ],
            [
                'id' => 'woobits_waitlist_settings_validation',
                'title' => __('Validation Messages', 'woobits-waitlist'),
                'callback' => array($this->callbacks, 'settingsSectionValidation'),
                'page' => 'woobits_settings'
            ],
            [
                'id' => 'woobits_waitlist_settings_checkbox',
                'title' => __('I Agree Checkbox in Subscribe Form', 'woobits-waitlist'),
                'callback' => array($this->callbacks, 'settingsSectionCheckbox'),
                'page' => 'woobits_settings'
            ]
        ];
    }

    /**
	 * Populates the fields attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
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
