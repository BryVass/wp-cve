<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Field: upload
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Field_upload' ) ) {
	class KIANFR_Field_upload extends KIANFR_Fields
	{

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$args = wp_parse_args( $this->field, [
				'library'        => [],
				'preview'        => false,
				'preview_width'  => '',
				'preview_height' => '',
				'button_title'   => esc_html__( 'Upload', 'kianfr' ),
				'remove_title'   => esc_html__( 'Remove', 'kianfr' ),
			] );

			echo $this->field_before();

			$library = ( is_array( $args['library'] ) ) ? $args['library'] : array_filter( (array) $args['library'] );
			$library = ( ! empty( $library ) ) ? implode( ',', $library ) : '';
			$hidden  = ( empty( $this->value ) ) ? ' hidden' : '';

			if ( ! empty( $args['preview'] ) ) {
				$preview_type   = ( ! empty( $this->value ) ) ? strtolower( substr( strrchr( $this->value, '.' ), 1 ) ) : '';
				$preview_src    = ( ! empty( $preview_type ) && in_array( $preview_type, [
						'jpg',
						'jpeg',
						'gif',
						'png',
						'svg',
						'webp',
					] ) ) ? $this->value : '';
				$preview_width  = ( ! empty( $args['preview_width'] ) ) ? 'max-width:' . esc_attr( $args['preview_width'] ) . 'px;' : '';
				$preview_height = ( ! empty( $args['preview_height'] ) ) ? 'max-height:' . esc_attr( $args['preview_height'] ) . 'px;' : '';
				$preview_style  = ( ! empty( $preview_width ) || ! empty( $preview_height ) ) ? ' style="' . esc_attr( $preview_width . $preview_height ) . '"' : '';
				$preview_hidden = ( empty( $preview_src ) ) ? ' hidden' : '';

				echo '<div class="kianfr--preview' . esc_attr( $preview_hidden ) . '">';
				echo '<div class="kianfr-image-preview"' . $preview_style . '>';
				echo '<i class="kianfr--remove fas fa-times"></i><span><img src="' . esc_url( $preview_src ) . '" class="kianfr--src" /></span>';
				echo '</div>';
				echo '</div>';
			}

			echo '<div class="kianfr--wrap">';
			echo '<input type="text" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes() . '/>';
			echo '<a href="#" class="button button-primary kianfr--button" data-library="' . esc_attr( $library ) . '">' . $args['button_title'] . '</a>';
			echo '<a href="#" class="button button-secondary kianfr-warning-primary kianfr--remove' . esc_attr( $hidden ) . '">' . $args['remove_title'] . '</a>';
			echo '</div>';

			echo $this->field_after();
		}
	}
}
