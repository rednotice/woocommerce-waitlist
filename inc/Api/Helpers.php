<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Api;

class Helpers
{
    public static function getSubscribedProductIds() {
        $productIds= [];

        $args = [ 
            'post_type' => 'wpbitswaitlist', 
            'post_status' => 'wpbits_subscribed',
        ];
        $query = new \WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $postId = get_the_ID();
                if( get_post_meta( $postId, '_wpbitswaitlist_variation_id' , true ) ) {
                    $productId = get_post_meta( $postId, '_wpbitswaitlist_variation_id' , true );
                } else {
                    $productId = get_post_meta( $postId, '_wpbitswaitlist_product_id' , true );
                }
                $productIds[] = trim( $productId );
            }
        }

        return array_unique( $productIds );
    }

    public static function getSubscribersByProduct( $productId ) {
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

        $query = new \WP_Query( $args );
        return $query;
    }

    public static function saveSubscriber( $email, $productId, $variationId = null ) {
        $meta = [
            '_wpbitswaitlist_email' => $email,
            '_wpbitswaitlist_product_id' => $productId,
            '_wpbitswaitlist_variation_id' => ( $variationId ?? null ),
            '_wpbitswaitlist_subscribed_at' => date('Y-m-d H:i:s'),
            '_wpbitswaitlist_mailsent_at' => null
        ];

        $postData = [
            'post_title' => $email,
            'post_status' => 'wpbits_subscribed',
            'post_type' => 'wpbitswaitlist',
            'meta_input' => $meta
        ];

        $postId = wp_insert_post( $postData );
        return $postId;
    }

    public static function isSubscribed( $email, $productId, $variationId = null ) {
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

        $query = new \WP_Query( $args );

        if( ! empty( $query->have_posts() ) ) {
            return true;
        }
        return false;
    }

    public static function getSubscriberEmail( int $subscriberId ) {
        return get_post_meta( $subscriberId, '_wpbitswaitlist_email', true );
    }

    public static function getProductId( int $subscriberId ) {
        if( get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true ) ) {
            return get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true );
        }
        return get_post_meta( $subscriberId, '_wpbitswaitlist_product_id', true );
    }

    public static function getProductName( int $subscriberId ) {
        if( get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true ) ) {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true );
        } else {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_product_id', true );
        }
        $product = wc_get_product( $productId );
        return $product->get_name();
    }

    public static function getProductLink( int $subscriberId ) {
        if( get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true ) ) {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true );
        } else {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_product_id', true );
        }
        return get_permalink( $productId );
    }

    public static function getProductImage( int $subscriberId ) {
        if( get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true ) ) {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_variation_id', true );
        } else {
            $productId = get_post_meta( $subscriberId, '_wpbitswaitlist_product_id', true );
        }
        $productImage = wc_get_product( $productId )->get_image();
        return $productImage;
    }

    public static function getShopName() {
        return get_bloginfo('name');
    }

}