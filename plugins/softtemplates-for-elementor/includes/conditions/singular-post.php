<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Conditions_Singular_Post' ) ) {

	/**
	 * Define Soft_template_Core_Conditions_Singular_Post class
	 */
	class Soft_template_Core_Conditions_Singular_Post extends Soft_template_Core_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'singular-post';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Post', 'soft-template-core' );
		}

		/**
		 * Condition group
		 *
		 * @return string
		 */
		public function get_group() {
			return 'singular';
		}

		public function get_controls() {
			return array(
				'posts' => array(
					'label'       => __( 'Select Posts', 'soft-template-core' ),
					'type'        => 'softtemplate_search',
					'action'      => 'soft_template_search_posts',
					'label_block' => true,
					'multiple'    => true,
					'description' => __( 'Leave empty to apply for all posts', 'soft-template-core' ),
					'saved'       => $this->get_saved_posts(),
				),
			);
		}

		public function get_saved_posts() {

			$template_id = get_the_ID();
			$saved       = get_post_meta( $template_id, '_elementor_page_settings', true );

			if ( ! empty( $saved['conditions_singular-post_posts'] ) ) {

				$posts = get_posts( array(
					'post_type'           => 'post',
					'post__in'            => $saved['conditions_singular-post_posts'],
					'ignore_sticky_posts' => true,
				) );

				if ( empty( $posts ) ) {
					return array();
				} else {
					return wp_list_pluck( $posts, 'post_title', 'ID' );
				}

			} else {
				return array();
			}
		}

		public function verbose_args( $args ) {

			if ( empty( $args['posts'] ) ) {
				return __( 'All', 'soft-template-core' );
			}

			$result = '';
			$sep    = '';

			foreach ( $args['posts'] as $post ) {
				$result .= $sep . get_the_title( $post );
				$sep     = ', ';
			}

			return $result;
		}

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		public function check( $args ) {

			if ( empty( $args['posts'] ) ) {
				return is_single();
			}

			return is_single( $args['posts'] );
		}

	}

}