<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Services;

class PostTypes
{
    public $postTypes = array();

    public $postStatuses;

    public $columns = array();

    public $columnsToUnset = array();

    public function register() {
        $this->setPostTypes();
        $this->setPostStatuses();
        $this->setColumns();
        $this->setColumnsToUnset();

        if( ! empty( $this->postTypes ) ) {
            add_action( 'init', array( $this, 'registerPostTypes' ), 10 );
        }

        if( ! empty( $this->postStatuses ) ) {
            add_action( 'init', array( $this, 'registerPostStatuses' ), 10 );
        }

        if( ! empty( $this->columns ) || ! empty( $this->columnsToUnset ) ) {
            foreach( $this->postTypes as $postType ) {
                add_action('manage_' . $postType['post_type'] . '_posts_columns', array( $this, 'registerColumns' ) );
                add_action('manage_' . $postType['post_type'] . '_posts_custom_column', array( $this, 'registerColumnsData' ), 10, 2 );
                add_filter( 'manage_edit-' . $postType['post_type'] . '_sortable_columns', array( $this, 'sortableColumns' ) );
            }
        }
    }

    public function setPostTypes() {
        $this->postTypes[] = [
            'post_type' => 'wpbitswaitlist',
            'name' => 'Waitlist',
            'singular_name' => 'Subscriber',
            'public' => true,
            'publicly_queryable' => false,
            'has_archive' => false,
            'show_ui' => true,
            'show_in_menu' => 'wpbits_waitlist',
            'show_in_admin_bar' => false,
            'capabilities' => [
                'create_posts' => false
            ],
            'map_meta_cap' => true,
            'supports' => [
                'title',
                'editor',
                'revisions' 
            ],
        ];
    }

    public function registerPostTypes() {
        foreach( $this->postTypes as $postType ) {
            register_post_type( 
                $postType['post_type'], 
                [
                    'labels' => [
                        'name' => $postType['name'],
                        'singular_name' => $postType['singular_name'],
                        'add_new' => $postType['add_new'] ?? 'Add New',
                        'add_new_item' => $postType['add_new_item'] ?? 'Add New ' . $postType['singular_name'],
                        'edit_item' => $postType['edit_item'] ?? 'Edit ' . $postType['singular_name'],
                        'new_item' => $postType['new_item'] ?? 'New ' . $postType['singular_name'],
                        'view_item' => $postType['view_item'] ?? 'View ' . $postType['singular_name'],
                        'view_items' => $postType['view_items'] ?? 'View ' . $postType['name'],
                        'search_items' => $postType['search_items'] ?? 'Search ' . $postType['name'],
                        'not_found' => $postType['not_found'] ?? 'No ' . $postType['singular_name'] . ' found.',
                        'not_found_in_trash' => $postType['not_found_in_trash'] ?? 'No ' . $postType['singular_name'] . ' found in trash',
                        'parent_item_colon' => $postType['parent_item_colon'] ?? 'Parent Page',
                        'all_items' => $postType['all_items'] ?? $postType['name'],
                        'archives' => $postType['archives'] ?? $postType['name'] . ' Archives',
                        'attributes' => $postType['attributes'] ?? $postType['singular_name'] . ' Attributes',
                        'insert_into_item' => $postType['insert_into_item'] ?? 'Insert into' . $postType['singular_name'],
                        'uploaded_to_this_item' => $postType['uploaded_to_this_item'] ?? 'Upload to this ' . $postType['singular_name'],
                        'featured_image' => $postType['featured_image'] ?? 'Featured Image',
                        'set_featured_image' => $postType['set_featured_image'] ?? 'Set featured image',
                        'remove_featured_image' => $postType['remove_featured_image'] ?? 'Remove featured image',
                        'use_featured_image' => $postType['use_featured_image'] ?? 'Use as featured image',
                        'menu_name' => $postType['menu_name'] ?? $postType['name'],
                        'filter_items_list' => $postType['filter_items_list'] ?? 'Filter ' . $postType['name'],
                        'items_list_navigation' => $postType['items_list_navigation'] ?? $postType['name'] . ' navigation',
                        'items_list' => $postType['items_list'] ?? $postType['name'],
                        'item_published' => $postType['item_published'] ?? $postType['singular_name'] . ' published.',
                        'item_published_privately' => $postType['item_published_privately'] ?? $postType['singular_name'] . ' published privately.',
                        'item_reverted_to_draft' => $postType['item_reverted_to_draft'] ?? $postType['singular_name'] . ' reverted to draft.',
                        'item_scheduled' => $postType['item_scheduled'] ?? $postType['singular_name'] . ' scheduled.',
                        'item_updated' => $postType['item_updated'] ?? $postType['singular_name'] . ' updated.',
                    ],
                    'public' => $postType['public'] ?? false,
                    'publicly_queryable' => $postType['publicly_queryable'] ?? $postType['public'],
                    'has_archive' => $postType['has_archive'] ?? false,
                    'show_ui' => $postType['show_ui'] ?? $postType['public'],
                    'show_in_menu' => $postType['show_in_menu'] ?? $postType['show_ui'],
                    'capabilities' => $postType['capabilities'] ?? [],
                    'map_meta_cap' => $postType['map_meta_cap'] ?? false,
                    'supports' => $postType['supports'] ?? []
                ]
            );
        }
    }

    public function setPostStatuses() {
        $this->postStatuses = [
            [
                'post_status' => 'wpbits_subscribed',
                'args' => [
                    'label' => 'Subscribed',
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'show_in_admin_status_list' =>  true,
                    'label_count'               => _n_noop( 'Subscribed <span class="count">(%s)</span>', 'Subscribed <span class="count">(%s)</span>' ),
                ]
            ],
            [
                'post_status' => 'wpbits_unsubscribed',
                'args' => [
                    'label' => 'Unsubscribed',
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'show_in_admin_status_list' =>  true,
                    'label_count'               => _n_noop( 'Unsubscribed <span class="count">(%s)</span>', 'Unsubscribed <span class="count">(%s)</span>' ),
                ]
            ],
            [
                'post_status' => 'wpbits_mailsent',
                'args' => [
                    'label' => 'Mail Sent',
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'show_in_admin_status_list' =>  true,
                    'label_count'               => _n_noop( 'Mail Sent <span class="count">(%s)</span>', 'Mail Sent <span class="count">(%s)</span>' ),
                ]
            ],
            [
                'post_status' => 'wpbits_failed',
                'args' => [
                    'label' => 'Failed',
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'show_in_admin_status_list' =>  true,
                    'label_count'               => _n_noop( 'Failed <span class="count">(%s)</span>', 'Failed <span class="count">(%s)</span>' ),
                ]
            ]
        ];
    }

    public function registerPostStatuses() {
        foreach( $this->postStatuses as $postStatus ) {
            register_post_status($postStatus['post_status'], $postStatus['args']);
        }
    }

    public function setColumns() {
        $this->columns = [
            'subscriber_email' => 'Email',
            'status' => 'Status',
            'product_id' => 'Product',
            'mailsent_at' => 'Mail sent on',
            'subscribed_at' => 'Subscribed on'
        ];
    }

    public function setColumnsToUnset() {
        $this->columnsToUnset = [
            'title',
            'date'
        ];
    }

    public function registerColumns( $columns ) {
        foreach( $this->columns as $key => $value ) {
            $columns[$key] = __($value);
        };

        foreach( $this->columnsToUnset as $unset ) {
            unset( $columns[$unset] );
        }; 

        return $columns;
    }

    public function registerColumnsData( $column, $post_id) {
        // $postMeta = get_post_meta( $post_id, '_wpbitswaitlist_email' , true);

        switch ( $column ) {
            case 'subscriber_email' :
                echo get_post_meta( $post_id, '_wpbitswaitlist_email' , true);
            break;

            case 'status' :
                $status = str_replace( 'wpbits_', '', get_post_status( $post_id ) );
                echo $status;
            break;

            case 'product_id' :
                $productId = get_post_meta( $post_id, '_wpbitswaitlist_product_id' , true);
                $url = get_permalink( $productId );
                $product = wc_get_product( $productId );

                $variationId = get_post_meta( $post_id, '_wpbitswaitlist_variation_id' , true);
                if( $variationId ) {
                    $product = wc_get_product( $variationId );
                }

                echo '<a href="' . $url . '">#' . $productId . ' ' . $product->get_name() .'</a>';
            break;

            case 'mailsent_at' :
                echo get_post_meta( $post_id, '_wpbitswaitlist_mailsent_at' , true);
            break;

            case 'subscribed_at' :
                echo get_post_meta( $post_id, '_wpbitswaitlist_subscribed_at' , true);
            break;

            default :
            break;      
        }
    }

    public function sortableColumns( $columns ) {
        foreach( array_keys($this->columns) as $key ) {
            $columns[$key] = $key;
        }

        return $columns;
    }

}