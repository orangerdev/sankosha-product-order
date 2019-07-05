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

		if(is_singular('snkpo-product') && !is_user_logged_in()) :
			wp_redirect(home_url('product'));
			exit;
		endif;

		$this->is_able_to_access = true;
	}

	/**
	 * Display product form
	 * Hooked via filter the_content, priority 999
	 * @param  string $content
	 * @return string
	 */
	public function display_form(string $content) {

		if($this->is_able_to_access) :
			ob_start();

	        require SNKPO_PATH . '/public/partials/order-form.php';

	        $content = ob_get_contents();
	        ob_end_clean();
		endif;

		return $content;
	}
}
