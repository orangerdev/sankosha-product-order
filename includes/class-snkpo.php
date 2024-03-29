<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Snkpo
 * @subpackage Snkpo/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Snkpo
 * @subpackage Snkpo/includes
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Snkpo {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Snkpo_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SNKPO_VERSION' ) ) {
			$this->version = SNKPO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'snkpo';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Snkpo_Loader. Orchestrates the hooks of the plugin.
	 * - Snkpo_i18n. Defines internationalization functionality.
	 * - Snkpo_Admin. Defines all hooks for the admin area.
	 * - Snkpo_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-snkpo-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-snkpo-i18n.php';

		// functions
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions/money.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions/product.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/product.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/order.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/email.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/product.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/order.php';

		$this->loader = new Snkpo_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Snkpo_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Snkpo_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$admin = new SNKPO\Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', 					$admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', 					$admin, 'enqueue_scripts' );
		$this->loader->add_action( 'after_setup_theme',							$admin, 'load_carbon_fields'	,999);
		$this->loader->add_action( 'carbon_fields_register_fields',				$admin, 'set_plugin_options'	,999);

		$product	= new SNKPO\Admin\Product( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init',										$product, 'register_post_type'		,999);
		$this->loader->add_action( 'carbon_fields_register_fields',				$product, 'set_post_options'		,999);
		$this->loader->add_action( 'manage_snkpo-product_posts_custom_column',	$product, 'display_data_in_table'	,999, 2);
		$this->loader->add_filter( 'manage_snkpo-product_posts_columns',		$product, 'set_table_columns'		,999);
		$this->loader->add_action( 'admin_footer',								$product, 'display_manage_stock_popup',999);
		$this->loader->add_action( 'parse_request',								$product, 'ajax_add_stock',999);
		$this->loader->add_action( 'parse_request',								$product, 'ajax_reduce_stock',999);		

		$order	= new SNKPO\Admin\Order( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init',										$order, 'register_post_type'		,999);
		$this->loader->add_action( 'add_meta_boxes',							$order, 'set_metabox'				,999);
		$this->loader->add_action( 'manage_snkpo-order_posts_custom_column',	$order, 'display_data_in_table'	,999, 2);
		$this->loader->add_filter( 'manage_snkpo-order_posts_columns',			$order, 'set_table_columns'		,999);

		$email	= new SNKPO\Admin\Email( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'snkpo/send-email',							$email, 'send_email'			,999,2);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$public = new SNKPO\Front( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_scripts' );

		$product = new SNKPO\Front\Product( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init',					$product, 'register_shortcode',		 999);
		$this->loader->add_action( 'template_redirect',		$product, 'check_if_user_logged_in', 999);
		$this->loader->add_action( 'wp_ajax_check-stock',	$product, 'check_stock_product', 	1999);
		$this->loader->add_filter( 'the_content',			$product, 'display_form', 999);
		$this->loader->add_filter( 'single_template',	    $product, 'single_template', 999);
		$this->loader->add_action( 'wp_footer',	    		$product, 'order_via_link', 999);

		$order = new SNKPO\Front\Order( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_ajax_create-order',	$order, 'crete_order', 1999);

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Snkpo_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
