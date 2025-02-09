<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link	  http://example.com
 * @since      1.7.0
 *
 * @package    Greenhouse_Job_Board
 * @subpackage Greenhouse_Job_Board/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Greenhouse_Job_Board
 * @subpackage Greenhouse_Job_Board/public
 * @author     Your Name <email@example.com>
 */
class Greenhouse_Job_Board_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $greenhouse_job_board    The ID of this plugin.
	 */
	private $greenhouse_job_board;

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
	 * @param      string    $greenhouse_job_board       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $greenhouse_job_board, $version ) {

		$this->greenhouse_job_board = $greenhouse_job_board;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.0.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Greenhouse_Job_Board_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Greenhouse_Job_Board_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( $this->greenhouse_job_board, plugin_dir_url( __FILE__ ) . 'css/greenhouse-job-board-public.css', array(), $this->version, 'all' );
		
		$options = get_option( 'greenhouse_job_board_settings' );
		if ( isset( $options['greenhouse_job_board_custom_css'] ) && isset($options['greenhouse_job_board_custom_css_checkbox']) &&
			 $options['greenhouse_job_board_custom_css'] !== '') {
			$custom_css = $options['greenhouse_job_board_custom_css'];		
			wp_add_inline_style( $this->greenhouse_job_board, $custom_css );
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.7.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Greenhouse_Job_Board_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Greenhouse_Job_Board_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$options = get_option( 'greenhouse_job_board_settings' );
		$v = $this->version;
		if ( isset( $options['greenhouse_job_board_debug'] ) &&
			 $options['greenhouse_job_board_debug'] === 'true' ) {
			$v .= '_' . time();	
		}
		
		if ( !wp_script_is( 'handlebars', 'registered' ) ) {
			wp_register_script( 'handlebars', plugin_dir_url( __FILE__ ) . 'js/handlebars-v3.0.0.js', array( 'jquery' ), null, false );
		}
		if ( !wp_script_is( 'jquery.cycle2', 'registered' ) ) {
			wp_register_script( 'jquery.cycle2', plugin_dir_url( __FILE__ ) . 'js/jquery.cycle2.min.js', array( 'jquery' ), '20141007', false );
		}
		wp_register_script( 'ghjbp', plugin_dir_url( __FILE__ ) . 'js/greenhouse-job-board-public.js', array( 'jquery', 'handlebars' ), $v, false );
		
	}
	
	/**
	 * Register the shortcodes.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {
		add_shortcode( 'greenhouse', array( $this, 'greenhouse_shortcode_function') );
	}
	
	/**
	 * Handle the main [greenhouse] shortcode.
	 *
	 * @since    2.7.0
	 */
	public function greenhouse_shortcode_function( $atts, $content = null ) {
		wp_enqueue_style($this->greenhouse_job_board);
		wp_enqueue_script('ghjbp');
		
		$options = get_option( 'greenhouse_job_board_settings' );
		
	    $atts = shortcode_atts( array(
	    	'id'				=> '',
	        'url_token' 		=> isset( $options['greenhouse_job_board_url_token'] ) ? $options['greenhouse_job_board_url_token'] : '',
	        'api_key' 			=> isset( $options['greenhouse_job_board_api_key'] ) ? $options['greenhouse_job_board_api_key'] : '',
	        'board_type' 		=> isset( $options['greenhouse_job_board_type'] ) ? $options['greenhouse_job_board_type'] : 'accordion',
	        'cycle_fx' 			=> isset( $options['greenhouse_job_cycle_fx'] ) ? $options['greenhouse_job_cycle_fx'] : 'fade',
	        'back'				=> isset( $options['greenhouse_job_board_back'] ) ? $options['greenhouse_job_board_back'] : 'Back',
	        'apply_now'			=> isset( $options['greenhouse_job_board_apply_now'] ) ? $options['greenhouse_job_board_apply_now'] : 'Apply Now',
	        'apply_now_cancel'	=> isset( $options['greenhouse_job_board_apply_now_cancel'] ) ? $options['greenhouse_job_board_apply_now_cancel'] : 'Cancel',
	        'read_full_desc'	=> isset( $options['greenhouse_job_board_read_full_desc'] ) ? $options['greenhouse_job_board_read_full_desc'] : 'Read Full Description',
	        'hide_full_desc'	=> isset( $options['greenhouse_job_board_hide_full_desc'] ) ? $options['greenhouse_job_board_hide_full_desc'] : 'Hide Full Description',
	        'hide_forms'		=> 'false',
	        'form_type'			=> isset( $options['greenhouse_job_board_form_type'] ) ? $options['greenhouse_job_board_form_type'] : 'iframe',
	        'form_fields'		=> isset( $options['greenhouse_job_board_form_fields'] ) ? $options['greenhouse_job_board_form_fields'] : '',
	        'department_filter'	=> '',
	        'job_filter'		=> '',
	        'office_filter'		=> '',
	        'location_filter'	=> '',
	        'location_label'	=> isset( $options['greenhouse_job_board_location_label'] ) ? $options['greenhouse_job_board_location_label'] : 'Location: ',
	        'office_label'		=> isset( $options['greenhouse_job_board_office_label'] ) ? $options['greenhouse_job_board_office_label'] : 'Office: ',
	        'department_label'	=> isset( $options['greenhouse_job_board_department_label'] ) ? $options['greenhouse_job_board_department_label'] : 'Department: ',
	        'description_label'	=> isset( $options['greenhouse_job_board_description_label'] ) ? $options['greenhouse_job_board_description_label'] : '',
	        'sticky'			=> isset( $options['greenhouse_job_board_sticky'] ) ? $options['greenhouse_job_board_sticky'] : '',
			'orderby'			=> isset( $options['greenhouse_job_board_orderby'] ) ? $options['greenhouse_job_board_orderby'] : '',
	        'order'				=> isset( $options['greenhouse_job_board_order'] ) ? $options['greenhouse_job_board_order'] : 'DESC',
	        'group'				=> isset( $options['greenhouse_job_board_group'] ) ? $options['greenhouse_job_board_group'] : '',
	        'group_headline'	=> isset( $options['greenhouse_job_board_group_headline'] ) ? $options['greenhouse_job_board_group_headline'] : '',
	        'display'			=> isset( $options['display'] ) ? $options['display'] : 'description',
			'cache_expiry'		=> isset( $options['greenhouse_job_board_cache_expiry'] ) ?  $options['greenhouse_job_board_cache_expiry'] : 0,
			'use_excerpt'		=> isset( $options['greenhouse_job_board_use_excerpt']) ? 0 : 1
		), $atts );
		
		STATIC $shortcode_id = 0;
		$shortcode_id++;
		
		
		if ( $atts['id'] === '' ) {
			$jbid = $shortcode_id;
		}
		else {
			$jbid = $atts['id'];
		}
	    
	    //sanitize values
	    //if hide_forms is anything other than true, set it to false
	    if ( $atts['hide_forms'] !== 'true' ) {
	    	$atts['hide_forms'] = 'false';
	    }
	    //set form_type 
	    if ( $atts['form_type'] === 'default' ) {
	    	$atts['form_type'] = '';
	    }
	    
	    
		$ghjb_html  = '<div class="greenhouse-job-board" 
			id="greenhouse-job-board_' . $jbid . '" 
			data-type="' . $atts['board_type'] . '" 
			data-form_type="' . $atts['form_type'] . '">';
		
	    	
        if ( $atts['url_token'] == '' ) {
        	$ghjb_html .= 'The greenhouse url_token is required. Please add it to your <a href="' . admin_url('options-general.php?page=greenhouse_job_board' ) . '">greenhouse settings</a>.';
    		$ghjb_html .= '</div>';
    		return $ghjb_html;
        }
	    if ( $atts['form_type'] == 'inline' && $atts['api_key'] == '' ) {
	    	$ghjb_html .= 'The greenhouse api key is required with inline forms. Please add it to your <a href="' . admin_url('options-general.php?page=greenhouse_job_board' ) . '">greenhouse settings</a>.';
			$ghjb_html .= '</div>';
			return $ghjb_html;
	    }
	    
	    /*
		$ghjb_html .= '<p>Greenhouse shortcode detected';
		if ($atts['board_type']) {
			$ghjb_html .= ', with board_type: ' . $atts['board_type'];
		}
		echo $shortcode_id;
		$ghjb_html .= '</p>';
		*/
		
		
		//only print templates and scripts if first shortcode on page
		if ( $shortcode_id === 1 ) {
			
			//accordion template
			if ( $atts['board_type'] == 'accordion' ) {
			// handlebars template for returned job
			$ghjb_html .= '<script id="job-template" type="text/x-handlebars-template">
			<div class="job job_{{id}} job_{{slug}}" 
				data-id="{{id}}" 
				id="{{slug}}" 
				data-departments="{{departments}}">
		 	    	<h2 class="job_title">{{title}}</h2>
		 	    	<p><a href="#" class="job_read_full" data-opened-text="' . $atts['hide_full_desc'] . '" data-closed-text="' . $atts['read_full_desc'] . '">' . $atts['read_full_desc'] . '</a></p>
		 	    	<div class="job_description job_description_{{id}}">
	    				{{#if display_location }}<div class="display_location"><span class="location_label">' . $atts['location_label'] . '</span>{{display_location}}</div>{{/if}}
	    	 	    	{{#if display_office }}<div class="display_office"><span class="office_label">' . $atts['office_label'] . '</span>{{display_office}}</div>{{/if}}
	    	 	    	{{#if display_department }}<div class="display_department"><span class="department_label">' . $atts['department_label'] . '</span>{{display_department}}</div>{{/if}}
		 	    			{{#if display_description }}<div class="display_description"><span class="description_label">' . $atts['description_label'] . '</span>{{{content}}}</div>{{/if}}
		 	    	</div>
		 	    	{{#ifeq hide_forms "false"}}<p><a href="#" class="job_apply job_apply_{{id}}" data-opened-text="' . $atts['apply_now_cancel'] . '" data-closed-text="' . $atts['apply_now'] . '">' . $atts['apply_now'] . '</a></p>{{/ifeq}}
		 	</div>
	</script>';
			}
			
			
			// cycle template
			else if ( $atts['board_type'] == 'cycle') {
				
				wp_enqueue_script('jquery.cycle2');
				
			// handlebars template for returned job
			$ghjb_html .= '<script id="job-template" type="text/x-handlebars-template">
			<div class="job job_{{id}} job_{{slug}}" 
				data-id="{{id}}" 
				data-slug="{{slug}}" 
				data-departments="{{departments}}">
		 	    	<h3 class="job_title">{{title}}</h3>
					 <div class="job_excerpt">';
					 if ($atts['board_type'] == 'cycle' && $atts['use_excerpt'] == 1) {
						 $ghjb_html .= '{{{excerpt}}}';
					 }
					 $ghjb_html .= '<br />
		 	    	<a href="#" class="job_goto">' . $atts['read_full_desc'] . '</a></div>
		 	</div>
	</script>';
			$ghjb_html .= '<script id="job-slide-template" type="text/x-handlebars-template">
			<div class="job cycle-slide job_{{id}} job_{{slug}}" 
				data-cycle-hash="{{slug}}"
				data-id="{{id}}" 
				data-slug="{{slug}}" 
				id="{{slug}}" 
				data-departments="{{departments}}">
					<div class="job_single">
			 	    	<p><a href="#" class="return">' . $atts['back'] . '</a></p>
						<h1 class="job_title">{{title}}</h1>';
			//http://code.tutsplus.com/tutorials/writing-extensible-plugins-with-actions-and-filters--wp-26759
						$ghjb_html = apply_filters( 'ghjb_single_job_template_after_title', $ghjb_html );
			$ghjb_html .= '
						{{#ifeq hide_forms "false"}}<p><a href="#" class="job_apply job_apply_{{id}} button" data-opened-text="' . $atts['apply_now_cancel'] . '" data-closed-text="' . $atts['apply_now'] . '">' . $atts['apply_now'] . '</a></p>{{/ifeq}}
			 			<div class="job_description job_description_{{id}}">
							{{#if display_location }}<div class="display_location"><span class="location_label">' . $atts['location_label'] . '</span>{{display_location}}</div>{{/if}}
				 			{{#if display_office }}<div class="display_office"><span class="office_label">' . $atts['office_label'] . '</span>{{display_office}}</div>{{/if}}
				 			{{#if display_department }}<div class="display_department"><span class="department_label">' . $atts['department_label'] . '</span>{{display_department}}</div>{{/if}}
			 					{{#if display_description }}<div class="display_description"><span class="description_label">' . $atts['description_label'] . '</span>{{{content}}}</div>{{/if}}
			 			</div>
			 			{{#ifeq hide_forms "false"}}<p><a href="#" class="job_apply job_apply_{{id}} button" data-opened-text="' . $atts['apply_now_cancel'] . '" data-closed-text="' . $atts['apply_now'] . '">' . $atts['apply_now'] . '</a></p>{{/ifeq}}
			 			<p><a href="#" class="return">' . $atts['back'] . '</a></p>
		 			</div>
		 	</div>
	</script>';
			}
		}
		
		// html container
		$ghjb_html .= '<div class="all_jobs" ';
		if ( $atts['board_type'] !== 'cycle') { //accordian
			$ghjb_html .= '><div class="jobs" ';
		}
		elseif ( $atts['board_type'] === 'cycle') { //cycle
			$ghjb_html .= 'data-cycle-fx="'. $atts['cycle_fx'] .'" style="overflow:hidden;"><div class="jobs cycle-slide" data-cycle-hash="#" ';
		}
		
		// fill in attributes as applicable
		if ( $atts['department_filter'] !== '') {
			$ghjb_html .= ' data-department_filter="' . $atts['department_filter'] . '" ';
		}
		if ( $atts['job_filter'] !== '') {
			$ghjb_html .= ' data-job_filter="' . $atts['job_filter'] . '" ';
		}
		if ( $atts['office_filter'] !== '') {
			$ghjb_html .= ' data-office_filter="' . $atts['office_filter'] . '" ';
		}
		if ( $atts['location_filter'] !== '') {
			$ghjb_html .= ' data-location_filter="' . $atts['location_filter'] . '" ';
		}
		if ( $atts['hide_forms'] !== '') {
			$ghjb_html .= ' data-hide_forms="' . $atts['hide_forms'] . '" ';
		}
		if ( $atts['form_type'] !== '' ) {
			$ghjb_html .= ' data-form_type="' . $atts['form_type'] . '" ';
		}
		if ( $atts['form_fields'] != '' && 
			 $atts['form_type'] === 'inline' ) {
			$ghjb_html .= ' data-form_fields="' . $atts['form_fields'] . '" ';
		}
		if ( $atts['display'] !== '') {
			$ghjb_html .= ' data-display="' . $atts['display'] . '" ';
		}
		if ( $atts['sticky'] !== '') {
			$ghjb_html .= ' data-sticky="' . $atts['sticky'] . '" ';
		}
		if ( $atts['order'] === 'ASC') {
			//orderby: DESC (default) or ASC
			$ghjb_html .= ' data-order="' . $atts['order'] . '" ';
		}
		if ( $atts['orderby'] !== '') {
			//order can be: none, title, date, id, random, department, location or office
			$ghjb_html .= ' data-orderby="' . $atts['orderby'] . '" ';
		}
		if ( $atts['group'] !== '') {
			//group can be: department, location or office
			$ghjb_html .= ' data-group="' . $atts['group'] . '" ';
			
			if ( $atts['group_headline'] === 'false' ) {
				//group_headline: true (default) or false
				$ghjb_html .= ' data-group_headline="false" ';
			}
		}
		$ghjb_html .= '>
			</div>';
		
		// cycle 
		if ( $atts['hide_forms'] !== 'true' &&
			 $atts['board_type'] === 'cycle'
			) {
			$ghjb_html .= '<div class="cycle-slide"><div class="apply_jobs">
					<h1>' . $options['greenhouse_job_board_apply_headline'] . '</h1>';
					//cycle iframe
					if ( $atts['form_type'] === 'iframe' ) {
						
						$ghjb_html .= '<div id="grnhse_app"></div>';// script for loading iframe
						$ghjb_html .= '<script src="https://app.greenhouse.io/embed/job_board/js?for=' . $atts['url_token'] . '"></script>';
					}
					//cycle inline
					elseif ($atts['form_type'] === 'inline') {
						$ghjb_html .= '<form id="apply_form" method="POST" action="' . plugins_url( '/greenhouse-job-board/public/partials/greenhouse-job-board-apply-submit.php' ) . '" enctype="multipart/form-data">
						</form>';
					}
					$ghjb_html .= '<p><a href="#" class="return">' . $atts['back'] . '</a></p>
					</div></div>
					<div class="cycle-slide">
						<div class="apply_ty">
							<h2>' . $options['greenhouse_job_board_thanks_headline'] . '</h2>
							<div>' . wpautop( $options['greenhouse_job_board_thanks_body'] ) . '</div>
						</div>
						<div class="apply_error">
							<h2>' . $options['greenhouse_job_board_error_headline'] . '</h2>
							<div>' . wpautop( $options['greenhouse_job_board_error_body'] ) . '</div>
						</div>
					</div>';
		} 
		// accordion inline
		elseif ( 	$atts['hide_forms'] !== 'true' &&
			 		$atts['form_type'] === 'inline' &&
			 		$atts['board_type'] === 'accordion'
			) {
			$ghjb_html .= '<div class="apply_jobs">
					
					<form id="apply_form" method="POST" action="' . plugins_url( '/greenhouse-job-board/public/partials/greenhouse-job-board-apply-submit.php' ) . '" enctype="multipart/form-data">
					</form>
					<div class="apply_ty" style="display:none;">
						<h2>' . $options['greenhouse_job_board_thanks_headline'] . '</h2>
						<p>' . $options['greenhouse_job_board_thanks_body'] . '</p>
					</div>
				</div>';
		} 
		// accordion iframe form
		elseif ( $atts['hide_forms'] !== 'true' &&
				 $atts['form_type'] === 'iframe' &&
			 		$atts['board_type'] === 'accordion'
			) {
			$ghjb_html .= '<div id="grnhse_app"></div>';// script for loading iframe
			$ghjb_html .= '<script src="https://app.greenhouse.io/embed/job_board/js?for=' . $atts['url_token'] . '"></script>';
		}
			
		$ghjb_html .= '</div>';
		
		// Get any existing copy of our transient data
		// transients can be saved for multiple shortcode uses since it stores the full api return and nothing is filtered until it hits our js.
		// http://www.tailored4wp.com/get-a-better-performance-with-wordpress-transients-api-501/
		// delete_transient( 'ghjb_json' ); //json is the main object that stores the whole job board
		// delete_transient( 'ghjb_jobs' ); //jobs is the object that stores the details (form fields) for each job, which must be loaded individually
		if ( false === ( $ghjb_json = get_transient( 'ghjb_json' ) ) ) {
			// It wasn't there, so regenerate the data and save the transient
			// api call to get jobs with callback
			$ghjb_json = wp_remote_retrieve_body( wp_remote_get('https://api.greenhouse.io/v1/boards/' . $atts['url_token'] . '/embed/jobs?content=true'));
			//save json data to transient
			set_transient( 'ghjb_json', $ghjb_json, $atts['cache_expiry'] );
		}
		if ( false === ( $ghjb_jobs = get_transient( 'ghjb_jobs' ) ) ) {
			// It wasn't there, so regenerate the data and save the transient
			//read job ids from json data
			$ghjb_json_php = json_decode($ghjb_json);
			//retreive json object for each job and save into transient
			$ghjb_jobs = '';//$ghjb_json_php->jobs[0]->id;
			foreach ( $ghjb_json_php->jobs as $job) {
				$job_json = wp_remote_retrieve_body( wp_remote_get('https://api.greenhouse.io/v1/boards/' . $atts['url_token'] . '/embed/job?id=' . $job->id . '&questions=true'));
				
				$ghjb_jobs .= $job_json . ',';
			}
			$ghjb_jobs = '[' . $ghjb_jobs . ']';
			set_transient( 'ghjb_jobs', $ghjb_jobs, $atts['cache_expiry'] );

		}
		if ( $shortcode_id === 1 ) {
			$ghjb_html .= '<script type="text/javascript">';
			$ghjb_html .= 'ghjb_d=';	
			if ( isset($options['greenhouse_job_board_debug']) &&
				 $options['greenhouse_job_board_debug'] === 'true' ) {
				$ghjb_html .= 'true';
			} else {
				$ghjb_html .= '0';
			}
			$ghjb_html .= ';ghjb_a=';
			if ( isset($options['greenhouse_job_board_analytics']) &&
				 $options['greenhouse_job_board_analytics'] === 'true' ) {
				$ghjb_html .= 'true';
			} else {
				$ghjb_html .= '0';
			}
			$ghjb_html .= ';ghjb_jobs = ';
			$ghjb_html .=  $ghjb_jobs;
			$ghjb_html .= ';ghjb_json = ';
			$ghjb_html .=  $ghjb_json;
			$ghjb_html .= ';';
			// $ghjb_html .= 'greenhouse_jobs(ghjb_json, "#greenhouse-job-board_' . $jbid . '");';
			$ghjb_html .= '</script>';
		}
		
		
		
		// close all_jobs
		$ghjb_html .= '</div>';
		
		return $ghjb_html;

	}
	
	
	/**
	 * Register the settings page.
	 *
	 * @since	2.4.0
	 */
	//http://wpsettingsapi.jeroensormani.com/settings-generator
	function greenhouse_job_board_add_admin_menu(  ) { 
		add_options_page( 'Greenhouse Job Board Settings', 'Greenhouse', 'manage_options', 'greenhouse_job_board', 'greenhouse_job_board_options_page' );
	}
	function greenhouse_job_board_settings_init(  ) { 
		register_setting( 'greenhouse_settings', 'greenhouse_job_board_settings' );

		add_settings_section(
			'greenhouse_job_board_greenhouse_settings_section', 
			__( 'Greenhouse Account', 'greenhouse_job_board' ), 
			'greenhouse_job_board_gh_settings_section_callback', 
			'greenhouse_settings'
		);
		
		add_settings_section(
			'greenhouse_job_board_jobboard_settings_section', 
			__( 'Job Board Settings', 'greenhouse_job_board' ), 
			'greenhouse_job_board_jb_settings_section_callback', 
			'greenhouse_settings'
		);
		
		add_settings_section(
			'greenhouse_job_board_plugin_settings_section', 
			__( 'Plugin Settings', 'greenhouse_job_board' ), 
			'greenhouse_job_board_p_settings_section_callback', 
			'greenhouse_settings'
		);
		
		//url_token
		add_settings_field( 
			'greenhouse_job_board_url_token', 
			__( 'URL Token', 'greenhouse_job_board' ), 
			'greenhouse_job_board_url_token_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_greenhouse_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_api_key', 
			__( 'API key', 'greenhouse_job_board' ), 
			'greenhouse_job_board_api_key_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_greenhouse_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_cache_expiry', 
			__( 'Cache Expiration', 'greenhouse_job_board' ), 
			'greenhouse_job_board_cache_expiry_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_greenhouse_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_clear_cache', 
			__( 'Clear Cache Now', 'greenhouse_job_board' ), 
			'greenhouse_job_board_clear_cache_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_greenhouse_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_type', 
			__( 'Type', 'greenhouse_job_board' ), 
			'greenhouse_job_board_type_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);
		
		add_settings_field( 
			'greenhouse_job_board_cycle_fx', 
			__( 'Cycle Transition', 'greenhouse_job_board' ), 
			'greenhouse_job_board_cycle_fx_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_back', 
			__( 'Back Text', 'greenhouse_job_board' ), 
			'greenhouse_job_board_back_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_apply_now', 
			__( 'Apply Now Text', 'greenhouse_job_board' ), 
			'greenhouse_job_board_apply_now_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_apply_now_cancel', 
			__( 'Apply Now Cancel Text', 'greenhouse_job_board' ), 
			'greenhouse_job_board_apply_now_cancel_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_read_full_desc', 
			__( 'Read Full Description Text', 'greenhouse_job_board' ), 
			'greenhouse_job_board_read_full_desc_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_hide_full_desc', 
			__( 'Hide Full Description Text', 'greenhouse_job_board' ), 
			'greenhouse_job_board_hide_full_desc_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);
		
		add_settings_field( 
			'greenhouse_job_board_form_type', 
			__( 'Form Type', 'greenhouse_job_board' ), 
			'greenhouse_job_board_form_type_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);
		
		add_settings_field( 
			'greenhouse_job_board_form_fields', 
			__( 'Form Fields', 'greenhouse_job_board' ), 
			'greenhouse_job_board_form_fields_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_location_label', 
			__( 'Location Label', 'greenhouse_job_board' ), 
			'greenhouse_job_board_location_label_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_office_label', 
			__( 'Office Label', 'greenhouse_job_board' ), 
			'greenhouse_job_board_office_label_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_department_label', 
			__( 'Department Label', 'greenhouse_job_board' ), 
			'greenhouse_job_board_department_label_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_description_label', 
			__( 'Description Label', 'greenhouse_job_board' ), 
			'greenhouse_job_board_description_label_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_apply_headline', 
			__( 'Apply Headline', 'greenhouse_job_board' ), 
			'greenhouse_job_board_apply_headline_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_thanks_headline', 
			__( 'Thank You Headline', 'greenhouse_job_board' ), 
			'greenhouse_job_board_thanks_headline_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_thanks_body', 
			__( 'Thank You Body', 'greenhouse_job_board' ), 
			'greenhouse_job_board_thanks_body_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_error_headline', 
			__( 'Error Headline', 'greenhouse_job_board' ), 
			'greenhouse_job_board_error_headline_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_error_body', 
			__( 'Error Body', 'greenhouse_job_board' ), 
			'greenhouse_job_board_error_body_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_custom_css', 
			__( 'Custom CSS', 'greenhouse_job_board' ), 
			'greenhouse_job_board_custom_css_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_jobboard_settings_section' 
		);

		add_settings_field( 
			'greenhouse_job_board_debug', 
			__( 'Debug', 'greenhouse_job_board' ), 
			'greenhouse_job_board_debug_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_plugin_settings_section' 
		);
		
		add_settings_field( 
			'greenhouse_job_board_analytics', 
			__( 'Add Analytics', 'greenhouse_job_board' ), 
			'greenhouse_job_board_analytics_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_plugin_settings_section' 
		);
		
		/*
		add_settings_field( 
			'greenhouse_job_board_allow_track', 
			__( 'Allow Tracking', 'greenhouse_job_board' ), 
			'greenhouse_job_board_allow_track', 
			'greenhouse_settings', 
			'greenhouse_job_board_plugin_settings_section' 
		);
		*/

		add_settings_field( 
			'greenhouse_job_board_log_errors', 
			__( 'Log Errors', 'greenhouse_job_board' ), 
			'greenhouse_job_board_log_errors_render', 
			'greenhouse_settings', 
			'greenhouse_job_board_plugin_settings_section' 
		);
		
	}


} ?>