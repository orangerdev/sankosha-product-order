<?php

namespace SNKPO\Admin;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Order {

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
    		'name'               => _x( 'Orders', 'post type general name', 'snkpo' ),
    		'singular_name'      => _x( 'Order', 'post type singular name', 'snkpo' ),
    		'menu_name'          => _x( 'Orders', 'admin menu', 'snkpo' ),
    		'name_admin_bar'     => _x( 'Order', 'add new on admin bar', 'snkpo' ),
    		'add_new'            => _x( 'Add New', 'order', 'snkpo' ),
    		'add_new_item'       => __( 'Add New Order', 'snkpo' ),
    		'new_item'           => __( 'New Order', 'snkpo' ),
    		'edit_item'          => __( 'Edit Order', 'snkpo' ),
    		'view_item'          => __( 'View Order', 'snkpo' ),
    		'all_items'          => __( 'All Orders', 'snkpo' ),
    		'search_items'       => __( 'Search Orders', 'snkpo' ),
    		'parent_item_colon'  => __( 'Parent Orders:', 'snkpo' ),
    		'not_found'          => __( 'No orders found.', 'snkpo' ),
    		'not_found_in_trash' => __( 'No orders found in Trash.', 'snkpo' )
    	];

    	$args = [
    		'labels'             => $labels,
    		'description'        => __( 'Description.', 'snkpo' ),
    		'public'             => true,
    		'publicly_queryable' => true,
    		'show_ui'            => true,
    		'show_in_menu'       => true,
    		'query_var'          => true,
    		'rewrite'            => array( 'slug' => 'order' ),
    		'capability_type'    => 'post',
    		'has_archive'        => true,
    		'hierarchical'       => false,
    		'menu_position'      => null,
			'supports'           => array( 'title', 'thumbnail' )
    	];

    	register_post_type( 'snkpo-order', $args );
    }

	/**
     * Set custom column into order table
     * Hooked via filter manage_snkpo-order_posts_columns, priority 999
     * @param   array   columns
     * @return  array
     */
    public function set_table_columns(array $columns) {
		$columns = [
			'cb'			=> '<input type="checkbox">',
			'title'			=> __('Title','snkpo'),
			'order_by'      => __('Order By','snkpo'),
        	'total_order'	=> __('Total Order','snkpo'),
			'date'			=> __('Date','snkpo')
		];

        return $columns;
    }

    /**
     * Display the order data
     * Hooked via action manage_snkpo-order_posts_custom_column, priority 999
     * @param  array  $column
     * @param  int    $post_id
     * @return void
     */
    public function display_data_in_table(string $column, int $post_id) {
		switch($column) :
			case 'order_by' :
				$order_data = get_post_meta($post_id,'order_data',true);
				echo $order_data['name'].' ( '.$order_data['email'].' )';
				break;
			case 'total_order' :
				$order_data = get_post_meta($post_id,'order_data',true);
				echo $order_data['order'];
				break;
		endswitch;
    }

	/**
	 * Add metabox to show order detail
	 * Hooked via action add_meta_boxes, priority 999
	 */
	public function set_metabox() {
		add_meta_box('snkpo-order-detail',__('Order Detail','snkpo'),[$this,'display_metabox'],'snkpo-order');
	}

	/**
	 * Display metabox data
	 * @return void
	 */
	public function display_metabox() {
		global $post;
		require 'partials/order-detail.php';
	}
}
