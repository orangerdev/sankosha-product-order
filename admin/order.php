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
}
