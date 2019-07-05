<?php

namespace SNKPO\Front;

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
     * Create order
     * Hooked via action template_redirect, priority 999
     * @return void
     */
    public function crete_order() {
        $args = wp_parse_args($_POST,[
            'post_data'  => [],
            'key'        => NULL,
            'product_id' => 0
        ]);

        $valid = true;

        if(wp_verify_nonce($args['key'],'sankosha-create-order')) :

            $data    = [];
            $message = '';

            foreach($args['post_data'] as $_data) :
                $data[$_data['name']] = $_data['value'];
            endforeach;

            if(empty($data['name'])) :
                $valid = false;
                $message .= '<p>Your name is empty</p>';
            endif;

            if(!is_email($data['email'])) :
                $valid = false;
                $message .= '<p>Email address is not valid</p>';
            endif;

            if(empty($args['product_id'])) :
                $valid = false;
                $message .= '<p>Product ID is not valid</p>';
            endif;

            if(false !== $valid) :
                $product = get_post($args['product_id']);
                $post_id = wp_insert_post([
                    'post_author'   => get_current_user_id(),
                    'post_type'     => 'snkpo-order',
                    'post_status'   => 'publish',
                    'post_parent'   => $args['product_id'],
                ]);

                wp_update_post([
                    'ID'    => $post_id,
                    'post_title' => sprintf(__('Order #%s for %s','snkpo'),$post_id,$product->post_title)
                ]);

                update_post_meta($post_id,'order_data',$data);

				$data['order_id']	= $post_id;
				$data['product_id']	= $product->ID;
				$data['product']	= $product->post_title;

				do_action('snkpo/send-email',$post_id,$data);

                $message .= sprintf(__('<p>Order #%s created</p>','snkpo'),$post_id);
            endif;
        else :
            $valid = false;
            $message .= '<p>Wrong security</p>';
        endif;

        wp_send_json([
            'valid'   => $valid,
            'message' => $message
        ]);
        exit;
    }
}
