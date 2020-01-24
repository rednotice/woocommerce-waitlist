<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

use \Inc\Base\Helpers;

/**
 * Handles the mails sent to the subscribers.
 * 
 * @since 1.0.0
 */
class Mail
{
    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        add_filter('wpbits_replace_shortcodes', array($this, 'replaceShortcodes'), 10, 2 );

        if( get_option('wpbits_waitlist_enable_instock_mail') ) {
            add_action('init', array($this, 'automaticInstockMails'), 10 );
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
        $lineBreak = Helpers::getLineBreak();

        $shortcodes = [ 
            '{product_id}', 
            '{product_name}', 
            '{product_link}',
            '{product_image}',
            '{subscriber_email}',
            '{shop_name}',
            '{line_break}'
        ];

        $replacements = [ 
            $productId, 
            $productName, 
            $productLink,
            $productImage,
            $subscriberEmail,
            $shopName,
            $lineBreak
        ];

        $filteredText = str_replace($shortcodes, $replacements, $text);
        return $filteredText;
    }

    /**
	 * Sends a success subscription mail to the subscriber.
	 *
	 * @since 1.0.0
     * 
     * @param int $subscriberId Id of the subscriber the mail will be send to.
	 * @return void
	 */
    public function sendSuccessSubscriptionMail(int $subscriberId ): void
    {
        $to = get_post_meta($subscriberId, '_wpbitswaitlist_email', true);
        $subject = apply_filters( 
            'wpbits_replace_shortcodes', 
            get_option('wpbits_waitlist_subscription_mail_subject'), 
            $subscriberId 
        );
        $message = apply_filters( 
            'wpbits_replace_shortcodes', 
            get_option('wpbits_waitlist_subscription_mail_message'), 
            $subscriberId 
        );
        $template = $this->getMailTemplate($subject, $message);

        if(get_option('wpbits_waitlist_subscription_mail_copy')) {
            $header = 'Bcc: ' . get_option('wpbits_waitlist_subscription_mail_copy');
        }

        $mailer = WC()->mailer();
        $mailer->send($to, $subject, $template, $header ?? '');
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
        if (function_exists('wc_get_template')) {
            do_action('woocommerce_email_header', $subject, null);
            echo $message;
            do_action('woocommerce_email_footer', get_option('woocommerce_email_footer_text'));
        } else {
            woocommerce_get_template('emails/email-header.php', array('email_heading' => $subject));
            echo $message;
            woocommerce_get_template('emails/email-footer.php');
        }
        return ob_get_clean();
    }

    /**
	 * Sends an instock mail to the subscriber.
	 *
	 * @since 1.0.0
     * 
     * @param int $subscriberId Id of the subscriber the mail will be send to.
	 * @return string Subscriber status.
	 */
    public function sendInstockMail(int $subscriberId): string 
    {
        $to = get_post_meta($subscriberId, '_wpbitswaitlist_email', true);
        $subject = apply_filters( 
            'wpbits_replace_shortcodes', 
            get_option('wpbits_waitlist_instock_mail_subject'), 
            $subscriberId 
        );
        $message = apply_filters( 
            'wpbits_replace_shortcodes', 
            get_option('wpbits_waitlist_instock_mail_message'), 
            $subscriberId 
        );

        $mailer = WC()->mailer();
        $mailSent = $mailer->send($to, $subject, $this->getMailTemplate($subject, $message));
        
        if(!$mailSent) {
            return Helpers::setStatusToFailed($subscriberId);
        }
        return Helpers::setStatusToMailSent($subscriberId);
    }

    /**
	 * Automatically sends instock mails to the subscribers of a product.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function automaticInstockMails(): void 
    {
        $subscribedProductIds = Helpers::getSubscribedProductIds();

        $backInStockProducts = [];
        foreach($subscribedProductIds as $productId) {
            if (wc_get_product($productId)->is_in_stock()) {
                $backInStockProducts[] = $productId;
            }
        }

        foreach($backInStockProducts as $backInStockProduct) {
            $query = Helpers::getSubscribersByProduct($backInStockProduct);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $subscriberId = get_the_ID();
                    $mailSent = $this->sendInstockMail($subscriberId);
                    if(!$mailSent) {
                        Helpers::setStatusToFailed($subscriberId);
                    } else {
                        Helpers::setStatusToMailSent($subscriberId);
                    }
                }
            }
        }
    }
}
