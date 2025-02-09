<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    sb_bar
 * @subpackage sb_bar/public
 * @author     Goran Jakovljevic <goranefbl@gmail.com>
 */
class sb_bar_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sb_bar    The ID of this plugin.
	 */
	private $sb_bar;

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
	 * @var      string    $sb_bar       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $sb_bar, $version ) {

		$this->sb_bar = $sb_bar;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// Call styles only if swifty is enabled and for chosen post types.
		$options = (get_option('sb_bar_options') ? get_option('sb_bar_options') : false);
		// Post Type
		$posttype = array();
		if(isset($options["post-type"]) && $options["post-type"] != '') {
			$posttype = $options["post-type"];
		}

		if($options == false || (!isset($options["disable-bar"]) && is_singular() && in_array(get_post_type(), $posttype))) {
			wp_enqueue_style( $this->sb_bar, plugin_dir_url( __FILE__ ) . 'assets/css/sb-bar-public.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Call scripts only if swifty is enabled and for chosen post types.
		$options = (get_option('sb_bar_options') ? get_option('sb_bar_options') : false);
		//Post Type
		$posttype = array();
		if(isset($options["post-type"]) && $options["post-type"] != '') {
			$posttype = $options["post-type"];
		}

		if($options == false || (!isset($options["disable-bar"]) && is_singular() && in_array(get_post_type(), $posttype))) {
			global $post;
			wp_enqueue_script( $this->sb_bar, plugin_dir_url( __FILE__ ) . 'assets/js/sb-bar-public.js', array( 'jquery' ), $this->version, false );
			wp_localize_script($this->sb_bar, 'admin_urls', 
				array( 
				'admin_ajax' => admin_url( 'admin-ajax.php'),
				'post_id' => $post->ID,
				'postNonce' => wp_create_nonce( 'myajax-post-nonce' )
				)
			);
		}
	}

	/**
	 * This will add divs before and after content to better calculate ttr line
	 *
	 * @since    1.0.0
	 */
	public function ttr_container( $content ) {
		$custom_content = '<div class="ttr_start"></div>';
		$custom_content .= $content;
		$custom_content .= '<div class="ttr_end"></div>';
		return $custom_content;
	}


	/**
	 * Including front end
	 *
	 * @since    1.0.0
	 */
	public function front_end() {
		// Check if plugin is disabled in options to remove css also.
		$options = (get_option('sb_bar_options') ? get_option('sb_bar_options') : false);

		if(!isset($options["disable-bar"])) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/sb-bar-public-display.php';
		}

	}

	/**
	 * Remove transient on share click to update shares
	 *
	 * @since    1.2.0
	 */
	public function delete_post_transient() {

		// Check nounce - security thingy
		$nonce = $_POST['postNonce'];
		if ( ! wp_verify_nonce( $nonce, 'myajax-post-nonce' ) ) {
			die ( 'Busted!');
		}
		$post_id = sanitize_text_field($_POST['post_id']);
		$transient_name = "sb_bar_".$post_id."_shares";

		delete_transient($transient_name);

	}

}
