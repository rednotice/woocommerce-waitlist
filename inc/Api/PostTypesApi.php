<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Api;

class PostTypesApi
{
    public $postTypes = array();

    public $columns = array();

    public $columnsToUnset = array();

    public function register() {
        if( ! empty( $this->postTypes ) ) {
            add_action( 'init', array( $this, 'registerCpt' ), 10 );
        }

        if( ! empty( $this->columns ) || ! empty( $this->columnsToUnset ) ) {
            foreach( $this->postTypes as $postType ) {
                add_action('manage_' . $postType['post_type'] . '_posts_columns', array( $this, 'addColumns' ) );
                add_action('manage_' . $postType['post_type'] . '_posts_custom_column', array( $this, 'generateColumnsData' ), 10, 2 );
                add_filter( 'manage_edit-' . $postType['post_type'] . '_sortable_columns', array( $this, 'sortableColumns' ) );
            }
        }
    }

    public function setPostTypes( array $postTypes ) {
        $this->postTypes = $postTypes;
        return $this;
    }

    public function setColumns( array $columns ) {
        $this->columns = $columns;
        return $this;
    }

    public function setColumnsToUnset ( array $columnsToUnset ) {
        $this->columnsToUnset = $columnsToUnset;
        return $this;
    }

    public function registerCpt() {
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
                    'supports' => $postType['supports'] ?? []
                ]
            );
        }
    }

    public function addColumns( $columns ) {
        foreach( $this->columns as $key => $value ) {
            $columns[$key] = __($value);
        };

        foreach( $this->columnsToUnset as $unset ) {
            unset( $columns[$unset] );
        }; 

        return $columns;
    }

    public function generateColumnsData( $column, $post_id) {
        $postMeta = get_post_meta( $post_id, '_wpbitswaitlist' , true);

        switch ( $column ) {
            case $column :
                if( $column === 'product_id') {
                    $url = get_permalink( $postMeta['product_id'] );
                    $product = wc_get_product( $postMeta['product_id'] );

                    if( $postMeta['variation_id'] ) {
                        $product = wc_get_product( $postMeta['variation_id'] );
                    }

                    echo '<a href="' . $url . '">#' . $postMeta[$column] . ' ' . $product->get_name() .'</a>';
                    break;
                }
                echo $postMeta[$column];
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