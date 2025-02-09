<?php
/**
 * Masteriyo setup.
 *
 * @package Masteriyo
 *
 * @since 1.0.0
 */

namespace Masteriyo;

use Masteriyo\AdminMenu;
use Masteriyo\ScriptStyle;
use Masteriyo\Capabilities;
use Masteriyo\Setup\Onboard;
use Masteriyo\RestApi\RestApi;
use Masteriyo\Emails\EmailHooks;
use Masteriyo\Enums\OrderStatus;
use Masteriyo\PostType\PostType;
use Masteriyo\Query\UserCourseQuery;
use Masteriyo\Enums\CourseAccessMode;
use Masteriyo\Enums\UserCourseStatus;
use Masteriyo\Emails\EmailScheduleActions;
use Masteriyo\Enums\UserStatus;
use Masteriyo\FileRestrictions\FileRestrictions;
use Masteriyo\ShowHideComponents\ShowHideArchiveCourseComponents;
use Masteriyo\ShowHideComponents\ShowHideCategoryCourseComponents;
use Masteriyo\ShowHideComponents\ShowHideInstructorCourseComponents;
use Masteriyo\ShowHideComponents\ShowHideSingleCourseComponents;
use Masteriyo\Tracking\SendAddonsTrackingInfo;

defined( 'ABSPATH' ) || exit;

/**
 * Main Masteriyo class.
 *
 * @class Masteriyo\Masteriyo
 */

class Masteriyo {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Get application version.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function version() {
		return MASTERIYO_VERSION;
	}

	/**
	 * Initialize the application.
	 *
	 * @since 1.0.0
	 */
	protected function init() {
		/**
	 * Fire before masteriyo is initialized.
		 *
		 * @use Initialize addon using this hook.
		 *
		 * @since 1.4.0
		 *
		 * @param \Masteriyo\Masteriyo $masteriyo Masteriyo class object.
		 */
		do_action( 'masteriyo_before_init', $this );

		masteriyo( 'migrator' )->migrate();

		Capabilities::init();
		UserVerification::init();
		Activation::init();
		Deactivation::init();
		FileRestrictions::init();
		EmailScheduleActions::init();
		EmailHooks::init();
		RestApi::init();
		AdminMenu::init();
		ScriptStyle::init();
		( new ShowHideArchiveCourseComponents() )->init();
		( new ShowHideCategoryCourseComponents() )->init();
		( new ShowHideInstructorCourseComponents() )->init();
		( new ShowHideSingleCourseComponents() )->init();
		( new CourseRetake() )->init();
		( new SendAddonsTrackingInfo() )->init();

		$this->define_tables();

		// Initialize the hooks.
		$this->init_hooks();

		/**
		 * Fire after masteriyo is initialized.
		 *
		 * @since 1.4.0
		 *
		 * @param \Masteriyo\Masteriyo $masteriyo Masteriyo class object.
		 */
		do_action( 'masteriyo_after_init', $this );
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 */
	protected function init_hooks() {
		add_action( 'init', array( $this, 'after_wp_init' ), 0 );
		add_action( 'admin_bar_menu', array( $this, 'add_courses_page_link' ), 35 );
		add_action( 'admin_notices', array( $this, 'masteriyo_display_compatibility_notice' ) );

		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_links' ), 10, 2 );
		add_filter( 'plugin_action_links_' . Constants::get( 'MASTERIYO_PLUGIN_BASENAME' ), array( $this, 'add_plugin_action_links' ) );
		add_filter( 'template_include', array( $this, 'template_loader' ), 100 );
		add_filter( 'template_redirect', array( $this, 'redirect_reset_password_link' ) );
		add_action( 'template_redirect', array( $this, 'masteriyo_email_verification_handler' ) );
		add_action( 'template_redirect', array( $this, 'resend_email_verification_email_handler' ) );

		add_action( 'switch_blog', array( $this, 'define_tables' ), 0 );
		add_action( 'admin_init', array( $this, 'admin_redirects' ) );
		add_action( 'after_setup_theme', array( $this, 'add_image_sizes' ) );

		add_action( 'admin_notices', array( $this, 'add_review_notice' ) );
		add_action( 'admin_notices', array( $this, 'display_allow_usage_notice' ) );
		add_action( 'in_admin_header', array( $this, 'hide_admin_notices' ) );
		add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
		add_action( 'admin_enqueue_scripts', 'wp_enqueue_editor' );

		add_action( 'cli_init', array( 'Masteriyo\Cli\Cli', 'register' ) );

		add_filter( 'wp_kses_allowed_html', array( $this, 'register_custom_kses_allowed_html' ), 10, 2 );

		add_filter( 'woocommerce_prevent_admin_access', array( $this, 'prevent_admin_access' ) );

		add_action( 'masteriyo_order_status_changed', array( $this, 'update_user_course_status' ), 10, 4 );

		// Fixed checkout (404) issue when WC is activated.
		// @see https://github.com/woocommerce/woocommerce/blob/76f99a482f6d05094078219225f896db9113f7d3/plugins/woocommerce/includes/wc-template-functions.php#L50
		add_filter( 'woocommerce_account_endpoint_page_not_found', '__return_false' );

		add_filter( 'post_type_archive_title', array( $this, 'update_courses_page_title_tag' ), 0, 2 );

		add_filter( 'masteriyo_start_course_url', array( $this, 'modify_start_url' ), 10, 3 );
		add_filter( 'masteriyo_single_course_start_text', array( $this, 'prepped_lock_sign' ), 10, 2 );
		add_filter( 'masteriyo_single_course_add_to_cart_text', array( $this, 'prepped_lock_sign' ), 10, 2 );
		add_filter( 'masteriyo_single_course_continue_text', array( $this, 'prepped_lock_sign' ), 10, 2 );
		add_filter( 'masteriyo_single_course_completed_text', array( $this, 'prepped_lock_sign' ), 10, 2 );

		// Resolve item metadata insertion issue with WooCommerce plugin active.
		add_action( 'masteriyo_after_order_item_created', array( $this, 'add_order_item_meta' ), 10, 3 );
	}

	/**
	 * Update courses page title tag.
	 *
	 * @since 1.6.7
	 *
	 * @param string $title Page Title.
	 * @param string $post_type Post type
	 * @return string
	 */
	public function update_courses_page_title_tag( $title, $post_type ) {
		if ( PostType::COURSE === $post_type ) {
			$title = masteriyo_page_title( false );
		}

		return $title;
	}

	/**
	 * Initialization after WordPress is initialized.
	 *
	 * @since 1.0.0
	 */
	public function after_wp_init() {

		$this->load_text_domain();
		Install::init();

		$this->restrict_wp_dashboard_and_admin_bar();
		$this->register_order_status();
		$this->setup_wizard();

		$this->handle_paypal_ipn();

		// Download the fonts.
		masteriyo_download_certificate_fonts();

		/**
		 * Fires in 'init' hook of WordPress.
		 *
		 * @since 1.0.0
		 */
		do_action( 'masteriyo_init' );
	}

	/**
	 * Setup wizard method.
	 *
	 * @return void
	 */
	public function setup_wizard() {
		// Setup.
		if ( ! empty( $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( 'masteriyo-onboard' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$onboard_obj = new Onboard();
				$onboard_obj->init();
			}
		}
	}

	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.0.0
	 */
	public function load_text_domain() {
		$locale = determine_locale();

		/**
		 * Filters the masteriyo plugin locale.
		 *
		 * @since 1.0.0
		 *
		 * @param string $locale The plugin locale.
		 * @param string $domain The text domain.
		 */
		$locale = apply_filters( 'plugin_locale', $locale, 'masteriyo' );

		unload_textdomain( 'masteriyo' );
		load_textdomain( 'masteriyo', WP_LANG_DIR . '/masteriyo/masteriyo-' . $locale . '.mo' );
		load_plugin_textdomain( 'masteriyo', false, plugin_basename( dirname( MASTERIYO_PLUGIN_FILE ) ) . '/i18n/languages' );
	}

	/**
	 * Add the "Courses" link in admin bar main menu.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Admin_Bar $wp_admin_bar Admin bar instance.
	 */
	public function add_courses_page_link( $wp_admin_bar ) {
		if ( ! is_admin() || ! is_admin_bar_showing() ) {
			return;
		}

		// Show only when the user is a member of this site, or they're a super admin.
		if ( ! is_user_member_of_blog() && ! is_super_admin() ) {
			return;
		}

		// Add an option to visit the courses page.
		$wp_admin_bar->add_node(
			array(
				'parent' => 'site-name',
				'id'     => 'courses-page',
				'title'  => __( 'Courses', 'masteriyo' ),
				'href'   => masteriyo_get_page_permalink( 'courses' ),
			)
		);
	}

	/**
	 * Add plugin links on the plugins screen.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $links Plugin Row Meta.
	 * @param mixed $file  Plugin Base file.
	 *
	 * @return array
	 */
	public static function add_plugin_links( $links, $file ) {
		if ( Constants::get( 'MASTERIYO_PLUGIN_BASENAME' ) !== $file ) {
			return $links;
		}

		$masteriyo_links = array(
			'docs'    => array(
				'url'        => 'https://docs.masteriyo.com/',
				'label'      => __( 'Docs', 'masteriyo' ),
				'aria-label' => __( 'View Masteriyo documentation', 'masteriyo' ),
			),
			'support' => array(
				'url'        => 'https://wordpress.org/support/plugin/learning-management-system/',
				'label'      => __( 'Community Support', 'masteriyo' ),
				'aria-label' => __( 'Visit community forums', 'masteriyo' ),
			),
			'review'  => array(
				'url'        => 'https://wordpress.org/support/plugin/learning-management-system/reviews/#new-post',
				'label'      => __( 'Rate the plugin ★★★★★', 'masteriyo' ),
				'aria-label' => __( 'Rate the plugin.', 'masteriyo' ),
			),
		);

		foreach ( $masteriyo_links as $key => $link ) {
			$links[ $key ] = sprintf(
				'<a target="_blank" href="%s" aria-label="%s">%s</a>',
				esc_url( $link['url'] ),
				esc_attr( $link['aria-label'] ),
				esc_html( $link['label'] )
			);
		}

		return $links;
	}

	/**
	 * Add action links on the plugins screen.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $links Plugin Action links.
	 *
	 * @return array
	 */
	public static function add_plugin_action_links( $links ) {
		$action_links      = array(
			'settings' => array(
				'url'        => admin_url( 'admin.php?page=masteriyo#/settings' ),
				'label'      => __( 'Settings', 'masteriyo' ),
				'aria-label' => __( 'View Masteriyo settings', 'masteriyo' ),
			),
		);
		$action_links_html = array();

		foreach ( $action_links as $key => $link ) {
			$action_links_html[ $key ] = sprintf(
				'<a href="%s" aria-label="%s">%s</a>',
				esc_url( $link['url'] ),
				esc_attr( $link['aria-label'] ),
				esc_html( $link['label'] )
			);
		}

		return array_merge( $action_links_html, $links );
	}

	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the theme's.
	 *
	 * @since 1.0.0
	 *
	 * @param string $template Template to load.
	 *
	 * @return string
	 */
	public function template_loader( $template ) {
		global $post;

		if ( masteriyo_is_single_course_page() ) {
			masteriyo_setup_course_data( $post );
			$template = masteriyo( 'template' )->locate( 'single-course.php' );
		} elseif ( masteriyo_is_archive_course_page() ) {
			$template = masteriyo( 'template' )->locate( 'archive-course.php' );
		} elseif ( masteriyo_is_learn_page() ) {
			$template = $this->handle_learn_page();
		}

		if ( is_tax( 'course_cat' ) ) {
			$template = masteriyo_locate_template( 'archive-course-category.php' );
		}
		if ( is_author() && is_post_type_archive( PostType::COURSE ) ) {
			$template = masteriyo_locate_template( 'archive-instructor-courses.php' );
		}

		return $template;
	}

	/**
	 * Redirect to password reset form after setting password reset cookie.
	 *
	 * @since 1.0.0
	 */
	public function redirect_reset_password_link() {
		if ( masteriyo_is_account_page() && isset( $_GET['key'] ) && ( isset( $_GET['id'] ) || isset( $_GET['login'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			// If available, get $user_id from query string parameter for fallback purposes.
			if ( isset( $_GET['login'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$user    = get_user_by( 'login', sanitize_user( wp_unslash( $_GET['login'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$user_id = $user ? $user->ID : 0;
			} else {
				$user_id = absint( $_GET['id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}

			// If the reset token is not for the current user, ignore the reset request (don't redirect).
			$logged_in_user_id = get_current_user_id();
			if ( $logged_in_user_id && $logged_in_user_id !== $user_id ) {
				masteriyo_add_notice( __( 'This password reset key is for a different user account. Please log out and try again.', 'masteriyo' ), 'error' );
				return;
			}

			$value = sprintf( '%d:%s', $user_id, wp_unslash( $_GET['key'] ) ); // phpcs:ignore

			masteriyo_set_password_reset_cookie( $value );
			wp_safe_redirect(
				add_query_arg(
					array(
						'show-reset-form' => 'true',
					),
					masteriyo_get_account_endpoint_url( 'reset-password' )
				)
			);
			exit;
		}
	}

	/**
	 * Handles email verification.
	 *
	 * @since 1.6.12
	 *
	 * @return void
	 */
	public function masteriyo_email_verification_handler() {

		if ( ! masteriyo_is_email_verification_enabled() || ! masteriyo_is_account_page() || ! isset( $_GET['token'] ) || ! isset( $_GET['uid'] ) || ! isset( $_GET['nonce'] ) ) {
			return;
		}

		$uid           = isset( $_GET['uid'] ) ? absint( $_GET['uid'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
		$confirm_token = isset( $_GET['token'] ) ? sanitize_key( $_GET['token'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$nonce         = isset( $_GET['nonce'] ) ? sanitize_key( $_GET['nonce'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

		if ( ! $uid || ! $confirm_token || ! $nonce ) {
			return;
		}

		$stored_key = get_user_meta( $uid, 'masteriyo_email_verification_token' . $uid, true );
		$expiration = get_user_meta( $uid, 'masteriyo_email_verification_token_expiration' . $uid, true );

		if ( ! $stored_key || ! $expiration ) {
			return;
		}

		if ( time() > $expiration || $confirm_token !== $stored_key || ! wp_verify_nonce( $nonce, 'masteriyo_email_verification_nonce' ) ) {
			return;
		}
		$user = masteriyo_get_user( $uid );

		if ( is_null( $user ) || is_wp_error( $user ) ) {
			return;
		}

		if ( $user->has_roles( 'masteriyo_instructor' ) ) {
			$user->set_status( UserStatus::INACTIVE );
		} else {
			$user->set_status( UserStatus::HAM );
		}

		$user->save();

		/**
		 * Fires after the completion of user registration.
		 *
		 * @since 1.6.12
		 *
		 * @param array $user->get_id() The id of the user.
		 * @param Masteriyo\Database\Model $user Masteriyo\Database\Model object.
		 */
		do_action( 'masteriyo_after_user_registration_complete', $user->get_id(), $user );

		masteriyo_set_customer_auth_cookie( $uid );

		setcookie( 'isFirstTimeAfterEmailVerification', 'true', time() + ( DAY_IN_SECONDS * 30 ), '/' );

		delete_user_meta( $uid, 'masteriyo_email_verification_token' . $uid );
		delete_user_meta( $uid, 'masteriyo_email_verification_token_expiration' . $uid );

		$account_page_permalink = masteriyo_get_page_permalink( 'account' ) . '/#/dashboard';

		/**
		 * Filters redirection URL to redirect to after user registration.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url Redirection URL.
		 */
		$redirection_url = apply_filters( 'masteriyo_registration_redirect_url', $account_page_permalink );

		$redirection_url = wp_validate_redirect( $redirection_url, $account_page_permalink );

		wp_redirect( $redirection_url ); //phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
		exit;
	}

	/**
	 * Resend the email verification email.
	 *
	 * @since 1.6.12
	 *
	 * @return void
	 */
	public static function resend_email_verification_email_handler() {
		if ( ! masteriyo_is_email_verification_enabled() || ! masteriyo_is_account_page() || ! isset( $_GET['uid'] ) || ! isset( $_GET['resend_email_verification'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}

		$uid                       = isset( $_GET['uid'] ) ? absint( $_GET['uid'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
		$resend_email_verification = isset( $_GET['resend_email_verification'] ) ? masteriyo_string_to_bool( $_GET['resend_email_verification'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification

		if ( ! $uid || ! $resend_email_verification ) {
			return;
		}

		$user = masteriyo_get_user( $uid );

		if ( is_null( $user ) || is_wp_error( $user ) ) {
			return;
		}

		if ( UserStatus::SPAM !== $user->get_status() ) {
			return;
		}

		if ( $user->has_roles( 'masteriyo_student' ) ) {
			EmailHooks::schedule_verification_email_to_student( $uid, $user );
		}

		if ( $user->has_roles( 'masteriyo_instructor' ) ) {
			EmailHooks::schedule_verification_email_to_instructor( $uid, $user );
		}

		masteriyo_add_notice( __( 'An email has been sent to your inbox. Please confirm your email before logging in.', 'masteriyo' ) );

	}

	/**
	 * Redirecting user to onboard or other page.
	 *
	 * @since 1.0.0
	 */
	public function admin_redirects() {

		if ( ! get_transient( '_masteriyo_activation_redirect' ) ) {
			return;
		}

		delete_transient( '_masteriyo_activation_redirect' );

		if ( ( ! empty( $_GET['page'] ) && in_array( $_GET['page'], array( 'masteriyo-onboard' ) ) ) || is_network_admin() || isset( $_GET['activate-multi'] ) || ! current_user_can( 'manage_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.PHP.StrictInArray.MissingTrueStrict
			return;
		}

		// If plugin is running for first time, redirect to onboard page.
		if ( '1' !== get_option( 'masteriyo_first_time_activation_flag' ) ) {
			wp_safe_redirect( admin_url( 'index.php?page=masteriyo-onboard' ) );
			exit;
		}

	}

	/**
	 * Register order status.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_order_status() {
		$order_statuses = \masteriyo_get_order_statuses();

		foreach ( $order_statuses as $order_status => $values ) {
			register_post_status( $order_status, $values );
		}
	}

	/**
	 * Display admin notices.
	 *
	 * @since 1.0.0
	 */
	public function masteriyo_display_compatibility_notice() {
		if ( version_compare( get_bloginfo( 'version' ), '5.0', '<' ) ) {
			// translators: %s: Dismiss link
			echo wp_sprintf(
				'<div class="notice notice-warning"><p><strong>%s</strong>: %s</p></div>',
				'Masteriyo',
				esc_html__( 'Minimum WordPress version required for Masteriyo to work is v5.0.', 'masteriyo' )
			);
		}
	}

	/**
	 * Add admin review notice.
	 *
	 * @since 1.4.0
	 */
	public function add_review_notice() {
		if ( ! masteriyo_is_show_review_notice() ) {
			return;
		}
		masteriyo_get_template( 'notices/ask-review.php' );
	}

	/**
	 * Display admin allow usage tracking notice.
	 *
	 * @since 1.6.7
	 */
	public function display_allow_usage_notice() {
		if ( masteriyo_show_usage_tracking_notice() ) {
			masteriyo_get_template( 'notices/allow-usage-tracking.php' );
		}
	}

	/**
	 * Hide admin notices from Masteriyo admin screens.
	 *
	 * @since 1.0.0
	 */
	public function hide_admin_notices() {
		// Bail if we're not on a Masteriyo screen or page.
		if ( empty( $_REQUEST['page'] ) || false === strpos( sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ), 'masteriyo' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}

		global $wp_filter;
		$ignore_notices = array( 'masteriyo_display_compatibility_notice' );

		foreach ( array( 'user_admin_notices', 'admin_notices', 'all_admin_notices' ) as $wp_notice ) {
			if ( empty( $wp_filter[ $wp_notice ] ) ) {
				continue;
			}

			$hook_callbacks = $wp_filter[ $wp_notice ]->callbacks;

			if ( empty( $hook_callbacks ) || ! is_array( $hook_callbacks ) ) {
				continue;
			}

			foreach ( $hook_callbacks as $priority => $hooks ) {
				foreach ( $hooks as $name => $callback ) {
					if ( ! empty( $name ) && in_array( $name, $ignore_notices, true ) ) {
						continue;
					}
					if (
						! empty( $callback['function'] ) &&
						! is_a( $callback['function'], '\Closure' ) &&
						isset( $callback['function'][0], $callback['function'][1] ) &&
						is_object( $callback['function'][0] ) &&
						in_array( $callback['function'][1], $ignore_notices, true )
					) {
						continue;
					}
					unset( $wp_filter[ $wp_notice ]->callbacks[ $priority ][ $name ] );
				}
			}
		}
	}

	/**
	 * Register custom tables within $wpdb object.
	 *
	 * @since 1.0.0
	 */
	public function define_tables() {
		global $wpdb;

		// List of tables without prefixes.
		$tables = array(
			'order_itemmeta'    => 'masteriyo_order_itemmeta',
			'user_activitymeta' => 'masteriyo_user_activitymeta',
			'user_itemmeta'     => 'masteriyo_user_itemmeta',
		);

		foreach ( $tables as $name => $table ) {
			$wpdb->$name    = $wpdb->prefix . $table;
			$wpdb->tables[] = $table;
		}
	}

	/**
	 * Handle Paypal IPN listener.
	 *
	 * @since 1.0.0
	 */
	public function handle_paypal_ipn() {
		// phpcs:disable
		if ( ( isset( $_POST['paypalListener'] ) && 'paypal_standard_IPN' === $_POST['paypalListener'] )
			|| isset( $_POST['test_ipn'] ) && '1' === $_POST['test_ipn'] ) {
			masteriyo( 'payment-gateways' )->get_available_payment_gateways();

			/**
			 * Fires in 'init' hook for handling paypal gateway.
			 *
			 * @since 1.0.0
			 */
			do_action( 'masteriyo_api_gateway_paypal' );
		}
		// phpcs:enable
	}

	/**
	 * Add image sizes.
	 *
	 * @since 1.0.0
	 */
	public function add_image_sizes() {
		add_image_size( 'masteriyo_single', 1584, 992, true );
		add_image_size( 'masteriyo_thumbnail', 540, 340, true );
		add_image_size( 'masteriyo_medium', 360, 224, true );
	}

	/**
	 * Disable wp dashboard and admin bar for student.
	 *
	 * @since 1.0.0
	 */
	public function restrict_wp_dashboard_and_admin_bar() {
		// If admin and instructor have students role, giving dashboard access.
		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_instructor() ) {
			return;
		}

		if ( masteriyo_is_current_user_student() ) {
			add_filter( 'show_admin_bar', '__return_false' );

			if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
				return;
			}

			wp_safe_redirect( home_url() );

			exit;
		}
	}

	/**
	 * Register custom kses allowed html.
	 *
	 * @since 1.3.10
	 *
	 * @return array
	 */
	public function register_custom_kses_allowed_html( $allowed_tags, $context ) {

		// Add iframe to the list of allowed tags in 'post' context.
		if ( 'post' === $context ) {
			$allowed_tags = masteriyo_add_iframe_to_post_context( $allowed_tags );
		}

		$custom_context = array( 'masteriyo_image', 'masteriyo_pagination' );

		if ( ! in_array( $context, $custom_context, true ) ) {
			return $allowed_tags;
		}

		switch ( $context ) {
			case 'masteriyo_image':
				return array(
					'div'  => array(
						'class'  => true,
						'id'     => true,
						'data-*' => true,
					),
					'span' => array(
						'class'  => true,
						'id'     => true,
						'data-*' => true,
					),
					'img'  => array(
						'width'    => true,
						'height'   => true,
						'src'      => true,
						'class'    => true,
						'id'       => true,
						'alt'      => true,
						'loading'  => true,
						'srcset'   => true,
						'longdesc' => true,
						'sizes'    => true,
						'data-*'   => true,
					),
				);
			case 'masteriyo_pagination':
				return array(
					'ul'   => array(
						'class' => array(),
					),
					'li'   => array(
						'class' => array(),
					),
					'span' => array(
						'class'        => array(),
						'aria-current' => array(),
					),
					'a'    => array(
						'class' => array(),
						'href'  => array(),
					),
					'svg'  => array(
						'class'   => array(),
						'xmlns'   => array(),
						'width'   => array(),
						'height'  => array(),
						'viewBox' => array(),
					),
					'path' => array(
						'd' => array(),
					),
				);
			default:
				return $allowed_tags;
		}
	}

	/**
	 * Return learn page template.
	 *
	 * @since 1.4.1
	 *
	 * @return string
	 */
	protected function handle_learn_page() {
		$preview   = masteriyo_string_to_bool( get_query_var( 'mto-preview', false ) );
		$course_id = get_query_var( 'course_name', 0 );

		if ( '' === get_option( 'permalink_structure' ) || $preview ) {
			$course_id = get_query_var( 'course_name', 0 );
		} else {
			$course_slug = get_query_var( 'course_name', '' );

			$courses = get_posts(
				array(
					'post_type'   => 'mto-course',
					'name'        => $course_slug,
					'numberposts' => 1,
					'fields'      => 'ids',
				)
			);

			$course_id = is_array( $courses ) ? array_shift( $courses ) : 0;
		}

		$course = masteriyo_get_course( $course_id );

		// Bail early if the course doesn't exits.
		if ( is_null( $course ) ) {
			wp_safe_redirect( \masteriyo_get_courses_url(), 307 );
			exit();
		}

		if ( ! ( $preview && masteriyo_is_course_previewable( $course ) ) ) {
			if ( CourseAccessMode::OPEN === $course->get_access_mode() && ! is_user_logged_in() ) {
				masteriyo( 'session' )->set_user_session_cookie( true );
			} else {
				$query = new UserCourseQuery(
					array(
						'course_id' => $course_id,
						'user_id'   => get_current_user_id(),
					)
				);

				$user_courses = $query->get_user_courses();

				if (
					empty( $user_courses ) &&
					(
						in_array( $course->get_access_mode(), array( CourseAccessMode::OPEN, CourseAccessMode::NEED_REGISTRATION ), true ) ||
						masteriyo_check_course_content_access_for_current_user( $course )
					)
				) {
					$user_courses = masteriyo( 'user-course' );
					$user_courses->set_status( UserCourseStatus::ACTIVE );
					$user_courses->set_course_id( $course_id );
					$user_courses->set_user_id( get_current_user_id() );
					$user_courses->set_date_start( current_time( 'mysql', true ) );
					$user_courses->save();
					$user_courses->set_object_read( true );
				}

				if ( empty( $user_courses ) || ! masteriyo_can_start_course( $course_id, get_current_user_id() ) ) {
					wp_safe_redirect( \masteriyo_get_courses_url(), 307 );
					exit();
				}
			}
		}

		/**
		 * Fires at the end of learn page handle.
		 *
		 * @since 1.5.10
		 *
		 * @param \Masteriyo\Models\Course $course Course object.
		 */
		do_action( 'masteriyo_after_learn_page_process', $course );

		return masteriyo_locate_template( 'learn.php' );
	}

	/**
	 * Allow admin access to approved instructor.
	 *
	 * Compatibility with WooCommerce
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/f04e0761a6c200e57e8a9df45c62b5e76b2f058a/plugins/woocommerce/includes/admin/class-wc-admin.php#L157
	 *
	 * @since 1.5.3
	 *
	 * @param boolean $prevent_access
	 * @return boolean
	 */
	public function prevent_admin_access( $prevent_access ) {
		$instructor = masteriyo_get_current_instructor();

		if ( $instructor ) {
			$prevent_access = $instructor->is_active() ? false : true;
		}

		return $prevent_access;
	}

	/**
	 * Update user course status.
	 *
	 * @since 1.5.4
	 *
	 * @param integer $id order ID.
	 * @param string $old_status Old status.
	 * @param string $new_status New status.
	 * @param \Masteriyo\Models\Order\Order $order The order object.
	 */
	public function update_user_course_status( $order_id, $from, $to, $order ) {

		foreach ( $order->get_items() as $order_item ) {
			$user_course = masteriyo_get_user_course_by_user_and_course( $order->get_customer_id(), $order_item->get_course_id() );

			if ( $user_course ) {
				$status = OrderStatus::COMPLETED === $order->get_status() ? UserCourseStatus::ACTIVE : UserCourseStatus::INACTIVE;
				$user_course->set_status( $status );
				$user_course->save();
			}
		}
	}

	/**
	 * Modify start URL or courses with memberships.
	 *
	 * @since 1.8.0
	 *
	 * @param string $url start URL.
	 * @param \Masteriyo\Models\Course $course Course object.
	 * @param boolean $append_first_lesson_or_quiz Whether to append first lesson or quiz or not.
	 *
	 * @return string
	 */
	public function modify_start_url( $url, $course, $append_first_lesson_or_quiz ) {

		if ( $course && post_password_required( get_post( $course->get_id() ) ) ) {
			$base_url = ( function_exists( 'is_feed' ) && is_feed() ) || ( function_exists( 'is_404' ) && is_404() ) ? $course->get_permalink() : '';

			$url = add_query_arg(
				array(
					'add-to-cart' => $course->get_id(),
				),
				$base_url
			);
		}

		return $url;

	}

	/**
	 * Prepend lock sign to enroll button if the course is password protected.
	 *
	 * @since 1.8.0
	 *
	 * @return string
	 */
	public function prepped_lock_sign( $text, $course ) {
		if ( $course && post_password_required( get_post( $course->get_id() ) ) ) {
			return '<span class="dashicons dashicons-lock" style="margin-right: 5px"></span>' . $text;
		}

		return $text;
	}

	/**
	 * Adds order item meta for a given order item.
	 *
	 * Ensures meta data is not duplicated by checking if the order item meta already exists in the database.
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 *
	 * @param int $item_id The order item ID.
	 * @param \Masteriyo\Models\Order\OrderItemCourse $item The order item object.
	 *
	 * @param int $order_id The order ID.
	 */
	public function add_order_item_meta( $item_id, $item, $order_id ) {
		global $wpdb;

		if ( ! $wpdb || ! $item_id || ! $item || ! $order_id ) {
			return;
		}

		if ( ! in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins', array() ), true ) ) {
			return;
		}

		$table_name = $wpdb->prefix . 'masteriyo_order_itemmeta';

		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$count = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$table_name} WHERE order_item_id = %d",
				$item_id
			)
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		if ( $count > 0 ) {
			return;
		}

		$itemmeta_data = masteriyo_build_item_meta_data( $item_id, $item );

		if ( ! is_array( $itemmeta_data ) || empty( $itemmeta_data ) ) {
			return;
		}

		masteriyo_insert_item_meta_batch( $itemmeta_data );
	}
}
