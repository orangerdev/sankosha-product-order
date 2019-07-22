<?php

namespace SNKPO\Admin;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Product {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    /**
     * Register custom post type
     * Hooked via action init, priority 999
     * @return  void
     */
    public function register_post_type() {
        $labels = [
    		'name'               => _x( 'Products', 'post type general name', 'snkpo' ),
    		'singular_name'      => _x( 'Product', 'post type singular name', 'snkpo' ),
    		'menu_name'          => _x( 'Products', 'admin menu', 'snkpo' ),
    		'name_admin_bar'     => _x( 'Product', 'add new on admin bar', 'snkpo' ),
    		'add_new'            => _x( 'Add New', 'product', 'snkpo' ),
    		'add_new_item'       => __( 'Add New Product', 'snkpo' ),
    		'new_item'           => __( 'New Product', 'snkpo' ),
    		'edit_item'          => __( 'Edit Product', 'snkpo' ),
    		'view_item'          => __( 'View Product', 'snkpo' ),
    		'all_items'          => __( 'All Products', 'snkpo' ),
    		'search_items'       => __( 'Search Products', 'snkpo' ),
    		'parent_item_colon'  => __( 'Parent Products:', 'snkpo' ),
    		'not_found'          => __( 'No products found.', 'snkpo' ),
    		'not_found_in_trash' => __( 'No products found in Trash.', 'snkpo' )
    	];

    	$args = [
    		'labels'             => $labels,
    		'description'        => __( 'Description.', 'snkpo' ),
    		'public'             => true,
    		'publicly_queryable' => true,
    		'show_ui'            => true,
    		'show_in_menu'       => true,
    		'query_var'          => true,
    		'rewrite'            => array( 'slug' => 'snkpo-product' ),
    		'capability_type'    => 'post',
    		'has_archive'        => true,
    		'hierarchical'       => false,
    		'menu_position'      => null,
    		'supports'           => array( 'title', 'editor', 'thumbnail' )
    	];

    	register_post_type( 'snkpo-product', $args );
    }

    /**
     * Setup carbon field option for product
     * Hooked via action carbon_fields_register_fields, priority 999
     * @return  void
     */
    public function set_post_options() {
        Container::make('post_meta',__('Product Data','snkpo'))
            ->where('post_type',  '=', 'snkpo-product')
            ->add_fields([
				Field::make('text'  ,'price',           	__('Price'  ,'snkpo')),
				Field::make('textarea'  ,'location',           	__('Location'  ,'snkpo')),
				Field::make( 'media_gallery', 'gallery', __( 'Gallery','snkpo' ) )
					->set_type( array( 'image' ) ),
                Field::make('text'  ,'leadtime_fewer_then_ok',      __('Leadtime Day','snkpo'))
                    ->set_attribute('type','number')
                    ->set_attribute('min',0)
                    ->set_default_value(7)
                    ->set_width(33)
                    ->set_help_text(__(' Stock OK >= Order ','snkpo')),
                Field::make('text'  ,'leadtime_fewer_then_total',   __('Leadtime Day','snkpo'))
                    ->set_attribute('type','number')
                    ->set_attribute('min',0)
                    ->set_default_value(14)
                    ->set_width(33)
                    ->set_help_text( __('Total Stock >= Order ','snkpo')),
                Field::make('text'  ,'leadtime_more_then_total',    __('Leadtime Day','snkpo'))
                    ->set_attribute('type','number')
                    ->set_attribute('min',0)
                    ->set_default_value(30)
                    ->set_width(33)
                    ->set_help_text(__('Total Stock <= Order','snkpo'))
            ]);
    }

    /**
     * Set custom column into product table
     * Hooked via filter manage_snkpo-product_posts_columns, priority 999
     * @param   array   columns
     * @return  array
     */
    public function set_table_columns(array $columns) {
		$columns['price']            = __('Price','snkpo');
        $columns['stock_ok']         = __('Stock OK','snkpo');
        $columns['stock_unschedule'] = __('Stock Unschedule','snkpo');
		$columns['leadtime']         = __('Leadtime Day','snkpo');
		$columns['manage_stock']     = __('Manage Stock','snkpo');

		unset($columns['date']);

        return $columns;
    }

    /**
     * Display the product data
     * Hooked via action manage_snkpo-product_posts_custom_column, priority 999
     * @param  array  $column
     * @param  int    $post_id
     * @return void
     */
    public function display_data_in_table(string $column, int $post_id) {
		
		$stock = snkpo_get_stock( $post_id );

		switch($column) :
			case 'price' :
				echo snkpo_money_format( carbon_get_post_meta($post_id, 'price') );
				break;
			case 'stock_ok' :
				echo $stock['ok'];
				break;
			case 'stock_unschedule' :
				echo $stock['uns'];
				break;
			case 'leadtime' :
				printf(__('%s / %s / %s','snkpo'),
					carbon_get_post_meta($post_id,'leadtime_fewer_then_ok'),
					carbon_get_post_meta($post_id,'leadtime_fewer_then_total'),
					carbon_get_post_meta($post_id,'leadtime_more_then_total')
				);
				break;
			case 'manage_stock' :
				include 'partials/manage-stock.php';
				break;
		endswitch;
	}
	
	public function display_manage_stock_popup() {

		include 'partials/manage-stock-popup.php';
		
	}

	public function ajax_add_stock() {

		if ( isset( $_POST['add_stock_submit'] ) && wp_verify_nonce( $_POST['add_stock_submit'] , 'add-stock-nonce' ) ) :

			$request = wp_parse_args( $_POST, [
				'stock_ok' => 0,
				'stock_unschedule' => 0,
				'comment' => 'Manual Add',
				'product_id' => 0,
			] );

			$errors = [];

			if ( empty( $request['product_id'] ) ) :
				$errors[] = __('Product id required','snkpo');
			endif;

			if ( $errors ) :
				wp_send_json_error( $errors );
			endif;

			$data = [
				'stock_ok' => $request['stock_ok'],
				'stock_unschedule' => $request['stock_unschedule'],
				'comment' => $request['comment']
			];

			add_post_meta( $request['product_id'], 'stock', $data );

			wp_send_json_success([__('Add stock success','snkpo')]);

		endif;

	}

	public function ajax_reduce_stock() {

		if ( isset( $_POST['reduce_stock_submit'] ) && wp_verify_nonce( $_POST['reduce_stock_submit'] , 'reduce-stock-nonce' ) ) :

			$request = wp_parse_args( $_POST, [
				'stock_ok' => 0,
				'stock_unschedule' => 0,
				'comment' => 'Manual Add',
				'product_id' => 0,
			] );

			$errors = [];

			if ( empty( $request['product_id'] ) ) :
				$errors[] = __('Product id required','snkpo');
			endif;

			if ( $errors ) :
				wp_send_json_error( $errors );
			endif;

			if ( $request['stock_ok'] > 0 ) :
				$request['stock_ok'] = -$request['stock_ok'];
			endif;

			if ( $request['stock_unschedule'] > 0 ) :
				$request['stock_unschedule'] = -$request['stock_unschedule'];
			endif;

			$data = [
				'stock_ok' => $request['stock_ok'],
				'stock_unschedule' => $request['stock_unschedule'],
				'comment' => $request['comment']
			];

			add_post_meta( $request['product_id'], 'stock', $data );

			wp_send_json_success([__('Reduce stock success','snkpo')]);

		endif;

	}
}
