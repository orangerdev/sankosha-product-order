<?php
namespace SNKPO;
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Snkpo
 * @subpackage Snkpo/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Snkpo
 * @subpackage Snkpo/public
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Front {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style('thickbox');
		wp_enqueue_style( $this->plugin_name.'-owl.carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', array(), '2.3.4', 'all' );
		wp_enqueue_style( $this->plugin_name.'-owl.theme.default', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css', array(), '2.3.4', 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/snkpo-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $post;

		wp_enqueue_script( $this->plugin_name.'-owl.carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array( 'jquery' ), '2.3.4', false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/snkpo-public.js', array( 'jquery', 'thickbox' ), $this->version, false );
		wp_localize_script($this->plugin_name, 'sankosha_var',[
			'order'	=> [
				'product_id' => $post->ID,
				'key'        => wp_create_nonce('sankosha-create-order'),
				'url'        => add_query_arg([
					'action' => 'create-order'
				],admin_url('admin-ajax.php'))
			],
			'checkStock'	=> [
				'product_id' => $post->ID,
				'key'        => wp_create_nonce('sankosha-check-stock'),
				'url'        => add_query_arg([
					'action' => 'check-stock'
				],admin_url('admin-ajax.php'))
			]
		]);
	}

}
