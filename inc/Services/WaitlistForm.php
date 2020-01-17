<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Services;

use \Inc\Base\Paths;

use \Inc\Services\Mail;
use \Inc\Api\WaitlistApi;

class WaitlistForm extends Paths
{
    public function register() {
        if ( ! class_exists( 'WC_Product' ) ) {
            return;
        }
        
        add_action( 'wp_ajax_nopriv_wpbits_submit_subscriber', array( $this, 'submitSubscriber') );
        add_action( 'wp_ajax_wpbits_submit_subscriber', array( $this, 'submitSubscriber') );

        add_action( 'woocommerce_single_product_summary', array( $this, 'loadFormForSingleProducts'), 31 );

        add_action( 'woocommerce_single_product_summary', array( $this, 'loadFormTags'), 10 );
        add_filter( 'woocommerce_available_variation',  array( $this, 'loadFormContainer'), 1, 3 );
    }

    public function submitSubscriber() {
        $email = sanitize_email( $_POST['email'] );
        $productId = sanitize_text_field($_POST['productId']);
        $variationId = sanitize_text_field($_POST['variationId']);

        $this->waitlistApi = new WaitlistApi();
        if( ! $this->waitlistApi->isSubscribed( $email, $productId, $variationId ) ) {
            $postId = $this->waitlistApi->saveSubscriber( $email, $productId, $variationId );
        }

        if( $postId ) {
            if( get_option( 'wpbits_waitlist_enable_subscription_mail' ) ) {
                $this->mail = new Mail();
                $this->mail->sendSuccessSubscriptionMail( $email );
            }

            $return = [
                'status' => 'success'
            ];
            wp_send_json( $return );
            wp_die();
        };

        $return = [
            'status' => 'error'
        ];
        wp_send_json( $return );
        wp_die();

    }

    public function loadFormForSingleProducts() {
        global $product;

        if ( ! $product->is_type('variable') && ! $product->is_in_stock() ) { 
            return require_once( $this->pluginPath .'/views/waitlist-form.php' );
        }
    }

    public function loadFormTags() {
        global $product;

        $url = admin_url( 'admin-ajax.php' );

        if ( $product->is_type('variable') ) { 
            echo '<form 
                id="wpbits-waitlist-form" 
                action="#" method="POST" 
                data-url="' . $url . '">
                </form>';
        }
    }

    public function loadFormContainer( $data, $product, $variation) {
        wp_enqueue_style( 'waitlistFormStyle', $this->pluginUrl . 'assets/css/form.css' );
        
        if( ! $variation->is_in_stock() ) {
            ob_start();
            include $this->pluginPath .'/views/waitlist-form.php';
            $form = ob_get_clean();

            $data['availability_html'] .= $form;
        }

        return $data;
    }
}