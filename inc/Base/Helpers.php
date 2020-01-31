<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Base;

/**
 * Helper functions.
 *
 * @since 1.0.0
 */
class Helpers
{
    /**
     * @since 1.0.0
     * 
     * @return array $productIds
     */
    public static function getAllSubscribedProductIds(): array
    {
        $productIds= [];

        $args = [ 
            'post_type' => 'wpbitswaitlist', 
            'post_status' => 'wpbits_subscribed',
        ];
        $query = new \WP_Query($args);

        if($query->have_posts()) {
            while($query->have_posts()) {
                $query->the_post();
                $postId = get_the_ID();
                if(get_post_meta($postId, '_wpbitswaitlist_variation_id' , true)) {
                    $productId = get_post_meta($postId, '_wpbitswaitlist_variation_id' , true);
                } else {
                    $productId = get_post_meta($postId, '_wpbitswaitlist_product_id' , true);
                }
                $productIds[] = trim($productId);
            }
        }

        return array_unique($productIds);
    }

    /**
     * @since 1.0.0
     * 
     * @return array All subscribers.
     */
    public static function getAllSubscribers(): array 
    {
        $args = [
            'post_type' => 'wpbitswaitlist', 
            'posts_per_page' => -1
        ];

        $query = new \WP_Query($args);
        return $query->posts;
    }

    /**
     * @since 1.0.0
     * 
     * @param int $productId Product id.
     * @return array Subscribers.
     */
    public static function getSubscribersByProductId(int $productId): array 
    {
        $args = [ 
            'post_type' => 'wpbitswaitlist', 
            'post_status' => 'wpbits_subscribed',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => '_wpbitswaitlist_product_id',
                    'value' => $productId
                ),
                array(
                    'key' => '_wpbitswaitlist_variation_id',
                    'value' => $productId
                )
            )
        ];

        $query = new \WP_Query($args);
        return $query->posts;;
    }

    /**
     * @since 1.0.0
     * 
     * @param string $email Subscriber email.
     * @param string $status (optional) Subscriber status (efault: 'wpbits_subscribed').
     * @param string $fields (optional) Return fields. Param 'all' returns all fields, 
     *  param 'ids' returns only the ids (default: 'all').
     * @return array Subscribers.
     */
    public static function getSubscribersByEmail(string $email, string $status = 'wpbits_subscribed', string $fields = 'all'): array 
    {
        $args = [ 
            'post_type' => 'wpbitswaitlist',
            'post_status' => $status,
            'fields' => $fields,
            'meta_query' => array(
                array(
                    'key' => '_wpbitswaitlist_email',
                    'value' => $email
                )
            )
        ];

        $query = new \WP_Query($args);
        return $query->posts;
    }

    /**
     * @since 1.0.0
     * 
     * @param string $email
     * @param int $productId
     * @param int $variationId
     * @return int $subscriberId
     */
    public static function saveSubscriber($email, $productId, $variationId = null): int
    {
        $meta = [
            '_wpbitswaitlist_email' => $email,
            '_wpbitswaitlist_product_id' => $productId,
            '_wpbitswaitlist_variation_id' => ( $variationId ?? null ),
            '_wpbitswaitlist_subscribed_at' => date('Y-m-d H:i:s'),
            '_wpbitswaitlist_mailsent_at' => null
        ];

        $data = [
            'post_title' => $email,
            'post_status' => 'wpbits_subscribed',
            'post_type' => 'wpbitswaitlist',
            'meta_input' => $meta
        ];

        $subscriberId = wp_insert_post($data);
        return $subscriberId;
    }

    /**
     * @since 1.0.0
     * 
     * @param string $email
     * @param int $productId
     * @param int $variationId
     * @return bool
     */
    public static function isSubscribed($email, $productId, $variationId = null): bool
    {
        $args = array(
            'post_type'  => 'wpbitswaitlist',
            'post_status' => 'wpbits_subscribed',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => '_wpbitswaitlist_email',
                    'value'   => $email,
                    'compare' => '='
                ),
                array(
                    'key'     => '_wpbitswaitlist_product_id',
                    'value'   => $productId,
                    'compare' => '='
                ),
                (isset( $variationId ) ?
                    array(
                    'key'     => '_wpbitswaitlist_variation_id',
                    'value'   => $variationId,
                    'compare' => '='
                    ) : ''
                )
            )
        );

        $query = new \WP_Query($args);

        if(!empty($query->have_posts())) {
            return true;
        }
        return false;
    }

    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return string $subscriberEmail
     */
    public static function getSubscriberEmail(int $subscriberId): string
    {
        return get_post_meta($subscriberId, '_wpbitswaitlist_email', true);
    }

    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return string 
     */
    public static function getSubscriptionDate(int $subscriberId): string
    {

        return get_post_meta($subscriberId, '_wpbitswaitlist_subscribed_at', true);
    }

    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return string|null
     */
    public static function getInstockMailDate(int $subscriberId): ?string
    {

        return get_post_meta($subscriberId, '_wpbitswaitlist_mailsent_at', true);
    }


    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return int $productId
     */
    public static function getProductId(int $subscriberId): int
    {
        if( get_post_meta($subscriberId, '_wpbitswaitlist_variation_id', true )) {
            return get_post_meta($subscriberId, '_wpbitswaitlist_variation_id', true);
        }
        return get_post_meta($subscriberId, '_wpbitswaitlist_product_id', true);
    }

    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return string $productName
     */
    public static function getProductName(int $subscriberId): string
    {
        if( get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true ) ) {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true );
        } else {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_product_id', true );
        }
        $product = wc_get_product( $productId );
        return $product->get_name();
    }

    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return string $productPermalink
     */
    public static function getProductLink(int $subscriberId): string
    {
        if( get_post_meta($subscriberId, '_wpbitswaitlist_variation_id', true)) {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true);
        } else {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_product_id', true);
        }
        return get_permalink($productId);
    }

    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return string $productImage
     */
    public static function getProductImage(int $subscriberId): string
    {
        if( get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true ) ) {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true );
        } else {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_product_id', true );
        }
        $productImage = wc_get_product( $productId )->get_image();
        return $productImage;
    }

    /**
     * @since 1.0.0
     * 
     * @return string Shop name.
     */
    public static function getShopName(): string 
    {
        return get_bloginfo('name');
    }
}