<?php
/**
 * @package pixelbaseWaitlist
 * 
 * @since 1.0.0
 */

namespace PixelBase\Services;

use \PixelBase\Api\SettingsApi;
use \PixelBase\Api\SettingsCallbacks;

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
                'name' => 'pxb_waitlist_title',
                'title' => __('Title for Subscribe Form', 'pxb-waitlist'),
                'defaultValue' => __('Email me when back in stock', 'pxb-waitlist'),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_form',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'pxb_waitlist_email',
                'title' => __('Placeholder for Email Field', 'pxb-waitlist'),
                'defaultValue' => __('Your email address', 'pxb-waitlist'),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_form',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'pxb_waitlist_subscribe',
                'title' => __('Text for Subscribe Button', 'pxb-waitlist'),
                'defaultValue' => __('Subscribe', 'pxb-waitlist'),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_form',
                'callback' => 'drawInputText'
            ],
            // Validation options.
            [
                'name' => 'pxb_waitlist_submission',
                'title' => __('During Submission Message', 'pxb-waitlist'),
                'defaultValue' => __('Please wait.', 'pxb-waitlist'),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'pxb_waitlist_success',
                'title' => __('Success Message', 'pxb-waitlist'),
                'defaultValue' => __(
                    'Your subscription was successful. We will email you when this product is available again.',
                    'pxb-waitlist'
                ),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'pxb_waitlist_error',
                'title' => __('Error Message', 'pxb-waitlist'),
                'defaultValue' => __('There was an error. Please try again.', 'pxb-waitlist'),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'pxb_waitlist_email_error',
                'title' => __('Invalid Email Error Message', 'pxb-waitlist'),
                'defaultValue' => __('Your email is required.', 'pxb-waitlist'),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'pxb_waitlist_already_subscribed_error',
                'title' => __('Already Subscribed Error Message', 'pxb-waitlist'),
                'defaultValue' => __(
                    'You have already subscribed to this product. 
                    We will email you when it is available again.',
                    'pxb-waitlist'
                ),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_validation',
                'callback' => 'drawInputText'
            ],
            // I agree-checkbox.
            [
                'name' => 'pxb_waitlist_confirmation',
                'title' => __('Enable I Agree in Subscribe Form', 'pxb-waitlist'),
                'defaultValue' => '',
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_checkbox',
                'callback' => 'drawCheckbox'
            ],
            [
                'name' => 'pxb_waitlist_confirmation_text',
                'title' => __('Text to Appear Next to the Checkbox', 'pxb-waitlist'),
                'defaultValue' => __(
                    'I agree to the <a href="/#">terms</a> and <a href="/#">privacy policy</a>.',
                    'pxb-waitlist'
                ),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_checkbox',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'pxb_waitlist_confirmation_error',
                'title' => __('Checkbox Error Message', 'pxb-waitlist'),
                'defaultValue' => __('Please accept our terms and privacy policy.', 'pxb-waitlist'),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_checkbox',
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
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_title',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_email',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_subscribe',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            // Validation settings
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_submission',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_success',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_error',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_email_error',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_already_subscribed_error',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            // Checkbox settings
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_confirmation',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeCheckbox')
                ]
            ],
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_confirmation_text',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeHtmlTextField')
                ]
            ],
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_confirmation_error',
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
                'id' => 'pxb_waitlist_settings_form',
                'title' => __('Subscribe Form', 'pxb-waitlist'),
                'callback' => array($this->callbacks, 'settingsSectionForm'),
                'page' => 'pxb_settings'
            ],
            [
                'id' => 'pxb_waitlist_settings_validation',
                'title' => __('Validation Messages', 'pxb-waitlist'),
                'callback' => array($this->callbacks, 'settingsSectionValidation'),
                'page' => 'pxb_settings'
            ],
            [
                'id' => 'pxb_waitlist_settings_checkbox',
                'title' => __('I Agree Checkbox in Subscribe Form', 'pxb-waitlist'),
                'callback' => array($this->callbacks, 'settingsSectionCheckbox'),
                'page' => 'pxb_settings'
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
                    'class' => ($option['class'] ?? ''),
                    'help_tip' => ($option['help_tip'] ?? '')
                )
            ];
        }
    }
}
