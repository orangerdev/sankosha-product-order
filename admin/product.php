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
    		'rewrite'            => array( 'slug' => 'product' ),
    		'capability_type'    => 'post',
    		'has_archive'        => true,
    		'hierarchical'       => false,
    		'menu_position'      => null,
    		'supports'           => array( 'title', 'thumbnail' )
    	];

    	register_post_type( 'snkpo-product', $args );
    }

    /**
     * Setup carbon field option for product
     * Hooked via action carbon_fields_register_fields, priority 999
     * @return  void
     */
    public function set_post_options() {

    }
}
