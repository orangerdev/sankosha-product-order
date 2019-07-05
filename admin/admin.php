<?php

namespace SNKPO;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Snkpo
 * @subpackage Snkpo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Snkpo
 * @subpackage Snkpo/admin
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/snkpo-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/snkpo-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Load carbon fields
	 * Hooked via action after_setup_theme, priority 999
	 * @return void
	 */
	public function load_carbon_fields() {
		\Carbon_Fields\Carbon_Fields::boot();
	}

	/**
	 * Display plugin options
	 * Hooked via action carbon_fields_register_fields, priority 999
	 * @return void
	 */
	public function set_plugin_options() {

		ob_start();
		require 'partials/shortcodes.php';
		$content = ob_get_contents();
		ob_end_clean();

		Container::make('theme_options',__('Sankosha Options','snkpo'))
			->add_tab(__('Email','snkpo'),[
				Field::make('html',		'snkpo_html_info'		,__('Shortcode Info','snkpo'))
					->set_html($content),
				Field::make('text',		'snkpo_sender_name'		,__('Sender Name','snkpo')),
				Field::make('text',		'snkpo_sender_email'	,__('Sender Email','snkpo')),
				Field::make('text',		'snkpo_reply_email'		,__('Reply-to Email','snkpo')),
				Field::make('text',		'snkpo_cc_emails'		,__('CC Email','snkpo'))
					->set_help_text(__('use comma to separate multi email addresses','snkpo')),
				Field::make('text',		'snkpo_email_title'		,__('Email Title','snkpo')),
				Field::make('rich_text','snkpo_email_content'	,__('Email Content','snkpo'))
			]);
	}
}
