<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

use \Inc\Services\SubscriberStatus;

/**
 * Implements the waitlist custom post type.
 * 
 * @since 1.0.0
 */
class PostTypes
{
    /**
	 * Post types.
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $postTypes = array();

    // /**
	//  * Post statuses.
	//  *
	//  * @since 1.0.0
    //  * 
	//  * @var array
	//  */
    // public $postStatuses;

    /**
	 * Columns in the custom post types admin page.
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $columns = array();

    /**
	 * BUild in columns to be removed in the custom post types admin page.
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $columnsToUnset = array();

    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        $this->setPostTypes();;
        $this->setColumns();
        $this->setColumnsToUnset();

        if(!empty($this->postTypes)) {
            add_action('init', array( $this, 'registerPostTypes'), 10);
        }

        if(!empty($this->columns) || !empty($this->columnsToUnset)) {
            foreach($this->postTypes as $postType) {
                add_action('manage_' . $postType['post_type'] . '_posts_columns', array($this, 'registerColumns' ));
                add_action('manage_' . $postType['post_type'] . '_posts_custom_column', array($this, 'registerColumnsData'), 10, 2);
                add_filter('manage_edit-' . $postType['post_type'] . '_sortable_columns', array($this, 'sortableColumns' ));
            }
        }
    }

    /**
	 * Populates the post types attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setPostTypes(): void 
    {
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

    /**
	 * Registers the custom post types.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function registerPostTypes(): void 
    {
        foreach($this->postTypes as $postType) {
            register_post_type( 
                $postType['post_type'], 
                [
                    'labels' => [
                        'name' => $postType['name'],
                        'singular_name' => $postType['singular_name'],
                        'add_new' => $postType['add_new'] ?? __('Add New', 'wpbits-waitlist'),
                        'add_new_item' => $postType['add_new_item'] ?? sprintf(__('Add New %s', 'wpbits-waitlist'), $postType['singular_name']),
                        'edit_item' => $postType['edit_item'] ?? sprintf(__('Add New %s', 'wpbits-waitlist'), $postType['singular_name']),
                        'new_item' => $postType['new_item'] ?? sprintf(__('New %s', 'wpbits-waitlist'), $postType['singular_name']),
                        'view_item' => $postType['view_item'] ?? sprintf(__('View %s', 'wpbits-waitlist'), $postType['singular_name']),
                        'view_items' => $postType['view_items'] ?? sprintf(__('View %s', 'wpbits-waitlist'), $postType['name']),
                        'search_items' => $postType['search_items'] ?? sprintf(__('Search %s', 'wpbits-waitlist'), $postType['name']),
                        'not_found' => $postType['not_found'] ?? sprintf(__('No %s found.', 'wpbits-waitlist'), $postType['singular_name']),
                        'not_found_in_trash' => $postType['not_found_in_trash'] ?? sprintf(__('No %s found in trash.', 'wpbits-waitlist'), $postType['singular_name']),
                        'parent_item_colon' => $postType['parent_item_colon'] ?? __('Parent Page','wpbits-waitlist'),
                        'all_items' => $postType['all_items'] ?? sprintf(__('%s', 'wpbits-waitlist'), $postType['name']),
                        'archives' => $postType['archives'] ?? sprintf(__('%s Archives', 'wpbits-waitlist'), $postType['name']),
                        'attributes' => $postType['attributes'] ?? sprintf(__('%s Attributes', 'wpbits-waitlist'), $postType['name']),
                        'insert_into_item' => $postType['insert_into_item'] ?? sprintf(__('Insert into %s', 'wpbits-waitlist'), $postType['singular_name']),
                        'uploaded_to_this_item' => $postType['uploaded_to_this_item'] ?? sprintf(__('Uploaded to this %s', 'wpbits-waitlist'), $postType['singular_name']),
                        'featured_image' => $postType['featured_image'] ?? __('Featured Image', 'wpbits-waitlist'),
                        'set_featured_image' => $postType['set_featured_image'] ?? __('Set featured image', 'wpbits-waitlist'),
                        'remove_featured_image' => $postType['remove_featured_image'] ?? __('Remove featured image', 'wpbits-waitlist'),
                        'use_featured_image' => $postType['use_featured_image'] ?? __('Use as featured image', 'wpbits-waitlist'),
                        'menu_name' => $postType['menu_name'] ?? $postType['name'],
                        'filter_items_list' => $postType['filter_items_list'] ?? sprintf(__('Filter %s', 'wpbits-waitlist'), $postType['name']),
                        'items_list_navigation' => $postType['items_list_navigation'] ?? sprintf(__('%s list navigation', 'wpbits-waitlist'), $postType['name']),
                        'items_list' => $postType['items_list'] ?? sprintf(__('%s list', 'wpbits-waitlist'), $postType['name']),
                        'item_published' => $postType['item_published'] ?? sprintf(__('%s published.', 'wpbits-waitlist'), $postType['singular_name']),
                        'item_published_privately' => $postType['item_published_privately'] ?? sprintf(__('%s published privately.', 'wpbits-waitlist'), $postType['singular_name']),
                        'item_reverted_to_draft' => $postType['item_reverted_to_draft'] ?? sprintf(__('%s reverted to draft.', 'wpbits-waitlist'), $postType['singular_name']),
                        'item_scheduled' => $postType['item_scheduled'] ?? sprintf(__('%s scheduled.', 'wpbits-waitlist'), $postType['singular_name']),
                        'item_updated' => $postType['item_updated'] ?? sprintf(__('%s updated.', 'wpbits-waitlist'), $postType['singular_name']),
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

    /**
	 * Populates the columns attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setColumns(): void
    {
        $this->columns = [
            'subscriber_email' => __('Email', 'wpbits-waitlist'),
            'status' => __('Status', 'wpbits-waitlist'),
            'product_id' => __('Product', 'wpbits-waitlist'),
            'mailsent_at' => __('Instock Mail Sent', 'wpbits-waitlist'),
            'subscribed_at' => __('Subscription Date', 'wpbits-waitlist'),
        ];
    }

    /**
	 * Populates the columnsToUnset attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setColumnsToUnset(): void 
    {
        $this->columnsToUnset = [
            'title',
            'date'
        ];
    }

    /**
	 * Registers the columns.
	 *
	 * @since 1.0.0
     * 
     * @param array $columns The columns in the post types admin page.
	 * @return void
	 */
    public function registerColumns(array $columns): array 
    {
        foreach($this->columns as $key => $value) {
            $columns[$key] = __($value);
        };

        foreach($this->columnsToUnset as $unset) {
            unset( $columns[$unset] );
        }; 

        return $columns;
    }

    /**
	 * Displays the data in the columns.
	 *
	 * @since 1.0.0
     * 
     * @param array $column A column in the post types admin page.
     * @param array $postId The id of teh post displayed in the column.
	 * @return void
	 */
    public function registerColumnsData(string $column, int $postId): void 
    {
        switch ($column) {
            case 'subscriber_email':
                echo get_post_meta($postId, '_wpbitswaitlist_email' , true);
                break;

            case 'status':
                $status = get_post_meta($postId, '_wpbitswaitlist_status' , true);

                $this->subscriberStatus = new SubscriberStatus();

                $index = array_search($status, array_column($this->subscriberStatus->statuses, 'name'));
                $statusToDisplay = $this->subscriberStatus->statuses[$index]['label'];
                $color = $this->subscriberStatus->statuses[$index]['color'];

                echo '<span class="wpbits-status-label" style="background-color:' . $color . '">' . $statusToDisplay . '</span>';
                break;

            case 'product_id':
                $productId = get_post_meta($postId, '_wpbitswaitlist_product_id' , true);
                $url = get_edit_post_link($productId);
                $product = wc_get_product($productId);

                $variationId = get_post_meta($postId, '_wpbitswaitlist_variation_id' , true);
                if($variationId) {
                    $product = wc_get_product($variationId);
                }

                echo '<a href="' . $url . '">#' . $productId . ' ' . $product->get_name() .'</a>';
                break;

            case 'mailsent_at':
                $unixTimeStamp = get_post_meta($postId, '_wpbitswaitlist_mailsent_at' , true);
                if($unixTimeStamp) {
                    $date = date_i18n(get_option('date_format'), strtotime($unixTimeStamp));
                    $time = date_i18n(get_option('time_format'), strtotime($unixTimeStamp));
                    echo $date . ' ' . $time;
                }
                break;

            case 'subscribed_at' :
                $unixTimeStamp = get_post_meta($postId, '_wpbitswaitlist_subscribed_at' , true);
                $date = date_i18n(get_option('date_format'), strtotime($unixTimeStamp));
                $time = date_i18n(get_option('time_format'), strtotime($unixTimeStamp));

                echo $date . ' ' . $time;
                break;

            default:
                break;      
        }
    }

    /**
	 * Makes the columns sortable.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function sortableColumns(array $columns): array 
    {
        foreach(array_keys($this->columns) as $key) {
            $columns[$key] = $key;
        }
        return $columns;
    }

}
