<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

/**
 * Implements a product filter dropdown menu on the waitlist custom post type admin page.
 * 
 * @since 1.0.0
 */
class Filter
{
    /**
	 * Dropdown menu options for the product filter.
	 *
	 * @since 1.0.0
     * 
	 * @var array
	 */
    public $options = array();

    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void 
    {
        add_action('init', array( $this, 'setOptions'), 10);
        add_action('restrict_manage_posts', array( $this, 'registerProductFilter' ), 10);
        add_filter('parse_query', array( $this, 'filterProducts'), 10);
    }

    /**
	 * Populates the options attribute.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function setOptions(): void
    {
        $productIds= [];

        $query = new \WP_Query('post_type=wpbitswaitlist');
        if($query->have_posts()) {
            while($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $meta = get_post_meta($post_id, '_wpbitswaitlist_product_id' , true);
                $productIds[] = trim($meta);
            }
        }

        $productIds = array_unique($productIds);

        foreach($productIds as $productId) {
            $productName = wc_get_product($productId)->get_name();
            $this->options[] = [
                'id' => $productId,
                'name' => $productName
            ];
        }
    }

    /**
	 * Registers the product filter.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function registerProductFilter(): void
    {
        global $typenow;
            if($typenow == 'wpbitswaitlist') {
                $options = $this->options; // Options for the filter select field
                $currentOption = '';
            if(isset($_GET['slug'])) {
                $currentOption = $_GET['slug']; // Check if option has been selected
            } ?>
            <select name="slug" id="slug">
                <option value="all" <?php selected('all', $currentOption); ?>>
                    <?php _e('All Products', 'wpbits-waitlist'); ?>
                </option>
                <?php foreach($options as $option) { ?>
                    <option 
                        value="<?php echo esc_attr($option['id']); ?>"
                        <?php selected($option['id'], $currentOption ); ?>
                    >
                        <?php echo '#' . esc_attr($option['id']) . ' ' . esc_attr($option['name']); ?>
                    </option>
                <?php } ?>
            </select>
        <?php }
    }

    /**
	 * Filters the products according to the option passed
     * by the registerProductFilter function.
	 *
	 * @since 1.0.0
     * 
     * @param object $query Instance of the WP_Query class.
	 * @return void
	 */
    public function filterProducts(object $query): void
    {
        global $pagenow;
        $post_type = $_GET['post_type'] ?? '';
        if ( is_admin() 
            && $pagenow === 'edit.php' 
            && $post_type === 'wpbitswaitlist' 
            && isset($_GET['slug'])
            && $_GET['slug'] !='all' ) {
            $query->query_vars['meta_key'] = '_wpbitswaitlist_product_id';
            $query->query_vars['meta_value'] = $_GET['slug'];
            $query->query_vars['meta_compare'] = '=';
        }
    }
}
