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
		$stock          = intval($_GET['total_order']);
		$stock_ok       = intval(carbon_get_post_meta($product_id, 'stock_ok'));
		$stock_uns      = intval(carbon_get_post_meta($product_id, 'stock_unschedule'));
		$leadtime_f_ok  = carbon_get_post_meta($product_id,'leadtime_fewer_then_ok');
		$leadtime_f_tot = carbon_get_post_meta($product_id,'leadtime_fewer_then_total');
		$leadtime_m_tot = carbon_get_post_meta($product_id,'leadtime_more_then_total');

		if($stock < $stock_ok) :
			$message = '<p>'.sprintf(__('Leadtime %s days','sankosha'),$leadtime_f_ok).'</p>';
		elseif($stock < ($stock_ok + $stock_uns)) :
			$message = '<p>'.sprintf(__('Leadtime %s days','sankosha'),$leadtime_f_tot).'</p>';
		else :
			$message = '<p>'.sprintf(__('Leadtime %s days','sankosha'),$leadtime_m_tot).'</p>';
		endif;

		wp_send_json([
			'stock'	=> [
				'ok'	=> $stock_ok,
				'uns'	=> $stock_uns
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
			if($this->is_able_to_access) :
		        require SNKPO_PATH . '/public/partials/order-form.php';
			else :
				?>
				<div class="sankosha">
					<div class="message error">
						<p>You need to login first to access this page</p>
					</div>
				</div>
				<?php
			endif;
			$content .= ob_get_contents();
			ob_end_clean();
		endif;

		return $content;
	}
}
