<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Services;

use \Inc\Api\Helpers;

class Mail
{
    public function register(): void
    {
        add_filter('wpbits_replace_shortcodes', array($this, 'replaceShortcodes'), 10, 2 );

        if( get_option('wpbits_waitlist_enable_instock_mail') ) {
            add_action( 'init', array($this, 'automaticInstockMails'), 10 );
        }
    }

    public function replaceShortcodes(string $text, int $subscriberId): string  
    {
        $productId = Helpers::getProductId($subscriberId);
        $productName = Helpers::getProductName($subscriberId);
        $productLink = Helpers::getProductLink($subscriberId);
        $productImage = Helpers::getProductImage($subscriberId);
        $subscriberEmail = Helpers::getSubscriberEmail($subscriberId);
        $shopName = Helpers::getShopName();

        $shortcodes = [ 
            '{product_id}', 
            '{product_name}', 
            '{product_link}',
            '{product_image}',
            '{subscriber_email}',
            '{shop_name}'
        ];

        $replacements = [ 
            $productId, 
            $productName, 
            $productLink,
            $productImage,
            $subscriberEmail,
            $shopName
        ];

        $filteredText = str_replace($shortcodes, $replacements, $text);
        return $filteredText;
    }

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
        $mailer = WC()->mailer();
        $mailer->send($to, $subject, $this->getMailTemplate( $subject, $message));
    }

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

    public function sendInstockMail(int $subscriberId): bool 
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
        return $mailSent;
    }

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
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $subscriberId = get_the_ID();
                    $mailSent = $this->sendInstockMail($subscriberId);
                    $this->updateSubscriberStatus($subscriberId , $mailSent);
                }
            }
        }
    }

    public function updateSubscriberStatus(int $subscriberId, bool $mailSent): string 
    {
        if(!$mailSent) {
            $subscriber = array(
                'ID' => $subscriberId,
                'post_status' => 'wpbits_failed'
            );
            wp_update_post($subscriber);
            return $subscriber['post_status'];
        }

        $subscriber = array(
            'ID' => $subscriberId,
            'post_status' => 'wpbits_mailsent'
        );

        wp_update_post($subscriber);
        update_post_meta($subscriberId, '_wpbitswaitlist_mailsent_at', date('Y-m-d H:i:s'));
        return $subscriber['post_status'];
    }
}
