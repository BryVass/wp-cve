<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Background
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class EDSFramework_Option_background extends EDSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo $this->element_before();

    $value_defaults = array(
      'image'       => '',
      'repeat'      => '',
      'position'    => '',
      'attachment'  => '',
      'size'        => '',
      'color'       => '',
    );

    $this->value  = wp_parse_args( $this->element_value(), $value_defaults );

    if( isset( $this->field['settings'] ) ) { extract( $this->field['settings'] ); }

    $upload_type  = ( isset( $upload_type  ) ) ? $upload_type  : 'image';
    $button_title = ( isset( $button_title ) ) ? $button_title : __( 'Upload', 'eds-framework' );
    $frame_title  = ( isset( $frame_title  ) ) ? $frame_title  : __( 'Upload', 'eds-framework' );
    $insert_title = ( isset( $insert_title ) ) ? $insert_title : __( 'Use Image', 'eds-framework' );

    echo '<div class="cs-field-upload">';
    echo '<input type="text" name="'. $this->element_name( '[image]' ) .'" value="'. $this->value['image'] .'"'. $this->element_class() . $this->element_attributes() .'/>';
    echo '<a href="#" class="button cs-add" data-frame-title="'. $frame_title .'" data-upload-type="'. $upload_type .'" data-insert-title="'. $insert_title .'">'. $button_title .'</a>';
    echo '</div>';

    // background attributes
    echo '<fieldset>';
    echo eds_add_element( array(
        'pseudo'          => true,
        'type'            => 'select',
        'name'            => $this->element_name( '[repeat]' ),
        'options'         => array(
          ''              => 'repeat',
          'repeat-x'      => 'repeat-x',
          'repeat-y'      => 'repeat-y',
          'no-repeat'     => 'no-repeat',
          'inherit'       => 'inherit',
        ),
        'attributes'      => array(
          'data-atts'     => 'repeat',
		  'disabled' => 'disabled'
        ),
        'value'           => $this->value['repeat']
    ) );
    echo eds_add_element( array(
        'pseudo'          => true,
        'type'            => 'select',
        'name'            => $this->element_name( '[position]' ),
        'options'         => array(
          ''              => 'left top',
          'left center'   => 'left center',
          'left bottom'   => 'left bottom',
          'right top'     => 'right top',
          'right center'  => 'right center',
          'right bottom'  => 'right bottom',
          'center top'    => 'center top',
          'center center' => 'center center',
          'center bottom' => 'center bottom'
        ),
        'attributes'      => array(
          'data-atts'     => 'position',
		  'disabled' => 'disabled'
        ),
        'value'           => $this->value['position']
    ) );
    echo eds_add_element( array(
        'pseudo'          => true,
        'type'            => 'select',
        'name'            => $this->element_name( '[attachment]' ),
        'options'         => array(
          ''              => 'scroll',
          'fixed'         => 'fixed',
        ),
        'attributes'      => array(
          'data-atts'     => 'attachment',
		  'disabled' => 'disabled'
        ),
        'value'           => $this->value['attachment']
    ) );
    echo eds_add_element( array(
        'pseudo'          => true,
        'type'            => 'select',
        'name'            => $this->element_name( '[size]' ),
        'options'         => array(
          ''              => 'size',
          'cover'         => 'cover',
          'contain'       => 'contain',
          'inherit'       => 'inherit',
          'initial'       => 'initial',
        ),
        'attributes'      => array(
          'data-atts'     => 'size',
		  'disabled' => 'disabled'
        ),
        'value'           => $this->value['size']
    ) );
    echo eds_add_element( array(
        'pseudo'          => true,
        'id'              => $this->field['id'].'_color',
        'type'            => 'color_picker',
        'name'            => $this->element_name('[color]'),
        'attributes'      => array(
          'data-atts'     => 'bgcolor',
		  'disabled' => 'disabled'
        ),
        'value'           => $this->value['color'],
        'default'         => ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '',
        'rgba'            => ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
    ) );
    echo '</fieldset>';

    echo $this->element_after();

  }
}
