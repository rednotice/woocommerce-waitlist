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
 * This class allows the user to customize the mails sent to the subscribers.
 * 
 * @since 1.0.0
 */
class UnsubscribeSettings
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
            [
                'name' => 'pxb_waitlist_unsubscribe_title',
                'title' => __('Title for the Unsubscribe Page', 'pxb-waitlist'),
                'defaultValue' => __('You Have Successfully Unsubscribed', 'pxb-waitlist'),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_unsubscribe',
                'callback' => 'drawInputText'
            ],
            [
                'name' => 'pxb_waitlist_unsubscribe_message',
                'title' => __('Message for the Unsubscribe Page', 'pxb-waitlist'),
                'defaultValue' => __(
                    '<p>We will not send you a notification mail when the product is available again.</p>',
                    'pxb-waitlist'
                ),
                'page' => 'pxb_settings',
                'section' => 'pxb_waitlist_settings_unsubscribe',
                'callback' => 'drawTextarea'
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
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_unsubscribe_title',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeTextField')
                ]
            ],
            [
                'option_group' => 'pxb_waitlist_option_group',
                'option_name' => 'pxb_waitlist_unsubscribe_message',
                'args' => [
                    'sanitize_callback' => array($this->callbacks, 'sanitizeHtmlTextField')
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
                'id' => 'pxb_waitlist_settings_unsubscribe',
                'title' => __('Unsubscribe Confirmation Page', 'pxb-waitlist'),
                'callback' => array($this->callbacks, 'settingsSectionUnsubscribe'),
                'page' => 'pxb_settings'
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
