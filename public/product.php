<?php

namespace SNKPO\Front;

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
	protected $is_able_to_access = false;

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
	 * Register custom shortcode
	 * Hooked via action init, priority 999
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode('sankosha-products'    ,[$this,'display_product_list']);
	}

    /**
     * Display all products
     * Called by shortcode
     * @return  void
     */
    public function display_product_list() {

        ob_start();

        require SNKPO_PATH . '/public/partials/list-product.php';

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

	/**
	 * Check if current page is product and is user logged in
	 * Hooked via action template_redirect, priority 999
	 * @return	void
	 */
	public function check_if_user_logged_in() {

		if(is_singular('snkpo-product') && is_user_logged_in()) :
			$this->is_able_to_access = true;
		endif;
	}

	/**
	 * Check product stock
	 * Hooked via action template_redirect, priority 1999
	 * @return	void
	 */
	public function check_stock_product() {

		$product_id     = intval($_GET['product_id']);
		$total_order    = intval($_GET['total_order']);
		$stock 			= snkpo_get_stock( $product_id );

		$leadtime_f_ok  = carbon_get_post_meta($product_id,'leadtime_fewer_then_ok');
		$leadtime_f_tot = carbon_get_post_meta($product_id,'leadtime_fewer_then_total');
		$leadtime_m_tot = carbon_get_post_meta($product_id,'leadtime_more_then_total');

		if($total_order <= $stock['ok']) :
			$message = '<p>'.sprintf(__('Leadtime %s days','sankosha'),$leadtime_f_ok).'</p>';
		elseif($total_order <= ($stock['ok'] + $stock['uns'])) :
			$message = '<p>'.sprintf(__('Leadtime %s days','sankosha'),$leadtime_f_tot).'</p>';
		else :
			$message = '<p>'.sprintf(__('Leadtime %s days','sankosha'),$leadtime_m_tot).'</p>';
		endif;

		wp_send_json([
			'stock'	=> [
				'ok'	=> $stock['ok'],
				'uns'	=> $stock['uns']
			],
			'message' => $message
		]);
		exit;
	}

	/**
	 * Display product form
	 * Hooked via filter the_content, priority 999
	 * @param  string $content
	 * @return string
	 */
	public function display_form(string $content) {


		if(is_singular('snkpo-product')) :
			ob_start();
			require SNKPO_PATH . '/public/partials/detail-product.php';
			require SNKPO_PATH . '/public/partials/order-form.php';
			$content .= ob_get_contents();
			ob_end_clean();
		endif;

		return $content;
	}

	public function single_template($single) {

		global $post;
	
		if ( $post->post_type == 'snkpo-product' ) :
			if ( file_exists( SNKPO_PATH . '/public/partials/single-product.php' ) ) :
				$single = SNKPO_PATH . '/public/partials/single-product.php';
			endif;
		endif;
	
		return $single;
	
	}

	public function order_via_link() {

		if ( is_singular('snkpo-product') &&
			 isset( $_GET['act'] ) && $_GET['act'] === 'order' ) :

			include SNKPO_PATH . '/public/partials/product-order-via-link.php';

		endif;
	}
}
