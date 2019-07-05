<?php

namespace SNKPO\Admin;

class Email {

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
    protected $args  = array();
    protected $setup = array();

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
     * Setup email data
     * @return void
     */
    protected function setup_email() {
        $this->setup = [
            'sender_name'   => carbon_get_theme_option('snkpo_sender_name'),
            'sender_email'  => carbon_get_theme_option('snkpo_sender_email'),
            'reply_email'   => carbon_get_theme_option('snkpo_reply_email'),
            'cc_emails'     => explode(',',carbon_get_theme_option('snkpo_cc_emails')),
            'email_title'   => carbon_get_theme_option('snkpo_email_title'),
            'email_content' => carbon_get_theme_option('snkpo_email_content'),
        ];
    }

    /**
     * Render content shortcode, both content and title
     * @return void
     */
    protected function setup_content() {
        foreach($this->args as $key => $value) :
            $this->setup['email_title']   = str_replace('{{'.$key.'}}',$value,$this->setup['email_title']);
            $this->setup['email_content'] = str_replace('{{'.$key.'}}',$value,$this->setup['email_content']);
        endforeach;
    }

    /**
     * Send email notification
     * @param  integer $post
     * @param  array   $args
     * @return void
     */
    public function send_email(int $post,array $args) {

        $this->args = $args;
        $this->setup_email();
        $this->setup_content();
        $headers = [];
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: '.$this->setup['sender_name'].' <'.$this->setup['sender_email'].'>';

        if(is_array($this->setup['cc_emails']) && 0 < count($this->setup['cc_emails'])) :
            foreach($this->setup['cc_emails'] as $email) :
                if(is_email($email)) :
                    $headers[] = 'Cc: '.$email;
                endif;
            endforeach;
        endif;

        wp_mail(
            $args['email'],
            $this->setup['email_title'],
            apply_filters('the_content',$this->setup['email_content']),
            $headers
        );
    }
}
