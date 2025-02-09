<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * Create the post type to hold the codes. We use a normal WordPress Post Type and post meta to create and manage codes and code meta data.
 *
 * Post Type: tk_invite_codes
 *
 * @since  0.1
 */
function all_in_one_invite_codes_register_post_type() {

	$labels = array(
		'name'          => __( 'Invite Codes', 'all_in_one_invite_codes' ),
		'singular_name' => __( 'Invite Code', 'all_in_one_invite_codes' ),
	);

	$args = array(
		'label'                 => __( 'Invite Codes', 'all_in_one_invite_codes' ),
		'labels'                => $labels,
		'description'           => '',
		'public'                => false,
		'publicly_queryable'    => false,
		'show_ui'               => true,
		'delete_with_user'      => false,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'has_archive'           => false,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => false,
		'exclude_from_search'   => true,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => true,
		'rewrite'               => array(
			'slug'       => 'tk_invite_codes',
			'with_front' => false,
		),
		'query_var'             => true,
		'supports'              => false,
	);

	register_post_type( 'tk_invite_codes', $args );
}

add_action( 'init', 'all_in_one_invite_codes_register_post_type' );


/**
 *
 * Add the actions to list table
 *
 * @param $actions
 * @param $post
 *
 * @return mixed
 */
function all_in_one_invite_codes_add_action_buttons( $actions, $post ) {

	if ( get_post_type() === 'tk_invite_codes' ) {

		// No quick edit please
		unset( $actions['inline hide-if-no-js'] );

		// Add resent and disable links as list actions
		$actions['disable'] = '<a href="#" data-post_id="' . $post->ID . '" id="all_in_one_disable_invite_code">Disable</a>';
		// $actions['resent'] = '<a href="#" data-post_id="' . $post->ID . '" id="all_in_one_resent_invite_code">Resent Invitation</a>';

	}

	return $actions;
}

add_filter( 'page_row_actions', 'all_in_one_invite_codes_add_action_buttons', 10, 2 );

/**
 *
 * Generate the table header for the invite code columns
 *
 * @param $columns
 * @param $post_id
 *
 * @return array
 */
function tk_invite_codes_columns( $columns, $post_id = false ) {
	unset( $columns['date'] );
	unset( $columns['title'] );

	$columns['code']           = __( 'Code', 'all-in-one-invite-codes' );
	$columns['code_status']    = __( 'Status', 'all-in-one-invite-codes' );
	$columns['email']          = __( 'eMail', 'all-in-one-invite-codes' );
	$columns['multiple_use']   = __( 'Multiple Use', 'all-in-one-invite-codes' );
	$columns['generate_codes'] = __( 'Generate new codes after account activation', 'all-in-one-invite-codes' );

	return $columns;
}

add_action( 'manage_tk_invite_codes_posts_columns', 'tk_invite_codes_columns', 102, 2 );

/**
 *
 * Display the invite code meta data as columns
 *
 * @param $columns
 * @param $post_id
 *
 * @return array
 */
function custom_tk_invite_codes_columns( $columns, $post_id = false ) {

	$all_in_one_invite_codes_options = get_post_meta( $post_id, 'all_in_one_invite_codes_options', true );

	switch ( $columns ) {
		case 'code':
			echo wp_kses_post( get_post_meta( $post_id, 'tk_all_in_one_invite_code', true ) );
			break;
		case 'code_status':
			echo wp_kses_post( all_in_one_invite_codes_get_status( $post_id ) );
			break;
		case 'email':
			echo isset( $all_in_one_invite_codes_options['email'] ) ? wp_kses_post( $all_in_one_invite_codes_options['email'] ) : '--';
			break;
		case 'generate_codes':
			echo wp_kses_post( all_in_one_invite_codes_get_generate_code( $post_id ) );
			break;
		case 'multiple_use':
			echo wp_kses_post( all_in_one_invite_codes_get_multiple_use( $post_id ) );
			break;
	}

	return $columns;

}
function all_in_one_invite_codes_get_generate_code( $invite_code_id ) {
	if ( $invite_code_id ) {
		$all_in_one_invite_codes_options = get_post_meta( get_the_ID(), 'all_in_one_invite_codes_options', true );
		$code_amount                     = isset( $all_in_one_invite_codes_options['generate_codes'] ) ? intval( $all_in_one_invite_codes_options['generate_codes'] ) : 1;
		$is_multiple_use                 = isset( $all_in_one_invite_codes_options['multiple_use'] ) ? true : false;
		$code_total                      = isset( $all_in_one_invite_codes_options['code_total'] ) ? intval( $all_in_one_invite_codes_options['code_total'] ) : $code_amount;
		if ( ! $is_multiple_use ) {

			return isset( $all_in_one_invite_codes_options['generate_codes'] ) ? $all_in_one_invite_codes_options['generate_codes'] : '--';
		} else {

			return '--';
		}
	}

}


function all_in_one_invite_codes_get_multiple_use( $invite_code_id ) {

	if ( $invite_code_id ) {
		$all_in_one_invite_codes_options = get_post_meta( get_the_ID(), 'all_in_one_invite_codes_options', true );
		$code_amount                     = isset( $all_in_one_invite_codes_options['generate_codes'] ) ? intval( $all_in_one_invite_codes_options['generate_codes'] ) : 1;
		$is_multiple_use                 = isset( $all_in_one_invite_codes_options['multiple_use'] ) ? true : false;
		$code_total                      = isset( $all_in_one_invite_codes_options['code_total'] ) ? intval( $all_in_one_invite_codes_options['code_total'] ) : $code_amount;
		if ( $is_multiple_use ) {

			return sprintf( '%d/%d', $code_amount, $code_total );
		} else {

			return '--';
		}
	}

}

add_action( 'manage_tk_invite_codes_posts_custom_column', 'custom_tk_invite_codes_columns', 10, 2 );

/**
 *
 * Adds a metabox to the main column on the Code edit screen
 */
function all_in_one_invite_codes_hide_publishing_actions() {
	global $post;

	// The edit screen of custom post types coes with for our solution unneeded information or actions.
	// So lets hide and remove not relevant functionality and keep the UI simple!
	if ( get_post_type( $post ) == 'tk_invite_codes' ) { ?>
		<style type="text/css">
			.misc-pub-visibility,
			.misc-pub-curtime,
			.misc-pub-post-status {
				display: none;
			}

			h1 {
				display: none;
			}

			.metabox-prefs label {
				/* float: right; */
				/* margin-top: 57px; */
				/*width: 100%;*/
			}

			/* Sven Quick Fix ToDo: Konrad please check it;) */
			.wrap .wp-heading-inline, .page-title-action {
				display: none;
			}

			#postbox-container-1, #postbox-container-2 {
				margin-top: 50px;
			}

			#minor-publishing-actions {
				display: none;
			}

		</style>
		<script>
			jQuery(document).ready(function (jQuery) {

				jQuery('body').find('h1:first').remove();
				jQuery('body').find('#post-body-content').remove();
				jQuery('body').find('.wp-heading-inline').remove();

				<?php
				$status = get_post_meta( $post->ID, 'tk_all_in_one_invite_code_status', true );
				if ( $status == 'disabled' ) {
					?>
				jQuery('body').find('.postbox-container h2').text('Disabled');
				jQuery('body').find('#publish').remove();
				jQuery("#post :input").prop("disabled", true);
				<?php } ?>

			});
		</script>
		<?php
	}

}

// add_action( 'admin_head-edit.php', 'all_in_one_invite_codes_hide_publishing_actions' );
add_action( 'admin_head-post.php', 'all_in_one_invite_codes_hide_publishing_actions' );
add_action( 'admin_head-post-new.php', 'all_in_one_invite_codes_hide_publishing_actions' );

/**
 *
 * Now that we have removed the wp action buttons we need to add new Actions Buttons to the publish metabox
 */
function all_in_one_invite_codes_add_button_to_submit_box() {
	global $post;

	if ( get_post_type( $post ) != 'tk_invite_codes' ) {
		return;
	}

	if ( get_post_status() != 'publish' ) {
		return;
	}
	?>

	<div id="all-in-one-invite-codes-actions" class="misc-pub-section">
		<p>Invite Code:
			<small><?php echo wp_kses_post( all_in_one_invite_codes_md5() ); ?></small>
		</p>
		<p><a href="#" data-post_id="<?php echo esc_attr( $post->ID ); ?>" id="all_in_one_disable_invite_code"
			  class="button button-large bf_button_action">Disable This Invite Code</a></p>
		<!--        <p><a href="#" data-post_id="--><?php // echo $post->ID ?><!--" id="all_in_one_resent_invite_code"-->
		<!--              class="button button-large bf_button_action">Resent Invitation Mail</a></p>-->
		<div class="clear"></div>
	</div>

	<?php

}

add_action( 'post_submitbox_misc_actions', 'all_in_one_invite_codes_add_button_to_submit_box' );

