<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Api\Callbacks;

use \Inc\Base\Paths;

class SettingsCallbacks extends Paths
{
    public function settings() {
        return require_once( $this->pluginPath .'/views/settings.php' );
    }

    public function upgrade() {
        return require_once( $this->pluginPath .'/views/upgrade.php' );
    }

    public function sanitizeOption( $input ) {
        return $input;
    }

    public function sanitizeCheckbox( $input ) {
        return ( isset( $input ) ? true : false );
    }

    public function settingsSectionForm() {
        echo 'Customize how your waitlist form will be displayed to your customers.';
    }

    public function settingsSectionValidation() {
        echo 'Customize the success and error messages your subscribers will see when submitting the form.';
    }

    public function settingsSectionCheckbox() {
        echo 'Add an I Agree-checkbox to the subscribe form.';
    }

    public function settingsSectionMail() {
        echo 'Customize the emails sent to your subscribers. You can use HTML and these shortcodes in the subject and the message of your mails:
        <b>{product_id}</b>,
        <b>{product_name}</b>,
        <b>{product_link}</b>,
        <b>{product_image}</b>,
        <b>{subscriber_email}</b>,
        <b>{shop_name}</b>.';
    }

    public function drawInputText( array $args ) {
        $value = esc_attr( get_option( $args['name'] ) );
        echo '<input 
            type="text" 
            class="regular-text ' . $args['class'] . '" 
            name="' . $args['name'] . '" 
            value="' . $value . '"
            placeholder="' . ( $args['placeholder'] ?? '' ) . '"
        >';
    }

    public function drawTextarea( array $args ) {
        $text = esc_attr( get_option( $args['name'] ) );
        echo '<textarea 
            rows="15"
            cols="50" 
            class="regular-text ' . $args['class'] . '" 
            name="' . $args['name'] . '">'
            . $text .
        '</textarea>';
    }

    public function drawCheckbox( array $args ) {
        $checkbox = get_option( $args['name'] );
        echo '<input 
            type="checkbox" 
            name="' . $args['name'] . '"'
            . ( $checkbox ? 'checked' : '' ) . '
        >';
    }

}