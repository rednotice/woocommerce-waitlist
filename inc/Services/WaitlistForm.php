<?php
/**
 * @package pixelbaseWaitlist
 * 
 * @since 1.0.0
 */

namespace PixelBase\Services;

use \PixelBase\Base\Paths;
use \PixelBase\Base\Helpers;
use \PixelBase\Services\Mail;

/**
 * Implements the waitlist front end form.
 * 
 * @since 1.0.0
 */
class WaitlistForm
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
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        $this->paths = new Paths();

        add_action('wp_enqueue_scripts', array( $this, 'enqueueScripts'), 10);

        add_action('woocommerce_single_product_summary', array($this, 'loadTemplateForSingleProducts'), 31 );
        add_action('woocommerce_single_product_summary', array($this, 'loadFormTagsForVariationProducts'), 10);
        add_filter('woocommerce_available_variation',  array($this, 'loadTemplateForVariationProducts'), 1, 3);

        add_action('wp_ajax_nopriv_pxb_submit_subscriber', array($this, 'submitSubscriber'));
        add_action('wp_ajax_pxb_submit_subscriber', array($this, 'submitSubscriber'));
    }

    /**
	 * Enqueues the javascript script and style sheet for the waitlist form.
	 *
	 * @since 1.0.0
     * 
	 * @return bool
	 */
    public function enqueueScripts(): bool
    {
        if(!is_product()) {
            return false;
        }

        wp_enqueue_style('waitlistFormStyle', $this->paths->pluginUrl . 'assets/css/form.css');
        wp_enqueue_script('waitlistFormScript', $this->paths->pluginUrl . 'assets/js/form.js');
        return true;
    }

    /**
	 * Loads the waitlist form on single product pages.
	 *
	 * @since 1.0.0
     * 
	 * @return string|null The waitlist form template.
	 */
    public function loadTemplateForSingleProducts(): ?string 
    {
        global $product;

        if($product->is_type('variable') || $product->is_in_stock()) { 
            return null;
        }

        return require_once( $this->paths->pluginPath .'/views/waitlist-form.php' );
    }

    /**
	 * Loads the form tags for the waitlist form template
     * on varation product pages.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function loadFormTagsForVariationProducts(): void 
    {
        global $product;
        $url = admin_url('admin-ajax.php');
        if ($product->is_type('variable')) { 
            echo '<form 
                id="pxb-waitlist-form" 
                action="#" method="POST" 
                data-url="' . $url . '"
            >
            </form>';
        }
    }

    /**
	 * Attaches the waitlist form template to the data 
     * displayed on variation product pages.
	 *
	 * @since 1.0.0
     * 
     * @param array $data The data displayed on the product variation page.
     * @param object $product
     * @param object $variation
	 * @return array $data The data to display on the page 
     * including the waitlist form template.
	 */
    public function loadTemplateForVariationProducts(array $data, object $product, object $variation): array
    {        
        if(!$variation->is_in_stock() ) {
            ob_start();
            include $this->paths->pluginPath .'/views/waitlist-form.php';
            $form = ob_get_clean();

            $data['availability_html'] .= $form;
        }
        return $data;
    }

    /**
     * Submit the subscriber.
     * 
	 * Fires when the submit button of the waitlist front end
     * form is triggered. Stores the subscriber to the database 
     * and triggers the sending of the success subscription mail. 
	 *
	 * @since 1.0.0
     * 
	 * @return array $return Returns the status to form.js by ajax
     * to trigger the validation messages.
	 */
    public function submitSubscriber(): array
    {
        $email = is_email($_POST['email']);
        $productId = intval($_POST['productId']);
        $variationId = intval($_POST['variationId']);

        if(Helpers::isSubscribed($email, $productId, $variationId)) {
            $return = [
                'status' => 'alreadySubscribed'
            ];
            wp_send_json($return);
            wp_die();
        } else {
            $subscriberId = Helpers::saveSubscriber($email, $productId, $variationId);
        }

        if($subscriberId) {
            if( get_option('pxb_waitlist_enable_subscription_mail')) {
                $this->mail = new Mail();
                $this->mail->sendSuccessSubscriptionMail($subscriberId);
            }
            $return = [
                'status' => 'success'
            ];
            wp_send_json($return);
            wp_die();
        };

        $return = [
            'status' => 'error'
        ];
        wp_send_json($return);
        wp_die();
    }
}
