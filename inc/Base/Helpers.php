<?php
/**
 * @package pixelbaseWaitlist
 * 
 * @since 1.0.0
 */

namespace PixelBase\Base;

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
            'post_type' => 'pxbwaitlist', 
            'meta_query' => array(
                array(
                    'key' => '_pxbwaitlist_status',
                    'value' => 'pxb_subscribed'
                )
            )
        ];
        $query = new \WP_Query($args);

        if($query->have_posts()) {
            while($query->have_posts()) {
                $query->the_post();
                $postId = get_the_ID();
                if(get_post_meta($postId, '_pxbwaitlist_variation_id' , true)) {
                    $productId = get_post_meta($postId, '_pxbwaitlist_variation_id' , true);
                } else {
                    $productId = get_post_meta($postId, '_pxbwaitlist_product_id' , true);
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
            'post_type' => 'pxbwaitlist', 
            'posts_per_page' => -1
        ];

        $query = new \WP_Query($args);
        return $query->posts;
    }

    /**
     * @since 1.0.0
     * 
     * @param int $productId Product id.
     * @param string $status (optional) Subscriber status (default: "pxb_subscribed".)
     * @return array Subscribers.
     */
    public static function getSubscribersByProductId(int $productId, string $status = 'pxb_subscribed'): array 
    {
        $args = [ 
            'post_type' => 'pxbwaitlist', 
            'post_status' => $status,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => '_pxbwaitlist_product_id',
                    'value' => $productId
                ),
                array(
                    'key' => '_pxbwaitlist_variation_id',
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
     * @param string $status (optional) Subscriber status (default: 'pxb_subscribed').
     * @param string $fields (optional) Return fields. Param 'all' returns all fields, 
     *  param 'ids' returns only the ids (default: 'all').
     * @return array Subscribers.
     */
    public static function getSubscribersByEmail(string $email, string $status = 'pxb_subscribed', string $fields = 'all'): array 
    {
        $args = [ 
            'post_type' => 'pxbwaitlist',
            'post_status' => $status,
            'fields' => $fields,
            'meta_query' => array(
                array(
                    'key' => '_pxbwaitlist_email',
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
            '_pxbwaitlist_email' => $email,
            '_pxbwaitlist_product_id' => $productId,
            '_pxbwaitlist_variation_id' => ($variationId ?? null),
            '_pxbwaitlist_subscribed_at' => date('Y-m-d H:i:s'),
            '_pxbwaitlist_mailsent_at' => null
        ];

        $data = [
            'post_title' => $email,
            'post_status' => 'pxb_subscribed',
            'post_type' => 'pxbwaitlist',
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
            'post_type'  => 'pxbwaitlist',
            'post_status' => 'pxb_subscribed',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => '_pxbwaitlist_email',
                    'value'   => $email,
                    'compare' => '='
                ),
                array(
                    'key'     => '_pxbwaitlist_product_id',
                    'value'   => $productId,
                    'compare' => '='
                ),
                (isset( $variationId ) ?
                    array(
                    'key'     => '_pxbwaitlist_variation_id',
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
        return get_post_meta($subscriberId, '_pxbwaitlist_email', true);
    }

    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return string Subscriber status.
     */
    public static function getSubscriberStatus(int $subscriberId): string
    {
        return get_post_status($subscriberId);
    }

    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return string 
     */
    public static function getSubscriptionDate(int $subscriberId): string
    {

        return get_post_meta($subscriberId, '_pxbwaitlist_subscribed_at', true);
    }

    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return string|null
     */
    public static function getInstockMailDate(int $subscriberId): ?string
    {

        return get_post_meta($subscriberId, '_pxbwaitlist_mailsent_at', true);
    }


    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return int $productId
     */
    public static function getProductId(int $subscriberId): int
    {
        if( get_post_meta($subscriberId, '_pxbwaitlist_variation_id', true )) {
            return get_post_meta($subscriberId, '_pxbwaitlist_variation_id', true);
        }
        return get_post_meta($subscriberId, '_pxbwaitlist_product_id', true);
    }

    /**
     * @since 1.0.0
     * 
     * @param int $subscriberId
     * @return string $productName
     */
    public static function getProductName(int $subscriberId): string
    {
        if( get_post_meta( $subscriberId, '_pxbwaitlist_variation_id', true ) ) {
            $productId = get_post_meta( $subscriberId, '_pxbwaitlist_variation_id', true );
        } else {
            $productId = get_post_meta( $subscriberId, '_pxbwaitlist_product_id', true );
        }
        $product = wc_get_product( $productId );

        if(!$product) {
            return false;
        }

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
        if(get_post_meta($subscriberId, '_pxbwaitlist_variation_id', true)) {
            $productId = get_post_meta( $subscriberId, '_pxbwaitlist_variation_id', true);
        } else {
            $productId = get_post_meta( $subscriberId, '_pxbwaitlist_product_id', true);
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
        if( get_post_meta( $subscriberId, '_pxbwaitlist_variation_id', true ) ) {
            $productId = get_post_meta( $subscriberId, '_pxbwaitlist_variation_id', true );
        } else {
            $productId = get_post_meta( $subscriberId, '_pxbwaitlist_product_id', true );
        }

        $product = wc_get_product( $productId );
        if(!$product || !$product->get_image()) {
            return false;
        }

        return $product->get_image();
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