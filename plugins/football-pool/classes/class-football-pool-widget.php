<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/**
 * Based on a prehistoric version of the Empty Widget template (https://gist.github.com/1229641), 
 * nowadays a more powerful widget plugin, but I haven't had time to look into its new features yet :)
 */

/**
 * Protection 
 * 
 * This string of code will prevent hacks from accessing the file directly.
 */
defined( 'ABSPATH' ) or die( 'Cannot access widgets directly.' );

abstract class Football_Pool_Widget extends WP_Widget {
	/**
	 * Widget settings
	 * 
	 * Simply use the following field examples to create the WordPress Widget options that
	 * will display to administrators. These options can then be found in the $params 
	 * variable within the widget method.
	 * 
	 * 
		array(
			'name' => 'Title',
			'desc' => '',
			'id' => 'title',
			'type' => 'text',
			'std' => 'Your widgets title'
		),
		array(
			'name' => 'Textarea',
			'desc' => 'Enter big text here',
			'id' => 'textarea_id',
			'type' => 'textarea',
			'std' => 'Default value 2'
		),
		array(
		    'name'    => 'Select box',
			'desc' => '',
		    'id'      => 'select_id',
		    'type'    => 'select',
		    'options' => array( 'KEY1' => 'Value 1', 'KEY2' => 'Value 2', 'KEY3' => 'Value 3' )
		),
		array(
			'name' => 'Radio',
			'desc' => '',
			'id' => 'radio_id',
			'type' => 'radio',
			'options' => array(
				array('name' => 'Name 1', 'value' => 'Value 1'),
				array('name' => 'Name 2', 'value' => 'Value 2')
			)
		),
		array(
			'name' => 'Checkbox',
			'desc' => '',
			'id' => 'checkbox_id',
			'type' => 'checkbox'	// value is 'on' or ''
		),
	 */
	
	protected $widget = array(
		// If not set, then name of class will be used (underscores replaced with spaces).
		'name' => '',
		
		// this description will display within the administrative widgets area
		// when a user is deciding which widget to use.
		'description' => '',
		
		// determines whether or not to use the sidebar _before and _after html
		'do_wrapper' => true, 
		
		'fields' => array(
			// You should always offer a widget title
			array(
				'name' => 'Title',
				'desc' => '',
				'id' => 'title',
				'type' => 'text',
				'std' => ''
			),
		)
	);
	
	/**
	 * Widget HTML
	 * 
	 * If you want to have an all inclusive single widget file, you can do so by
	 * dumping your css styles with base_encoded images along with all of your 
	 * html string, right into this method.
	 *
	 * @param array $title
	 * @param array $args
	 * @param array $instance
	 */
	protected function html( $title, $args, $instance ) {
		wp_die( 'Widget: override html function in child class!' );
	}

	/**
	 * Constructor
	 *
	 * Registers the widget details with the parent class, based off of the options
	 * that were defined within the widget property. This method does not need to be
	 * changed.
	 *
	 * @param string $id_base       Base ID for the widget, lowercase and unique.
	 * @param string $widgetname    Name for the widget displayed on the configuration page.
	 * @param string $description   Will be passed on as part of the widget_options array to the parent constructor.
	 */
	public function __construct( $id_base, $widgetname, $description ) {
		$description = __( 'Football pool plugin', 'football-pool' )
		               . ' &raquo; ' . __( $description, 'football-pool' );
		parent::__construct( $id_base, $widgetname, array( 'description' => $description ) );
	}
	
	/**
	 * Output widget.
	 * 
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$this->widget['number'] = $this->number;

		if ( isset( $instance['title'] ) ) {
			$title = apply_filters( 'widget_title',
				__( $instance['title'], 'football-pool' ), $instance, $this->id_base );
		} else {
			$title = '';
		}
		
		$do_wrapper = ( ! isset( $this->widget['do_wrapper'] ) || $this->widget['do_wrapper'] );
		
		if ( $do_wrapper ) echo $args['before_widget'];
		$this->widget_html( $title, $args, $instance );
		if ( $do_wrapper ) echo $args['after_widget'];
	}
	
	public function widget_html( $title, $args, $instance ) {
		echo '<div class="football-pool-widget">';
		$this->html( $title, $args, $instance );
		echo '</div>';
	}

	/**
	 * This function is called in the form method for the administrative form in the admin. If you
	 * need to add some dynamic values in the $widget['fields'] (e.g. get them from the database),
	 * you can override this method in the child class.
	 */
	protected function initiate_widget_dynamic_fields() {
		// override this function in the child class if you want to dynamically set extra fields
	}

	/**
	 * Administration Form
	 * 
	 * This method is called from within the wp-admin/widgets area when this
	 * widget is placed into a sidebar. The resulting is a widget options form
	 * that allows the administration to modify how the widget operates.
	 * 
	 * You do not need to adjust this method what-so-ever, it will parse the array
	 * parameters given to it from the protected widget property of this class.
	 *
	 * @param array $instance
	 * @return boolean
	 */
	public function form( $instance ) {
		$this->initiate_widget_dynamic_fields();

		//reasons to fail
		if ( empty( $this->widget['fields'] ) ) return false;
		
		// translate the default title
		if ( $this->widget['fields'][0]['name'] === 'Title' )
			$this->widget['fields'][0]['std'] = __( $this->widget['fields'][0]['std'], 'football-pool' );
		
		$defaults = array(
			'id' => '',
			'name' => '',
			'desc' => '',
			'type' => '',
			'options' => '',
			'std' => '',
		);
		
		// do_action( get_class( $this ) . '_before' );
		foreach ( $this->widget['fields'] as $field ) {
			// making sure we don't throw strict errors
			$field = wp_parse_args( $field, $defaults );

			$meta = false;
			if ( isset( $field['id'] ) && array_key_exists( $field['id'], $instance ) ) {
				@$meta = esc_attr( $instance[ $field['id'] ] );
			}
			
			if ( $field['type'] != 'custom' && $field['type'] != 'metabox' ) {
				echo '<p><label for="', $this->get_field_id(  $field['id'] ), '">';
			}
			if ( isset( $field['name'] ) && $field['name'] ) echo __( $field['name'], 'football-pool' ), ': ';
			if ( $field['type'] != 'checkbox' ) echo '<br>';
			
			switch ( $field['type'] ) {
				case 'text':
					echo '<input type="text" name="', $this->get_field_name( $field['id'] ), '" id="', $this->get_field_id( $field['id'] ), '" value="', ( $meta ? $meta : @$field['std'] ), '" class="vibe_text">',
					'<br><span class="description">', @$field['desc'], '</span>';
					break;
				case 'textarea':
					echo '<textarea class="vibe_textarea" name="', $this->get_field_name( $field['id'] ), '" id="', $this->get_field_id( $field['id'] ), '" cols="60" rows="4" style="width:97%">', $meta ? $meta : @$field['std'], '</textarea>', 
					'<br><span class="description">', @$field['desc'], '</span>';
					break;
				case 'select':
					echo '<select class="vibe_select" name="', $this->get_field_name( $field['id'] ), '" id="', $this->get_field_id( $field['id'] ), '">';

					foreach ($field['options'] as $value => $option)
					{
 					   $selected_option = ( $value ) ? $value : $option;
					    echo '<option', ( $value ? ' value="' . $value . '"' : '' ), ( $meta == $selected_option ? ' selected="selected"' : '' ), '>', $option, '</option>';
					}

					echo '</select>', 
					'<br><span class="description">', @$field['desc'], '</span>';
					break;
				case 'radio':
					foreach ( $field['options'] as $option ) {
						echo '<input class="vibe_radio" type="radio" name="', $this->get_field_name( $field['id'] ), '" value="', $option['value'], '"', ( $meta == $option['value'] ? ' checked="checked"' : '' ), '>',
						$option['name'];
					}
					echo '<br><span class="description">', @$field['desc'], '</span>';
					break;
				case 'checkbox':
					echo '<input type="hidden" name="', $this->get_field_name( $field['id'] ), '" id="', $this->get_field_id( $field['id'] ), '"> ',
						 '<input style="margin-left: 1em" class="vibe_checkbox" type="checkbox" name="', $this->get_field_name( $field['id'] ), '" id="', $this->get_field_id( $field['id'] ), '"', $meta ? ' checked="checked"' : '', '> ',
					'<br><span class="description">', @$field['desc'], '</span>';
					break;
				case 'custom':
					echo $field['std'];
					break;
			}

			if ( $field['type'] != 'custom' && $field['type'] != 'metabox' ) {
				echo '</label></p>';
			}
		}
		// do_action( get_class( $this ) . '_after' );
		return true;
	}

	/**
	 * Update the Administrative parameters
	 * 
	 * This function will merge any posted paramters with that of the saved
	 * parameters. This ensures that the widget options never get lost. This
	 * method does not need to be changed.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$new_instance = array_map( 'strip_tags', $new_instance );

		return wp_parse_args( $new_instance, $old_instance );
	}
}
