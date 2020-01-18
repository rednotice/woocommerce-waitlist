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

        add_action( 'woocommerce_single_product_summary', array( $this, 'loadTemplateForSingleProducts'), 31 );

        add_action( 'woocommerce_single_product_summary', array( $this, 'loadFormTagsForVariationProducts'), 10 );
        add_filter( 'woocommerce_available_variation',  array( $this, 'loadTemplateForVariationProducts'), 1, 3 );

        add_action( 'wp_ajax_nopriv_wpbits_submit_subscriber', array( $this, 'submitSubscriber') );
        add_action( 'wp_ajax_wpbits_submit_subscriber', array( $this, 'submitSubscriber') );
    }

    public function loadTemplateForSingleProducts() 
    {
        global $product;

        if ( ! $product->is_type('variable') && ! $product->is_in_stock() ) { 
            return require_once( $this->pluginPath .'/views/waitlist-form.php' );
        }
    }

    public function loadFormTagsForVariationProducts(): void 
    {
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

    public function loadTemplateForVariationProducts($data, $product, $variation): string
    {        
        if( ! $variation->is_in_stock() ) {
            ob_start();
            include $this->pluginPath .'/views/waitlist-form.php';
            $form = ob_get_clean();

            $data['availability_html'] .= $form;
        }

        return $data;
    }

    public function submitSubscriber(): array
    {
        $email = sanitize_email( $_POST['email'] );
        $productId = sanitize_text_field($_POST['productId']);
        $variationId = sanitize_text_field($_POST['variationId']);

        $this->waitlistApi = new WaitlistApi();
        if($this->waitlistApi->isSubscribed($email, $productId, $variationId) ) {
            $return = [
                'status' => 'alreadySubscribed'
            ];
            wp_send_json( $return );
            wp_die();
        } else {
            $subscriberId = $this->waitlistApi->saveSubscriber($email, $productId, $variationId);
        }

        if($subscriberId) {
            if( get_option( 'wpbits_waitlist_enable_subscription_mail' ) ) {
                $this->mail = new Mail();
                $this->mail->sendSuccessSubscriptionMail( $subscriberId );
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
}