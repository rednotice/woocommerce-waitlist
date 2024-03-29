<?php
/**
 * @package pixelbaseWaitlist
 * 
 * @since 1.0.0
 */

namespace PixelBase\Services;

use \PixelBase\Base\Helpers;
use \PixelBase\Services\Unsubscribe;
use \PixelBase\Services\SubscriberStatus;

/**
 * Handles the mails sent to the subscribers.
 * 
 * @since 1.0.0
 */
class Mail
{
    /**
	 * Instance of the Unsubcribe class.
	 *
	 * @since 1.0.0
     * 
	 * @var object
	 */
    public $unsubscribe;

    /**
	 * Instance of the SubscriberStatus class.
	 *
	 * @since 1.0.0
     * 
	 * @var object
	 */
    public $subscriberStatus;


    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        add_filter('pxb_replace_shortcodes', array($this, 'replaceShortcodes'), 10, 2);

        if( get_option('pxb_waitlist_enable_instock_mail') ) {
            add_action('woocommerce_update_product', array($this, 'automaticInstockMails'), 10, 1);
            add_action('woocommerce_update_product_variation', array($this, 'automaticInstockMails'), 10, 1);
        }
    }

    /**
	 * Replaces the shortcodes the user can use in the mail templates.
	 *
	 * @since 1.0.0
     * 
     * @param string $text Mail subject or message created by the user.
     * @param int $subscriberId Id of the subscriber the mail will be send to.
	 * @return string Filtered text.
	 */
    public function replaceShortcodes(string $text, int $subscriberId): string  
    {
        $productId = Helpers::getProductId($subscriberId);
        $productName = Helpers::getProductName($subscriberId);
        $productLink = Helpers::getProductLink($subscriberId);
        $productImage = Helpers::getProductImage($subscriberId);
        $subscriberEmail = Helpers::getSubscriberEmail($subscriberId);
        $shopName = Helpers::getShopName();
        $unsubscribeLink = Unsubscribe::generateUrl($subscriberEmail);

        $shortcodes = [ 
            '{product_id}', 
            '{product_name}', 
            '{product_link}',
            '{product_image}',
            '{subscriber_email}',
            '{shop_name}',
            '{unsubscribe_link}'
        ];

        $replacements = [ 
            $productId, 
            $productName, 
            $productLink,
            $productImage,
            $subscriberEmail,
            $shopName,
            $unsubscribeLink
        ];

        $filteredText = str_replace($shortcodes, $replacements, $text);
        return $filteredText;
    }

    /**
	 * Gets the mail template.
	 *
	 * @since 1.0.0
     * 
     * @param string $subject Mail subject.
     * @param string $message Mail message.
	 * @return string Mail template.
	 */
    public function getMailTemplate(string $subject, string $message): string 
    {
        ob_start();

        if(function_exists('wc_get_template')) {
            $wc_emails = new \WC_Emails();
            $wc_emails->email_header($subject);
            echo $message;
            $wc_emails->email_footer();
        } else {
            woocommerce_get_template('emails/email-header.php', array('email_heading' => $subject));
            echo $message;
            woocommerce_get_template('emails/email-footer.php');
        }
        return ob_get_clean();
    }

    /**
	 * Sends a success subscription mail to the subscriber.
	 *
	 * @since 1.0.0
     * 
     * @param int $subscriberId Id of the subscriber the mail will be send to.
	 * @return void
	 */
    public function sendSuccessSubscriptionMail(int $subscriberId): void
    {
        $to = get_post_meta($subscriberId, '_pxbwaitlist_email', true);
        $subject = apply_filters( 
            'pxb_replace_shortcodes', 
            get_option('pxb_waitlist_subscription_mail_subject'), 
            $subscriberId 
        );
        $message = apply_filters( 
            'pxb_replace_shortcodes', 
            get_option('pxb_waitlist_subscription_mail_message'), 
            $subscriberId 
        );
        $template = $this->getMailTemplate($subject, $message);

        if(get_option('pxb_waitlist_subscription_mail_copy')) {
            $header = 'Bcc: ' . get_option('pxb_waitlist_subscription_mail_copy');
        }

        $mailer = WC()->mailer();
        $mailer->send($to, $subject, $template, $header ?? '');
    }

    /**
	 * Sends an instock mail to the subscriber.
	 *
	 * @since 1.0.0
     * 
     * @param int $subscriberId Id of the subscriber the mail will be send to.
	 * @return string Status.
	 */
    public function sendInstockMail(int $subscriberId): string 
    {
        $to = get_post_meta($subscriberId, '_pxbwaitlist_email', true);
        $subject = apply_filters( 
            'pxb_replace_shortcodes', 
            get_option('pxb_waitlist_instock_mail_subject'), 
            $subscriberId 
        );
        $message = apply_filters( 
            'pxb_replace_shortcodes', 
            get_option('pxb_waitlist_instock_mail_message'), 
            $subscriberId 
        );

        $mailer = WC()->mailer();
        $mailSentSuccess = $mailer->send($to, $subject, $this->getMailTemplate($subject, $message));
        
        $this->subscriberStatus = new SubscriberStatus();
        if(!$mailSentSuccess) {
            $this->subscriberStatus->updateStatus($subscriberId, 'pxb_failed');
            return false;
        }
        return $this->subscriberStatus->updateStatus($subscriberId, 'pxb_mailsent');
    }

    /**
	 * Automatically sends instock mails to the subscribers of a back in stock product.
	 *
	 * @since 1.0.0
     * 
     * @param int $updatedProductId
	 * @return bool
	 */
    public function automaticInstockMails(int $updatedProductId): bool 
    {
        $updatedProduct = wc_get_product($updatedProductId);

        if(!$updatedProduct || !$updatedProduct->is_in_stock() || $updatedProduct->get_stock_quantity() <= 0 ) {
            return false;
        }

        $subscribers = Helpers::getSubscribersByProductId($updatedProductId);

        if($subscribers) {
            foreach($subscribers as $subscriber) {
                $subscriberId = $subscriber->ID;
                $this->sendInstockMail($subscriberId);
            }
        }
        return true;
    }
}
