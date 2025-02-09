<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class BWFAN_Admin
 */
#[AllowDynamicProperties]
class BWFAN_Admin {

	private static $ins = null;
	public $admin_path;
	public $admin_url;
	public $section_page = '';
	public $should_show_shortcodes = null;
	public $events_js_data = array();
	public $actions_js_data = array();
	public $select2ajax_js_data = array();
	public $dashboard_page;

	public function __construct() {
		$this->admin_path = BWFAN_PLUGIN_DIR . '/admin';
		$this->admin_url  = BWFAN_PLUGIN_URL . '/admin';
		$this->include_admin_pages();
		$this->init_admin_pages();
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 90 );
		add_filter( 'plugin_action_links_' . BWFAN_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ), 10, 2 );

		/**
		 * Admin enqueue scripts
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ), 99 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_settings_page' ), 99 );

		/**
		 * Admin footer text
		 */
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 99999, 1 );
		add_filter( 'update_footer', array( $this, 'update_footer' ), 9999, 1 );

		add_action( 'admin_head', array( $this, 'js_variables' ) );
		add_action( 'admin_init', array( $this, 'maybe_set_automation_id' ) );
		add_action( 'admin_head', array( $this, 'change_autonami_menu_icon' ), - 1 );

		/** Hooks to check if activation and deactivation request for post. */
		add_filter( 'plugin_action_links_' . BWFAN_PLUGIN_BASENAME, array( $this, 'plugin_actions' ) );

		/** Scheduling actions */
		add_action( 'admin_init', array( $this, 'maybe_set_as_ct_worker' ), 12 );
		add_action( 'admin_init', array( $this, 'schedule_abandoned_cart_cron' ) );
		add_action( 'wp', array( $this, 'maybe_set_as_ct_worker' ) );
		add_action( 'wp', array( $this, 'schedule_abandoned_cart_cron' ) );

		add_action( 'in_admin_header', array( $this, 'maybe_remove_all_notices_on_page' ) );

		add_action( 'admin_init', array( $this, 'maybe_handle_optin_choice' ), 14 );

		add_action( 'admin_notices', array( $this, 'maybe_show_sandbox_mode_notice' ) );

		/** Create automation earlier */
		add_action( 'admin_init', array( $this, 'maybe_redirect_to_automation' ), 15 );

		/** Enable reset tracking setting in global woofunnels tools */
		add_filter( 'bwf_needs_order_indexing', '__return_true' );

		/** redirect the connector page to autonami-automations tabs connector page */
		add_action( 'admin_init', array( $this, 'redirect_autonami_connector_page' ), 99999 );

		/** Automation builder, modify events action array */
		add_filter( 'bwfan_modify_actions_groups', array( $this, 'automation_modify_actions_groups' ) );
		add_filter( 'bwfan_modify_integrations', array( $this, 'automation_modify_integrations' ) );

		add_action( 'personal_options', array( $this, 'bwfan_add_contact_profile_link' ), 10, 1 );

		/** Add Total spent column on WC orders listing page */
		add_filter( 'manage_edit-shop_order_columns', array( $this, 'bwfan_add_order_contact_column_header' ), 20 );
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'bwfan_add_order_contact_column_content' ), 20, 2 );

		/** Add Total spent column on WC orders listing page when HPOS enabled */
		add_filter( 'woocommerce_shop_order_list_table_columns', array( $this, 'bwfan_add_order_contact_column_header' ), 20 );
		add_action( 'woocommerce_shop_order_list_table_custom_column', array( $this, 'bwfan_add_order_contact_column_content' ), 20, 2 );

		add_action( 'add_meta_boxes', array( $this, 'bwf_add_single_order_meta_box' ), 50, 2 );
		add_filter( 'user_row_actions', array( $this, 'bwf_user_list_add_contact_link' ), 10, 2 );

		/** Validating & removing scripts on page load */
		add_action( 'admin_print_styles', array( $this, 'bwfan_removing_scripts_single_ui' ), - 1 );
		add_action( 'admin_print_scripts', array( $this, 'bwfan_removing_scripts_single_ui' ), - 1 );
		add_action( 'admin_print_footer_scripts', array( $this, 'bwfan_removing_scripts_single_ui' ), - 1 );

		/** redirect on autonami list page */
		add_action( 'load-autonami_page_autonami-automations', array( $this, 'redirect_autonami_automations_page' ) );

		/** Load font and size selector */
		add_filter( 'mce_buttons', array( $this, 'add_tinymce_options' ), 999 );
		add_action( 'wp_ajax_bwf_migrate_automation', array( $this, 'bwfan_migrate_automation' ) );
	}

	/**
	 * Set meta key for v1 migrated automation
	 * @return void
	 */
	function bwfan_migrate_automation() {
		BWFAN_Common::check_nonce();

		// phpcs:disable WordPress.Security.NonceVerification
		if ( empty( $_POST['automation_id'] ) ) {
			$resp = array(
				'msg'    => 'Automation ID is missing',
				'status' => false,
			);
			wp_send_json( $resp );
		}

		$id     = sanitize_text_field( $_POST['automation_id'] );
		$result = BWFAN_Model_Automationmeta::insert_automation_meta_data( $id, [
			'v1_migrate' => true,
		] );

		$resp = array(
			'msg'    => 'Automation migrated',
			'status' => true,
		);

		if ( ! $result ) {
			$resp = array(
				'msg'    => 'Unable to update automation',
				'status' => false,
			);
		}
		//phpcs:enable WordPress.Security.NonceVerification

		wp_send_json( $resp );
	}

	/** Load font selector and size selector */
	function add_tinymce_options( $toolbar2 ) {
		$temp = [
			'fontselect',
			'fontsizeselect',
		];

		return array_merge( $temp, $toolbar2 );
	}

	public function include_admin_pages() {
		include_once $this->admin_path . '/class-bwfcrm-base-react-page.php';
		include_once $this->admin_path . '/view/class-bwfcrm-dashboard.php';
	}

	public function init_admin_pages() {
		$this->dashboard_page = BWFCRM_Dashboard::get_instance();
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/** removing the script from other plugin on single ui */
	function bwfan_removing_scripts_single_ui() {
		global $wp_scripts, $wp_styles;

		if ( ! BWFAN_Common::is_load_admin_assets( 'all' ) ) {
			return;
		}

		$mod_wp_scripts = $wp_scripts;
		$assets         = $wp_scripts;

		if ( 'admin_print_styles' == current_action() ) {
			$mod_wp_scripts = $wp_styles;
			$assets         = $wp_styles;
		}

		if ( is_object( $assets ) && isset( $assets->registered ) && count( $assets->registered ) > 0 ) {
			foreach ( $assets->registered as $handle => $script_obj ) {
				if ( ! isset( $script_obj->src ) || empty( $script_obj->src ) ) {
					continue;
				}

				$src = $script_obj->src;

				/** Remove scripts of massive VC addons plugin */
				if ( strpos( $src, 'wp-cloudflare-page-cache/' ) !== false ) {
					unset( $mod_wp_scripts->registered[ $handle ] );
				}

				/** Remove scripts of massive bigscoots cache plugin */
				if ( strpos( $src, 'bigscoots-cache/' ) !== false ) {
					unset( $mod_wp_scripts->registered[ $handle ] );
				}

				/** remove scripts of gm-woocommerce-quote-popup */
				if ( strpos( $src, 'gm-woocommerce-quote-popup/' ) !== false ) {
					unset( $mod_wp_scripts->registered[ $handle ] );
				}

				/** remove scripts of themes Jupiter Core */
				if ( strpos( $src, 'themes/jupiter/' ) !== false ) {
					unset( $mod_wp_scripts->registered[ $handle ] );
				}

				/** remove scripts of location domination */
				if ( strpos( $src, 'location-domination-wordpress-stable/' ) !== false ) {
					unset( $mod_wp_scripts->registered[ $handle ] );
				}

				/** remove scripts of Embed Plus Plugin For Youtube  */
				if ( strpos( $src, 'youtube-embed-plus/' ) !== false ) {
					unset( $mod_wp_scripts->registered[ $handle ] );
				}
			}
		}

		if ( 'admin_print_styles' == current_action() ) {
			$wp_styles = $mod_wp_scripts;
		} else {
			$wp_scripts = $mod_wp_scripts;
		}
	}

	public function maybe_show_sandbox_mode_notice() {
		/** Check if Autonami is in sandbox mode */
		if ( false === BWFAN_Common::is_sandbox_mode_active() ) {
			return;
		}
		?>
        <div class="notice notice-warning" style="display: block!important;">
            <p>
				<?php
				echo __( '<strong>Warning! FunnelKit Automations is in Sandbox Mode</strong>. New Tasks will not be created & existing Tasks will not execute.', 'wp-marketing-automations' );
				?>
            </p>
        </div>
		<?php
	}

	public function get_admin_url() {
		return plugin_dir_url( BWFAN_PLUGIN_FILE ) . 'admin';
	}

	public function register_admin_menu() {
		$capability = $this->menu_cap();

		/** Check if Autonami is in sandbox mode */
		$title = 'FunnelKit Automations';
		if ( true === BWFAN_Common::is_sandbox_mode_active() ) {
			$title .= ' <span style="background-color:#ca4a1f;border-radius:10px;margin-left:0;font-size:10px;padding:3px 6px;">Sandbox</span>';
		}
		add_menu_page( false, $title, $capability, 'autonami', array( $this, 'autonami_page' ), '', 59 );

		add_submenu_page( 'autonami', __( 'Dashboard', 'wp-marketing-automations' ), __( 'Dashboard', 'wp-marketing-automations' ), $capability, 'autonami', false, 10 );

		if ( true === bwfan_is_autonami_pro_active() ) {
			add_submenu_page( 'autonami', 'Contacts', 'Contacts', $capability, 'autonami&path=/contacts', array( $this, 'autonami_page' ), 20 );
		}

		if ( false !== BWFAN_Plugin_Dependency::woocommerce_active_check() ) {
			add_submenu_page( 'autonami', __( 'Carts', 'wp-marketing-automations' ), 'Carts', $capability, 'autonami&path=/carts/recoverable', function () {
			}, 27 );

			$position = apply_filters( 'bwfan_cart_submenu_position', 5 );
			$position = ( empty( absint( $position ) ) ) ? 5 : absint( $position );

			add_submenu_page( 'woocommerce', __( 'Carts', 'wp-marketing-automations' ), __( 'Carts', 'wp-marketing-automations' ), $capability, 'admin.php?page=autonami&path=/carts/recoverable', false, $position );
		}

		add_submenu_page( 'autonami', 'Automations', 'Automations', $capability, 'autonami&path=/automations', array(
			$this,
			'autonami_automations_page'
		), 24 );

		if ( BWFAN_Common::is_automation_v1_active() ) {
			add_submenu_page( 'autonami', 'Automations', 'Automations <span style="background-color:#ece6e4; color: #000;white-space: nowrap; border-radius:10px;margin-left:2px;font-size:10px;padding:3px 6px;">Legacy</span>', $capability, 'autonami-automations', array(
				$this,
				'autonami_automations_page'
			), 25 );
		}

		if ( true === bwfan_is_autonami_pro_active() ) {
			add_submenu_page( 'autonami', 'Broadcasts', 'Broadcasts', $capability, 'autonami&path=/broadcasts/email', function () {
			}, 30 );

			add_submenu_page( 'autonami', 'Templates', 'Templates', $capability, 'autonami&path=/templates', function () {
			}, 30 );

			add_submenu_page( 'autonami', 'Forms', 'Forms', $capability, 'autonami&path=/forms', function () {
			}, 50 );

			add_submenu_page( 'autonami', 'Link Triggers', 'Link Triggers', $capability, 'autonami&path=/link-triggers', function () {
			}, 50 );
		}

		if ( ! get_option( 'bwfan_smtp_recommend', false ) ) {
			add_submenu_page( 'autonami', 'Email Setup', 'Email Setup', $capability, 'autonami&path=/mail-setup', function () {
			}, 50 );
		}

		add_submenu_page( 'autonami', __( 'Analytics', 'wp-marketing-automations' ), __( 'Analytics', 'wp-marketing-automations' ), $capability, 'autonami&path=/analytics', function () {
		}, 15 );

		if ( true === bwfan_is_autonami_pro_active() ) {
			add_submenu_page( 'autonami', __( 'Connectors', 'wp-marketing-automations' ), __( 'Connectors', 'wp-marketing-automations' ), $capability, 'autonami&path=/connectors', function () {
			}, 15 );
		}

		add_submenu_page( 'autonami', __( 'Settings', 'wp-marketing-automations' ), __( 'Settings', 'wp-marketing-automations' ), $capability, 'autonami&path=/settings', function () {
		}, 45 );

		/** Adding Buy Pro sub menu when pro not activated */
		if ( false === bwfan_is_autonami_pro_active() ) {
			$url  = BWFAN_Common::get_fk_site_links();
			$url  = isset( $url['upgrade'] ) ? $url['upgrade'] : '';
			$link = add_query_arg( [
				'utm_medium' => 'Admin+Menu'
			], $url );
			add_submenu_page( 'autonami', '', '<a href="' . $link . '" style="background-color:#1DA867; color:white;" target="_blank"><strong>' . __( 'Upgrade to Pro', 'wp-marketing-automations' ) . '</strong></a>', $capability, '', function () {
			}, 60 );

			$time = strtotime( gmdate( 'c' ) );
			if ( $time >= 1700456400 && $time < 1701493200 ) {
				$utm_campaign = 'CM' . date( 'Y' );
				$title        = "Cyber Monday";
				if ( $time < 1701061200 ) {
					$utm_campaign = 'BF' . date( 'Y' );
					$title        = "Black Friday";
				}
				$title .= " 🔥";
				$link  = add_query_arg( [
					'utm_source'   => 'WordPress',
					'utm_medium'   => 'Admin+Menu+FKA',
					'utm_campaign' => $utm_campaign
				], $url );
				add_submenu_page( 'autonami', '', '<a href="' . $link . '"  target="_blank">' . $title . '</a>', $capability, 'upgrade_pro', function () {
				}, 61 );
			} elseif ( $time >= 1702270800 && $time < 1702357200 ) {
				$link  = add_query_arg( [
					'utm_source'   => 'WordPress',
					'utm_medium'   => 'Admin+Menu+FKA',
					'utm_campaign' => 'GM' . date( 'Y' )
				], $url );
				$title = "Green Monday 🔥";
				add_submenu_page( 'autonami', '', '<a href="' . $link . '"  target="_blank">' . $title . '</a>', $capability, 'upgrade_pro', function () {
				}, 61 );
			}
		}
	}

	/**
	 * Modify the plugin action links
	 *
	 * @param $actions
	 * @param $plugin_file
	 *
	 * @return array|mixed
	 */
	public function plugin_action_links( $actions, $plugin_file ) {
		if ( false === bwfan_is_autonami_pro_active() ) {
			$link = BWFAN_Common::get_fk_site_links();
			$link = isset( $link['upgrade'] ) ? $link['upgrade'] : '';
			$link = add_query_arg( [
				'utm_medium'  => 'Plugin+Action+Links',
				'utm_content' => 'Upgrade'
			], $link );

			$actions = array_merge( [
				'pro_upgrade' => '<a href="' . $link . '" target="_blank" style="color: #1da867 !important;font-weight:600">' . __( 'Upgrade to Pro', 'all-in-one-seo-pack' ) . '</a>'
			], $actions );
		}

		if ( isset( $actions['edit'] ) ) {
			unset( $actions['edit'] );
		}

		return $actions;
	}

	/**
	 * Autonami admin menu capability
	 *
	 * @return mixed|void
	 */
	public function menu_cap() {
		$capability    = 'manage_options';
		$modified_caps = BWFAN_Common::access_capabilities();
		if ( in_array( 'manage_woocommerce', $modified_caps, true ) ) {
			$capability = 'manage_woocommerce';
		}

		return apply_filters( 'bwfan_menu_access_caps', $capability, $modified_caps );
	}

	public function admin_enqueue_assets() {
		global $post;

		$min = '.min';
		if ( defined( 'BWFAN_IS_DEV' ) && true === BWFAN_IS_DEV ) {
			$min = '';
		}
		$pro_active = false;

		if ( bwfan_is_autonami_pro_active() ) {
			$pro_active = true;
		}

		$v1_active = BWFAN_Common::is_automation_v1_active();

		/**
		 * Adding Woofunnels' font CSS
		 */
		wp_enqueue_style( 'bwfan-woofunnel-fonts', $this->admin_url . '/assets/css/bwfan-admin-font.css', array(), BWFAN_VERSION_DEV );

		/**
		 * Load Builder page assets
		 */
		if ( BWFAN_Common::is_load_admin_assets( 'builder' ) ) {
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		}

		/**
		 * Including izimodal assets
		 */
		if ( true === $v1_active && BWFAN_Common::is_load_admin_assets( 'all' ) ) {
			wp_enqueue_style( 'bwfan-izimodal', $this->admin_url . '/includes/iziModal/iziModal.css', array(), BWFAN_VERSION_DEV );
			wp_enqueue_script( 'bwfan-izimodal', $this->admin_url . '/includes/iziModal/iziModal.js', array(), BWFAN_VERSION_DEV );
		}

		$data = array(
			'ajax_nonce'            => wp_create_nonce( 'bwfan-action-admin' ),
			'plugin_url'            => plugin_dir_url( BWFAN_PLUGIN_FILE ),
			'ajax_url'              => admin_url( 'admin-ajax.php' ),
			'admin_url'             => admin_url(),
			'ajax_chosen'           => wp_create_nonce( 'json-search' ),
			'search_products_nonce' => wp_create_nonce( 'search-products' ),
			'loading_gif_path'      => admin_url() . 'images/wpspin_light.gif',
			'rules_texts'           => array(
				'text_or'         => __( 'OR', 'wp-marketing-automations' ),
				'text_apply_when' => '',
				'remove_text'     => __( 'Remove', 'wp-marketing-automations' ),
			),
			'current_page_id'       => ( isset( $post->ID ) ) ? $post->ID : 0,
		);

		/** WooCommerce ajax endpoint */
		if ( class_exists( 'WC_AJAX' ) ) {
			$data['wc_ajax_url'] = WC_AJAX::get_endpoint( '%%endpoint%%' );
		}

		/** Cart tracking enable checking */
		$bwfan_ab_enable                 = BWFAN_Common::is_cart_abandonment_active();
		$data['wc_cart_tracking_status'] = ( empty( $bwfan_ab_enable ) ) ? 'no' : 'yes';

		/** CRM active */
		$data['crm'] = ( class_exists( 'BWFCRM_Core' ) ) ? 'yes' : 'no';

		/**
		 * Including Autonami assets on all Autonami pages.
		 */
		if ( true === $v1_active && BWFAN_Common::is_load_admin_assets( 'automation' ) ) {

			wp_enqueue_script( 'wp-i18n' );
			wp_enqueue_script( 'wp-util' );

			wp_dequeue_script( 'wpml-select-2' );
			wp_dequeue_script( 'select2' );
			wp_deregister_script( 'select2' );
			wp_enqueue_style( 'bwfan-select2-css', $this->admin_url . '/assets/css/select2.min.css', array(), BWFAN_VERSION_DEV );
			wp_enqueue_style( 'bwfan-sweetalert2-style', $this->admin_url . '/assets/css/sweetalert2.min.css', array(), BWFAN_VERSION_DEV );
			wp_enqueue_style( 'bwfan-toast-style', $this->admin_url . '/assets/css/toast.min.css', array(), BWFAN_VERSION_DEV );
			wp_register_script( 'select2', $this->admin_url . '/assets/js/select2.min.js', array( 'jquery' ), BWFAN_VERSION_DEV, true );
			wp_enqueue_script( 'select2' );
			wp_enqueue_script( 'bwfan-sweetalert2-script', $this->admin_url . '/assets/js/sweetalert2.js', array( 'jquery' ), BWFAN_VERSION_DEV, true );
			wp_enqueue_script( 'bwfan-toast-script', $this->admin_url . '/assets/js/toast.min.js', array( 'jquery' ), BWFAN_VERSION_DEV, true );
			wp_enqueue_editor();
			wp_enqueue_script( 'jquery-ui-datepicker' );

			// jQuery UI theme css file
			wp_register_style( 'jquery-ui', $this->admin_url . '/assets/css/jquery-ui.css', array(), BWFAN_VERSION_DEV );
			wp_enqueue_style( 'jquery-ui' );

			$all_events_merge_tags = BWFAN_Common::get_all_events_merge_tags();
			$all_events_rules      = BWFAN_Common::get_all_events_rules();
			$all_merge_tags        = BWFAN_Core()->merge_tags->get_localize_tags_with_source();

			/** Filter v1 merge tags */
			foreach ( $all_merge_tags as $mergeGroup => $mergeTagList ) {
				if ( empty( $mergeTagList ) ) {
					continue;
				}
				$final_Arr = [];
				foreach ( $mergeTagList as $mergeTagKey => $mergeTagData ) {
					if ( isset( $mergeTagData['support_v1'] ) && $mergeTagData['support_v1'] ) {
						$final_Arr[ $mergeTagKey ] = $mergeTagData;
					}
				}
				if ( ! empty( $final_Arr ) ) {
					$all_merge_tags[ $mergeGroup ] = $final_Arr;
				}
			}

			/**
			 * @todo: Since we are including default merge tags at the bottom of every merge tags then we need to do sorting in JS.
			 */
			$all_events_merge_tags = BWFAN_Common::attach_default_merge_to_events( $all_events_merge_tags, $all_merge_tags );

			$data['events_merge_tags'] = $all_events_merge_tags;
			$data['events_rules']      = $all_events_rules;

			wp_enqueue_style( 'bwfan-admin-app', $this->admin_url . '/assets/css/bwfan-admin-app' . $min . '.css', array(), BWFAN_VERSION_DEV );
			wp_enqueue_style( 'bwfan-admin', $this->admin_url . '/assets/css/bwfan-admin' . $min . '.css', array(), BWFAN_VERSION_DEV );
			wp_enqueue_style( 'bwfan-admin-sub', $this->admin_url . '/assets/css/bwfan-admin-sub' . $min . '.css', array(), BWFAN_VERSION_DEV );
			//}
			/** Common open function */
			wp_enqueue_script( 'bwfan-admin-common', $this->admin_url . '/assets/js/bwfan-admin-common.js', array(), BWFAN_VERSION_DEV, true );

			wp_enqueue_script( 'wc-backbone-modal' );
			wp_enqueue_script( 'bwfan-admin-app', $this->admin_url . '/assets/js/bwfan-admin-ui-rules' . $min . '.js', array(
				'jquery',
				'jquery-ui-datepicker',
				'underscore',
				'backbone',
			), BWFAN_VERSION_DEV, true );

			/** @todo below admin sub css needs to clean */
			wp_enqueue_script( 'bwfan-admin', $this->admin_url . '/assets/js/bwfan-admin' . $min . '.js', array(), BWFAN_VERSION_DEV, true );
			wp_enqueue_script( 'bwfan-admin-ui-actions', $this->admin_url . '/assets/js/bwfan-admin-ui-actions' . $min . '.js', array(), BWFAN_VERSION_DEV, true );

			if ( BWFAN_Common::is_load_admin_assets( 'automation' ) ) {
				wp_enqueue_script( 'wp-block-library' );
				wp_tinymce_inline_scripts();
				wp_enqueue_script( 'jquery-ui-draggable' );
				wp_enqueue_script( 'bwfan-admin-ui', $this->admin_url . '/assets/js/bwfan-admin-ui' . $min . '.js', array( 'bwfan-admin-ui-actions' ), BWFAN_VERSION_DEV, true );
			}
		}

		$data['bitly_success_authentication_message'] = __( 'Successfully Authenticated', 'wp-marketing-automations' );
		$data['setting_page_url']                     = admin_url( 'admin.php?page=autonami-settings' );
		$data['connector_page_url']                   = admin_url( 'admin.php?page=autonami-automations&tab=connector' );
		$data                                         = apply_filters( 'bwfan_admin_localize_data', $data, $this );
		$data['coupon_enabled']                       = ( 'yes' === get_option( 'woocommerce_enable_coupons' ) ) ? 'y' : 'n';
		$data['pro_active']                           = $pro_active;

		if ( BWFAN_Common::is_load_admin_assets( 'all' ) ) {
			$data['bwfan_global_settings']        = BWFAN_Common::get_global_settings();
			$data['bwfan_global_settings_schema'] = BWFAN_Common::get_setting_schema();// sample test data
		}

		if ( bwfan_is_autonami_pro_active() ) {
			$data['pro_version_notice']           = [ 'version' => BWFAN_MIN_PRO_VERSION ];
			$data['pro_version_notice']['status'] = ! ( version_compare( BWFAN_PRO_VERSION, BWFAN_MIN_PRO_VERSION, '>=' ) );
		}
		$data['lk'] = BWFAN_Common::get_lk_data();

		wp_localize_script( 'jquery', 'bwfanParams', $data );

		$automation_id = BWFAN_Core()->automations->get_automation_id();

		if ( is_null( $automation_id ) || empty( $automation_id ) ) {
			return;
		}

		/** Single v1 automation edit page. So continue respective params localization */

		global $automation_global_events_js_data;

		$automation_global_js_object      = array();
		$automation_global_events_js_data = array();

		$automation_global_events_js_data['automation_sync_state'] = 'off';

		$automation_meta = BWFAN_Core()->automations->get_automation_data_meta( $automation_id );

		$all_sources_events  = BWFAN_Load_Sources::get_sources_events_arr();
		$all_triggers        = BWFAN_Core()->sources->get_source_localize_data();
		$all_triggers_events = BWFAN_Core()->sources->get_sources_events_localize_data();
		$all_integrations    = BWFAN_Core()->integration->get_integration_actions_localize_data();
		$all_automations     = BWFAN_Core()->integration->get_integration_localize_data();

		$automation_global_js_object['trigger']                  = array();
		$automation_global_js_object['actions']                  = array();
		$automation_global_js_object['condition']                = array();
		$automation_global_js_object['ui']                       = array();
		$automation_global_js_object['uiData']                   = array();
		$automation_global_events_js_data['all_integrations']    = array();
		$automation_global_events_js_data['all_automations']     = array();
		$automation_global_events_js_data['all_triggers_events'] = array();
		$automation_global_events_js_data['all_triggers']        = array();
		$automation_global_events_js_data['automation_id']       = $automation_id;

		/** Localize all the data which needs to be present on single automation screen. */
		if ( isset( $automation_meta['event'] ) && ! empty( $automation_meta['event'] ) ) {
			$automation_global_js_object['trigger']['source'] = $automation_meta['source'];
			$automation_global_js_object['trigger']['event']  = $automation_meta['event'];
			$automation_global_js_object['trigger']['name']   = __( 'Not Found', 'wp-marketing-automations' );

			$single_event = BWFAN_Core()->sources->get_event( $automation_meta['event'] );
			if ( ! is_null( $single_event ) && true === $single_event->is_time_independent() ) {
				$automation_global_events_js_data['is_time_independent'] = true;
				$automation_global_events_js_data['name']                = $single_event->get_name();
			} else {
				$automation_global_events_js_data['is_time_independent'] = false;
			}
		}
		if ( isset( $automation_meta['event_meta'] ) ) {
			$automation_global_js_object['trigger']['event_meta'] = $automation_meta['event_meta'];
		}
		$automation_global_js_object['actions'] = isset( $automation_meta['actions'] ) ? $automation_meta['actions'] : array();
		if ( isset( $automation_meta['condition'] ) ) {
			$automation_global_js_object['condition'] = $automation_meta['condition'];
		}
		if ( isset( $automation_meta['ui'] ) ) {
			$automation_global_js_object['ui'] = $automation_meta['ui'];
		}
		if ( isset( $automation_meta['uiData'] ) ) {
			$automation_global_js_object['uiData'] = $automation_meta['uiData'];
		}
		if ( isset( $all_integrations ) ) {
			$automation_global_events_js_data['all_integrations'] = $all_integrations;
		}
		if ( isset( $all_automations ) ) {
			$automation_global_events_js_data['all_automations'] = $all_automations;
		}
		if ( isset( $all_triggers_events ) ) {
			$automation_global_events_js_data['all_triggers_events'] = $all_triggers_events;
		}
		if ( isset( $all_triggers ) ) {
			$automation_global_events_js_data['all_triggers'] = $all_triggers;
		}
		if ( isset( $all_sources_events ) ) {
			$automation_global_events_js_data['all_sources_events'] = $all_sources_events;
		}
		if ( isset( $all_merge_tags ) ) {
			$automation_global_events_js_data['all_merge_tags'] = $all_merge_tags;
		}

		$automation_global_events_js_data['int_actions'] = BWFAN_Core()->integration->get_mapped_arr_action_with_integration();
		$automation_global_events_js_data['actions_int'] = BWFAN_Core()->integration->get_mapped_arr_integration_name_with_action_name();
		$automation_global_events_js_data['pro_actions'] = BWFAN_Common::merge_default_actions();
		$automation_global_events_js_data                = apply_filters( 'bwfan_admin_builder_localized_data', $automation_global_events_js_data );

		/** Exclude actions from events */
		$events                  = BWFAN_Core()->sources->get_events();
		$events_included_actions = array();
		$events_excluded_actions = array();

		if ( is_array( $events ) && count( $events ) > 0 ) {
			foreach ( $events as $event ) {
				/**
				 * @var $event_instance BWFAN_Event;
				 */
				$events_included_actions[ $event->get_slug() ] = $event->get_included_actions();
				$events_excluded_actions[ $event->get_slug() ] = $event->get_excluded_actions();
			}
		}

		// all event js data and then set localized unique key
		$all_event_js_data = BWFAN_Core()->admin->get_events_js_data();
		foreach ( $all_event_js_data as $key => $data ) {
			$all_event_js_data[ $key ]['localized_automation_key'] = md5( uniqid( time(), true ) );
		}
		$all_event_js_data = apply_filters( 'bwfan_all_event_js_data', $all_event_js_data, $automation_meta, $automation_id );

		$automation_global_events_js_data = apply_filters( 'bwfan_automation_global_js_data', $automation_global_events_js_data );

		wp_enqueue_media();
		wp_localize_script( 'bwfan-admin', 'bwfan_automation_ui_data_detail', $automation_global_js_object );
		wp_localize_script( 'bwfan-admin', 'bwfan_automation_data', $automation_global_events_js_data );
		wp_localize_script( 'bwfan-admin', 'bwfan_events_js_data', $all_event_js_data );
		wp_localize_script( 'bwfan-admin', 'bwfan_events_included_actions', $events_included_actions );
		wp_localize_script( 'bwfan-admin', 'bwfan_events_excluded_actions', $events_excluded_actions );
		wp_localize_script( 'bwfan-admin', 'bwfan_set_select2ajax_js_data', BWFAN_Core()->admin->get_select2ajax_js_data() );
		wp_localize_script( 'bwfan-admin', 'bwfan_set_actions_js_data', BWFAN_Core()->admin->get_actions_js_data() );
	}

	public function get_events_js_data() {
		return $this->events_js_data;
	}

	/**
	 * Set the event field values for each html field present in that event.
	 *
	 * @param $event_slug
	 * @param $key
	 * @param $data
	 */
	public function set_events_js_data( $event_slug, $key, $data ) {
		if ( ! isset( $this->events_js_data[ $event_slug ] ) ) {
			$this->events_js_data[ $event_slug ]         = [];
			$this->events_js_data[ $event_slug ][ $key ] = $data;

			return;
		}

		if ( ! isset( $this->events_js_data[ $event_slug ][ $key ] ) ) {
			$this->events_js_data[ $event_slug ][ $key ] = $data;

			return;
		}

		$saved_value = is_string( $this->events_js_data[ $event_slug ][ $key ] ) ? json_decode( $this->events_js_data[ $event_slug ][ $key ] ) : array();

		if ( ! empty( $data ) ) {
			$data = is_string( $data ) ? json_decode( $data ) : array();
			foreach ( $data as $key1 => $value1 ) {
				$saved_value[ $key1 ] = $value1;
			}
		}
		$this->events_js_data[ $event_slug ][ $key ] = wp_json_encode( $saved_value );
	}

	public function get_select2ajax_js_data() {
		return $this->select2ajax_js_data;
	}

	/**
	 * @param string $key a search type key to set data against to
	 * @param array $data
	 */
	public function set_select2ajax_js_data( $key, $data ) {
		if ( isset( $this->select2ajax_js_data[ $key ] ) ) {

			$this->select2ajax_js_data[ $key ] = array_replace( $this->select2ajax_js_data[ $key ], $data );
		} else {
			$this->select2ajax_js_data[ $key ] = $data;
		}
	}

	public function get_actions_js_data() {
		return $this->actions_js_data;
	}

	/**
	 * Set action's html fields data.
	 *
	 * @param $integration_slug
	 * @param $key
	 * @param $data
	 */
	public function set_actions_js_data( $integration_slug, $key, $data ) {
		if ( isset( $this->actions_js_data[ $integration_slug ] ) ) {
			if ( isset( $this->actions_js_data[ $integration_slug ][ $key ] ) ) {
				$saved_value = json_decode( $this->actions_js_data[ $integration_slug ][ $key ] );

				if ( ! empty( $data ) ) {
					$data = json_decode( $data );
					foreach ( $data as $key1 => $value1 ) {
						$saved_value[ $key1 ] = $value1;
					}
				}
				$this->actions_js_data[ $integration_slug ][ $key ] = wp_json_encode( $saved_value );
			} else {
				$this->actions_js_data[ $integration_slug ][ $key ] = $data;
			}
		} else {
			$this->actions_js_data[ $integration_slug ][ $key ] = $data;
		}
	}

	public function admin_enqueue_settings_page() {
		$is_connector_page = $this->is_autonami_connector_page();
		if ( $is_connector_page ) {
			wp_enqueue_style( 'wfco-sweetalert2-style' );
			wp_enqueue_style( 'wfco-izimodal' );
			wp_enqueue_style( 'wfco-toast-style' );
			wp_enqueue_script( 'wfco-sweetalert2-script' );
			wp_enqueue_script( 'wfco-izimodal' );
			wp_enqueue_script( 'wfco-toast-script' );
			wp_enqueue_script( 'wc-backbone-modal' );
			wp_enqueue_style( 'wfco-admin' );
			wp_enqueue_script( 'wfco-admin' );
			WFCO_Admin::localize_data();
		}
	}

	public function is_autonami_connector_page() {
		if ( isset( $_GET['page'] ) && 'autonami-automations' === sanitize_text_field( $_GET['page'] ) && false !== strpos( filter_input( INPUT_GET, 'page' ), 'autonami' ) && 'connector' === filter_input( INPUT_GET, 'tab' ) ) { // WordPress.CSRF.NonceVerification.NoNonceVerification
			return true;
		}

		return false;
	}

	public function autonami_page() {
		//phpcs:disable WordPress.Security.NonceVerification
		if ( ! isset( $_GET['page'] ) && 'autonami' !== sanitize_text_field( $_GET['page'] ) ) {
			return;
		}
		if ( class_exists( 'BWFCRM_Dashboard' ) ) {
			$dashboard_page = BWFCRM_Dashboard::get_instance();

			return $dashboard_page->render();
		} else {
			include_once $this->admin_path . '/view/automation-dashboards.php';
		}
		//phpcs:enable WordPress.Security.NonceVerification
	}

	public function js_variables() {
		$time_texts = array(
			'singular' => array(
				'minutes' => __( 'minute', 'wp-marketing-automations' ),
				'hours'   => __( 'hour', 'wp-marketing-automations' ),
				'day'     => __( 'day', 'wp-marketing-automations' ),
			),
			'plural'   => array(
				'minutes' => __( 'minutes', 'wp-marketing-automations' ),
				'hours'   => __( 'hours', 'wp-marketing-automations' ),
				'day'     => __( 'days', 'wp-marketing-automations' ),
			),
		);
		$data       = array(
			'site_url'   => home_url(),
			'texts'      => array(
				'sync_title'                         => __( 'Sync Integration', 'wp-marketing-automations' ),
				'sync_text'                          => __( 'All the data of this Integration will be Synced.', 'wp-marketing-automations' ),
				'sync_wait'                          => __( 'Please Wait...', 'wp-marketing-automations' ),
				'sync_progress'                      => __( 'Sync in progress...', 'wp-marketing-automations' ),
				'sync_success_title'                 => __( 'Integration Synced', 'wp-marketing-automations' ),
				'sync_success_text'                  => __( 'We have detected change in the integration during syncing. Please Re-save your Automations.', 'wp-marketing-automations' ),
				'sync_oops_title'                    => __( 'Oops', 'wp-marketing-automations' ),
				'sync_oops_text'                     => __( 'There was some error. Please try again later.', 'wp-marketing-automations' ),
				'delete_int_title'                   => __( 'There was some error. Please try again later.', 'wp-marketing-automations' ),
				'delete_int_text'                    => __( 'There was some error. Please try again later.', 'wp-marketing-automations' ),
				'delete_int_prompt_title'            => __( 'Delete Connector', 'wp-marketing-automations' ),
				'delete_int_prompt_text'             => __( 'All the Tasks of this Integration will be Deleted.', 'wp-marketing-automations' ),
				'delete_int_wait_title'              => __( 'Please Wait...', 'wp-marketing-automations' ),
				'delete_int_wait_text'               => __( 'Disconnecting the connector ...', 'wp-marketing-automations' ),
				'delete_int_success'                 => __( 'Connector Disconnected', 'wp-marketing-automations' ),
				'task_executed_success'              => __( 'Task Executed', 'wp-marketing-automations' ),
				'task_executed_just'                 => __( 'Just Executed', 'wp-marketing-automations' ),
				'log_deleted_title'                  => __( 'Log Deleted', 'wp-marketing-automations' ),
				'task_deleted_success'               => __( 'Task Deleted', 'wp-marketing-automations' ),
				'change_event_title'                 => __( 'Change in Event', 'wp-marketing-automations' ),
				'change_event_text'                  => __( 'You are about to change the event. You would need to Re-create your automation.', 'wp-marketing-automations' ),
				'delete_automation_title'            => __( 'Delete Automation', 'wp-marketing-automations' ),
				'delete_automation_text'             => __( 'All the Tasks of this automation will be deleted.', 'wp-marketing-automations' ),
				'delete_automation_wait_title'       => __( 'Please Wait...', 'wp-marketing-automations' ),
				'delete_automation_wait_text'        => __( 'Deleting the automation...', 'wp-marketing-automations' ),
				'delete_automation_success'          => __( 'Automation Deleted', 'wp-marketing-automations' ),
				'merge_tag_error_title'              => __( 'Merge Tag Error', 'wp-marketing-automations' ),
				'merge_tag_error_text'               => __( 'Please Check All Your Merge Tags.', 'wp-marketing-automations' ),
				'wrong_action_title'                 => __( 'Incompatible Action', 'wp-marketing-automations' ),
				'wrong_action_text'                  => __( 'Selected Action is not compatible with the selected Event.', 'wp-marketing-automations' ),
				'wrong_event_title'                  => __( 'Incompatible Event', 'wp-marketing-automations' ),
				'wrong_event_text'                   => __( 'Selected Event is not compatible with the Integrations. If you proceed, then you would need to re-create your integrations.', 'wp-marketing-automations' ),
				'no_event'                           => __( 'Please select an event', 'wp-marketing-automations' ),
				'no_trigger'                         => __( 'Please select a trigger', 'wp-marketing-automations' ),
				'no_action'                          => __( 'Please select an action', 'wp-marketing-automations' ),
				'source_change'                      => __( 'Change in Source ! You would need to re-create you automation.', 'wp-marketing-automations' ),
				'activated'                          => __( 'Activated', 'wp-marketing-automations' ),
				'deactivated'                        => __( 'Deactivated', 'wp-marketing-automations' ),
				'sync_process_oops_title'            => __( 'Automation is in sync process', 'wp-marketing-automations' ),
				'task_delete_title'                  => __( 'Delete Task', 'wp-marketing-automations' ),
				'task_delete_text'                   => __( 'Are you sure to delete the task ?', 'wp-marketing-automations' ),
				'delete_batch_process_title'         => __( 'Are you sure to delete the batch process', 'wp-marketing-automations' ),
				'delete_batch_process_text'          => __( 'This batch process will be deleted.', 'wp-marketing-automations' ),
				'delete_batch_process_wait_title'    => __( 'Please Wait...', 'wp-marketing-automations' ),
				'delete_batch_process_wait_text'     => __( 'Deleting the batch process...', 'wp-marketing-automations' ),
				'delete_batch_process_success'       => __( 'Batch Process Deleted', 'wp-marketing-automations' ),
				'terminate_batch_process_title'      => __( 'Are you sure to terminate the batch process', 'wp-marketing-automations' ),
				'terminate_batch_process_text'       => __( 'This batch process will be terminated.', 'wp-marketing-automations' ),
				'terminate_batch_process_wait_title' => __( 'Please Wait...', 'wp-marketing-automations' ),
				'terminate_batch_process_wait_text'  => __( 'Terminating the batch process...', 'wp-marketing-automations' ),
				'terminate_batch_process_success'    => __( 'Batch Process Terminated', 'wp-marketing-automations' ),
			),
			'time_delay' => $time_texts,
		);
		if ( class_exists( 'BWFCRM_Common' ) ) {
			$data['crm'] = '1';
		}

		$wfo = 'window.bwfan=' . wp_json_encode( $data ) . ';';
		echo "<script>$wfo</script>"; //phpcs:ignore WordPress.Security.EscapeOutput

		?>

        <style type="text/css">
            #adminmenu li.bwfan_admin_menu_b_top {
                border-top: 1px dashed #65686b;
                padding-top: 5px;
                margin-top: 5px
            }

            #adminmenu li.bwfan_admin_menu_b_bottom {
                border-bottom: 1px dashed #65686b;
                padding-bottom: 5px;
                margin-bottom: 5px
            }
        </style>

		<?php
	}

	public function admin_footer_text( $footer_text ) {
		if ( false === BWFAN_Common::is_load_admin_assets( 'all' ) ) {
			return $footer_text;
		}
		if ( BWFAN_Common::is_load_admin_assets( 'builder' ) ) {
			return '';
		}
		$link = add_query_arg( array(
			'utm_source'   => 'WordPress',
			'utm_medium'   => 'Footer+Support',
			'utm_campaign' => 'Lite+Plugin',
		), 'https://funnelkit.com/support' );

		return sprintf( __( 'Thanks for using FunnelKit Automations. Need Help? <a href="%s" target="_blank">Contact Support</a>.', 'wp-marketing-automations' ), $link );
	}

	public function update_footer( $footer_text ) {
		if ( BWFAN_Common::is_load_admin_assets( 'builder' ) ) {
			return '';
		}

		return $footer_text;
	}

	public function get_automation_section() {
		if ( isset( $_GET['section'] ) && ! empty( sanitize_text_field( $_GET['section'] ) ) && isset( $_GET['page'] ) && 'autonami-automations' === sanitize_text_field( $_GET['page'] ) ) { // WordPress.CSRF.NonceVerification.NoNonceVerification
			return sanitize_text_field( $_GET['section'] ); // WordPress.CSRF.NonceVerification.NoNonceVerification
		}

		return 'automation';
	}

	public function maybe_set_automation_id() {
		if ( $this->is_autonami_page() && isset( $_GET['edit'] ) ) { // WordPress.CSRF.NonceVerification.NoNonceVerification
			BWFAN_Core()->automations->set_automation_id( sanitize_text_field( $_GET['edit'] ) ); // WordPress.CSRF.NonceVerification.NoNonceVerification
			BWFAN_Core()->automations->set_automation_details();

			do_action( 'bwfan_automation_data_set_automation' ); // WordPress.CSRF.NonceVerification.NoNonceVerification
		}
	}

	public function is_autonami_page() {
		$page = filter_input( INPUT_GET, 'page' ); // WordPress.CSRF.NonceVerification.NoNonceVerification
		if ( is_null( $page ) ) {
			return false;
		}

		return ( 'autonami-automations' === $page || false !== strpos( $page, 'autonami' ) );
	}

	public function change_autonami_menu_icon() {
		?>
        <style>
            .wp-admin #adminmenu .toplevel_page_autonami .wp-menu-image:before {
                content: none;
            }

            .wp-admin #adminmenu .toplevel_page_autonami .wp-not-current-submenu .wp-menu-image {
                background-image: url("<?php echo esc_url( plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/img/bwf-icon-grey.svg' ); ?>") !important;
            }

            .wp-admin #adminmenu .toplevel_page_autonami .wp-has-current-submenu .wp-menu-image {
                background-image: url("<?php echo esc_url( plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/img/bwf-icon-white.svg' ); ?>") !important;
            }

            .wp-admin #adminmenu .toplevel_page_autonami .wp-menu-image {
                background-repeat: no-repeat;
                position: relative;
                top: 5px;
                background-position: 50% 25%;
                background-size: 60%;
            }
        </style>
		<?php
	}

	/**
	 * Hooked over 'plugin_action_links_{PLUGIN_BASENAME}' WordPress hook to add deactivate popup support
	 *
	 * @param array $links array of existing links
	 *
	 * @return array modified array
	 */
	public function plugin_actions( $links ) {
		if ( isset( $links['deactivate'] ) ) {
			$links['deactivate'] .= '<i class="woofunnels-slug" data-slug="' . BWFAN_PLUGIN_BASENAME . '"></i>';
		}

		return $links;
	}

	public function tooltip( $text ) {
		?>
        <span class="bwfan-help"><i class="icon"></i><div class="helpText"><?php esc_html_e( $text ); ?></div></span>
		<?php
	}

	/**
	 * Remove all the notices in our dashboard pages as they might break the design.
	 */
	public function maybe_remove_all_notices_on_page() {
		if ( isset( $_GET['page'] ) && 'autonami' === sanitize_text_field( $_GET['page'] ) && isset( $_GET['section'] ) ) { // WordPress.CSRF.NonceVerification.NoNonceVerification
			remove_all_actions( 'admin_notices' );
		}
	}

	public function maybe_set_as_ct_worker() {
		if ( BWFAN_Common::is_automation_v1_active() && ! BWFAN_Common::bwf_has_action_scheduled( 'bwfan_run_queue' ) ) {
			bwf_schedule_recurring_action( time(), MINUTE_IN_SECONDS, 'bwfan_run_queue' );
		}
		if ( ! BWFAN_Common::bwf_has_action_scheduled( 'bwfan_run_queue_v2' ) ) {
			bwf_schedule_recurring_action( time(), MINUTE_IN_SECONDS, 'bwfan_run_queue_v2' );
		}

		if ( ! BWFAN_Common::bwf_has_action_scheduled( 'bwfan_delete_logs' ) ) {
			$store_time = BWFAN_Common::get_store_time( 4 );
			bwf_schedule_recurring_action( $store_time, DAY_IN_SECONDS, 'bwfan_delete_logs' );
		}
	}

	public function schedule_abandoned_cart_cron() {
		/** If no WC, return */
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		/** Schedule abandoned cart tracking action */
		if ( ! BWFAN_Common::bwf_has_action_scheduled( 'bwfan_check_abandoned_carts' ) ) {
			bwf_schedule_recurring_action( time(), MINUTE_IN_SECONDS, 'bwfan_check_abandoned_carts' ); // check for abandoned carts for every minute
		}

		/** Schedule delete expired coupons and abandoned lost cart actions */
		$delete_expired_coupons = BWFAN_Common::bwf_has_action_scheduled( 'bwfan_delete_expired_autonami_coupons' );
		$mark_lost_carts        = BWFAN_Common::bwf_has_action_scheduled( 'bwfan_mark_abandoned_lost_cart' );

		$midnight_time = BWFAN_Common::get_midnight_store_time();
		if ( ! $delete_expired_coupons ) {
			bwf_schedule_recurring_action( $midnight_time, DAY_IN_SECONDS, 'bwfan_delete_expired_autonami_coupons' ); // Run once in a day
		}
		if ( ! $mark_lost_carts ) {
			bwf_schedule_recurring_action( $midnight_time, DAY_IN_SECONDS, 'bwfan_mark_abandoned_lost_cart' ); // Run once in a day
		}

		if ( true === apply_filters( 'bwfan_ab_delete_inactive_carts', false ) && ! BWFAN_Common::bwf_has_action_scheduled( 'bwfan_delete_old_abandoned_carts' ) ) {
			bwf_schedule_recurring_action( $midnight_time, DAY_IN_SECONDS, 'bwfan_delete_old_abandoned_carts' ); // Run once in a day
		}
	}

	public function maybe_handle_optin_choice() {
		if ( isset( $_GET['bwfan-optin-choice'] ) && isset( $_GET['_bwfan_optin_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_GET['_bwfan_optin_nonce'], 'bwfan_optin_nonce' ) ) {
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'woofunnels' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'Cheating huh?', 'woofunnels' ) );
			}

			$optin_choice = sanitize_text_field( $_GET['bwfan-optin-choice'] );
			if ( $optin_choice === 'yes' ) {
				$this->allow_optin();

			} else {
				$this->block_optin();
			}

			do_action( 'bwfan_after_optin_choice', $optin_choice );
			wp_redirect( admin_url( 'admin.php?page=autonami' ) );
			exit;
		}
	}

	public function allow_optin() {
		update_option( 'bwfan_is_opted', 'yes', true );

		// try to push data for once
		$data = WooFunnels_optIn_Manager::collect_data();

		// posting data to api
		WooFunnels_API::post_tracking_data( $data );
	}

	public function block_optin() {
		update_option( 'bwfan_is_opted', 'no', true );
	}

	public function maybe_redirect_to_automation() {
		$page = filter_input( INPUT_GET, 'page' );
		$id   = filter_input( INPUT_GET, 'edit' );
		if ( empty( $page ) || 'autonami-automations' !== strval( $page ) || ! empty( $id ) ) {
			return;
		}
		wp_redirect( admin_url( 'admin.php?page=autonami&path=/automations' ) );
		exit;
	}

	public function get_automation_id() {
		if ( isset( $_GET['edit'] ) && ! empty( sanitize_text_field( $_GET['edit'] ) ) && isset( $_GET['page'] ) && 'autonami-automations' === sanitize_text_field( $_GET['page'] ) ) { // WordPress.CSRF.NonceVerification.NoNonceVerification
			return sanitize_text_field( $_GET['edit'] ); // WordPress.CSRF.NonceVerification.NoNonceVerification
		}

		return false;
	}

	/**
	 *  autonami page
	 */
	public function autonami_automations_page() {

		$external_template = apply_filters( 'bwfan_load_external_autonami_page_template', '' );
		if ( ! empty( $external_template ) ) {
			if ( is_array( $external_template ) ) {
				foreach ( $external_template as $template ) {
					require_once $template;
				}
			} else {
				require_once $external_template;
			}

			return;
		}

		if ( isset( $_GET['edit'] ) ) {
			if ( isset( $_GET['section'] ) ) {
				if ( 'preview_email' === sanitize_text_field( $_GET['section'] ) ) {
					include_once $this->admin_path . '/view/preview_email.php';
					exit;
				}
			}

			include_once $this->admin_path . '/view/automation-builder-view.php';

			return;
		}

		if ( isset( $_GET['tab'] ) ) {
			$tab = sanitize_text_field( $_GET['tab'] );
			switch ( $tab ) {
				case 'tasks':
					wp_safe_redirect( admin_url( 'admin.php?page=autonami&path=/automations/task-history' ) );
					exit;
				case 'connector':
					include_once $this->admin_path . '/view/connector-admin.php';
					break;
				case 'logs':
					wp_safe_redirect( admin_url( 'admin.php?page=autonami&path=/automations/task-history' ) );
					break;
				default:
					break;
			}

			return;
		}
	}

	/**
	 * redirect to automation list page
	 */
	public function redirect_autonami_automations_page() {
		$tab  = htmlspecialchars( filter_input( INPUT_GET, 'tab', FILTER_UNSAFE_RAW ) );
		$edit = htmlspecialchars( filter_input( INPUT_GET, 'edit', FILTER_UNSAFE_RAW ) );
		if ( ! empty( $tab ) || ! empty( $edit ) ) {
			return;
		}
		wp_safe_redirect( admin_url( 'admin.php?page=autonami&path=/automations-v1' ) );
		exit;
	}

	/**
	 *  making tools tab data
	 */
	public function make_tools_tabs_data() {

		$tab_arr = array(
			'unsubscribers' => array(
				'name' => __( 'Unsubscribers', 'wp-marketing-automations' ),
				'href' => admin_url( 'admin.php?page=autonami-settings&tab=unsubscribers' ),
			),
			'api_endpoints' => array(
				'name' => __( 'Endpoints', 'wp-marketing-automations' ),
				'href' => admin_url( 'admin.php?page=autonami-settings&tab=api_endpoints' ),
			),
			'actions'       => array(
				'name' => __( 'Actions', 'wp-marketing-automations' ),
				'href' => admin_url( 'admin.php?page=autonami-settings&tab=actions' ),
			),
		);

		if ( 'autonami-settings' === filter_input( INPUT_GET, 'page' ) ) {
			$tab = filter_input( INPUT_GET, 'tab' );
			switch ( $tab ) {
				case 'api_endpoints':
				case 'actions':
					$tab_arr[ $tab ]['active'] = true;
					break;
				default:
					$tab_arr['unsubscribers']['active'] = true;
			}
		}

		$tab_arr = apply_filters( 'bwfan_tools_tab_array', $tab_arr );
		$this->make_tools_tab_ui( $tab_arr );
	}

	/** making tools tab html
	 *
	 * @param $arr
	 * @param string $prefix
	 */
	public function make_tools_tab_ui( $arr, $prefix = 'bwfan' ) {
		if ( ! is_array( $arr ) || count( $arr ) === 0 ) {
			return;
		}

		ob_start();
		echo '<nav class="nav-tab-wrapper woo-nav-tab-wrapper">';
		foreach ( $arr as $key => $val ) {
			if ( ! isset( $val['name'] ) || empty( $val['name'] ) ) {
				continue;
			}
			$href  = ( isset( $val['href'] ) && ! empty( $val['href'] ) ) ? $val['href'] : 'javascript:void(0)';
			$class = array( 'nav-tab', $prefix . '_tab_' . $key );
			if ( isset( $val['active'] ) && true === $val['active'] ) {
				$class[] = 'nav-tab-active';
			}
			if ( isset( $val['class'] ) && is_array( $val['class'] ) && count( $val['class'] ) > 0 ) {
				$class = array_merge( $class, $val['class'] );
			}
			$attr = array();
			if ( isset( $val['attr'] ) && is_array( $val['attr'] ) && count( $val['attr'] ) > 0 ) {
				$attr = $val['attr'];
				array_walk( $attr, function ( &$val, $key ) {
					if ( ! empty( $key ) && ! empty( $val ) ) {
						$val = ' ' . $key . '=' . $val;
					}
				} );
			}

			?>
            <a href="<?php echo $href; //phpcs:ignore WordPress.Security.EscapeOutput ?>"
               class="<?php esc_attr_e( implode( ' ', $class ) ); ?>"<?php esc_attr_e( implode( ' ', $attr ) ); ?>><?php esc_html_e( $val['name'] ); ?></a>
			<?php
		}
		echo '</nav>';
		echo ob_get_clean(); //phpcs:ignore WordPress.Security.EscapeOutput
	}

	/** handling autonami connector page  */
	public function redirect_autonami_connector_page() {
		/** handling connectors oAuth 2.0 in autonami */
		if ( isset( $_GET['page'] ) && 'autonami' === $_GET['page'] && isset( $_GET['tab'] ) && 'connector' === $_GET['tab'] ) {
			$_GET['path'] = '/connectors';
			unset( $_GET['tab'] );
			$build_query = http_build_query( $_GET );

			wp_redirect( admin_url( 'admin.php?' . $build_query ) );
			exit;
		}

		/** handling batch process page in autonami */
		if ( isset( $_GET['page'] ) && 'autonami' === $_GET['page'] && isset( $_GET['tab'] ) && 'batch_process' === $_GET['tab'] ) {
			$_GET['page'] = 'autonami-automations';
			$build_query  = http_build_query( $_GET );

			wp_redirect( admin_url( 'admin.php?' . $build_query ) );
			exit;
		}

		/** handling autonami builder page */
		if ( isset( $_GET['page'] ) && 'autonami' === $_GET['page'] && isset( $_GET['section'] ) && 'automation' === $_GET['section'] && filter_input( INPUT_GET, 'edit' ) > 0 ) {
			$_GET['page'] = 'autonami-automations';
			unset( $_GET['section'] );
			$build_query = http_build_query( $_GET );

			wp_redirect( admin_url( 'admin.php?' . $build_query ) );
			exit;
		}

		return;
	}

	public function automation_modify_actions_groups( $arr ) {
		/** Adding custom WP send email */
		if ( ! isset( $arr['messaging'] ) ) {
			$arr['messaging'] = array(
				'label'    => __( 'Messaging', 'wp-marketing-automations' ),
				'priority' => 5,
				'subgroup' => array( 'wp' => 'Email' ),
			);
		} else {
			$arr['messaging']['subgroup'] = array_merge( array( 'wp' => __( 'Email', 'wp-marketing-automations' ) ), $arr['messaging']['subgroup'] );
		}
		if ( isset( $arr['wp']['subgroup']['wp'] ) ) {
			unset( $arr['wp']['subgroup']['wp'] );
		}

		/** Modify WP_ADV group */
		if ( isset( $arr['wp']['subgroup']['wp_adv'] ) ) {
			unset( $arr['wp']['subgroup']['wp_adv'] );

			$arr['wp']['subgroup']['wp_adv_1'] = __( 'Users', 'wp-marketing-automations' );
			$arr['wp']['subgroup']['wp_adv_2'] = __( 'Advanced', 'wp-marketing-automations' );
		}

		/** Adding Autonami new groups */
		if ( isset( $arr['autonami'] ) ) {
			$arr['autonami']['subgroup'] = array(
				'autonami_1' => __( 'Contacts', 'wp-marketing-automations' ),
				'autonami_2' => __( 'Automation', 'wp-marketing-automations' ),
			);
		}

		/** Adding HTTP Post in Send Data */
		if ( ! isset( $arr['send_data'] ) ) {
			$arr['send_data'] = array(
				'label'    => __( 'Send Data', 'wp-marketing-automations' ),
				'priority' => 85,
				'subgroup' => array(),
			);
		}
		$arr['send_data']['subgroup'] = array_merge( array( 'http_post' => __( 'Post', 'wp-marketing-automations' ) ), $arr['send_data']['subgroup'] );

		return $arr;
	}

	public function automation_modify_integrations( $arr ) {
		/** Adding WP ADV integration actions */
		if ( isset( $arr['wp_adv'] ) ) {
			$arr = $this->copy_integration_action_arr( $arr, 'wp_createuser', 'wp_adv', 'wp_adv_1' );
			$arr = $this->copy_integration_action_arr( $arr, 'wp_update_user_meta', 'wp_adv', 'wp_adv_1' );
			$arr = $this->copy_integration_action_arr( $arr, 'wp_update_user_role', 'wp_adv', 'wp_adv_1' );

			$arr = $this->copy_integration_action_arr( $arr, 'wp_custom_callback', 'wp_adv', 'wp_adv_2' );
			$arr = $this->copy_integration_action_arr( $arr, 'wp_debug', 'wp_adv', 'wp_adv_2' );

			$arr = $this->copy_integration_action_arr( $arr, 'wp_http_post', 'wp_adv', 'http_post' );
		}

		/** Adding Autonami new integration actions */
		if ( isset( $arr['autonami'] ) ) {
			$arr = $this->copy_integration_action_arr( $arr, 'crm_create_contact', 'autonami', 'autonami_1' );
			$arr = $this->copy_integration_action_arr( $arr, 'crm_update_customfields', 'autonami', 'autonami_1' );
			$arr = $this->copy_integration_action_arr( $arr, 'crm_add_tag', 'autonami', 'autonami_1' );
			$arr = $this->copy_integration_action_arr( $arr, 'crm_add_contact_note', 'autonami', 'autonami_1' );
			$arr = $this->copy_integration_action_arr( $arr, 'crm_rmv_tag', 'autonami', 'autonami_1' );
			$arr = $this->copy_integration_action_arr( $arr, 'crm_add_to_list', 'autonami', 'autonami_1' );
			$arr = $this->copy_integration_action_arr( $arr, 'crm_rmv_from_list', 'autonami', 'autonami_1' );
			$arr = $this->copy_integration_action_arr( $arr, 'crm_change_contact_status', 'autonami', 'autonami_1' );
			$arr = $this->copy_integration_action_arr( $arr, 'automation_end', 'autonami', 'autonami_2' );
		}

		return $arr;
	}

	public function copy_integration_action_arr( $arr, $key, $old_base, $new_base ) {
		if ( ! isset( $arr[ $new_base ] ) ) {
			$arr[ $new_base ] = array();
		}
		if ( isset( $arr[ $old_base ] ) && isset( $arr[ $old_base ][ $key ] ) ) {
			$arr[ $new_base ][ $key ]                     = $arr[ $old_base ][ $key ];
			$arr[ $new_base ][ $key ]['real_integration'] = $old_base;
		}

		return $arr;
	}

	function bwfan_add_contact_profile_link( $user ) {
		if ( $user && $user instanceof WP_User ) {
			$editingUserVars = null;
			$urlBase         = admin_url( 'admin.php?page=autonami' );
			$crmProfile      = bwf_get_contact( $user->ID, $user->user_email );
			if ( $crmProfile && $crmProfile instanceof WooFunnels_Contact && $crmProfile->get_id() > 0 ) {
				$crmProfileUrl   = $urlBase . '&path=/contact/' . $crmProfile->id;
				$editingUserVars = array(
					'user_id'            => $user->ID,
					'bwfcrm_profile_id'  => $crmProfile->get_id(),
					'bwfcrm_profile_url' => $crmProfileUrl,
				);
			}
			$bwfan_bar_vars['rest']            = '';
			$bwfan_bar_vars['links']           = '';
			$bwfan_bar_vars['subscriber_base'] = $urlBase . '&path=/contacts';
			$bwfan_bar_vars['edit_user_vars']  = $editingUserVars;

			?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    window.bwfan_bar_vars = '<?php echo wp_json_encode( $bwfan_bar_vars ); ?>';
                    if (window.bwfan_bar_vars.hasOwnProperty('edit_user_vars') !== null) {
                        var edit_user_vars = JSON.parse(window.bwfan_bar_vars).edit_user_vars;
                        if (edit_user_vars !== null && edit_user_vars.hasOwnProperty('bwfcrm_profile_url')) {
                            window.jQuery('<a style="background: #1DAAFC;color: white;border: none;" class="page-title-action" href="' + edit_user_vars.bwfcrm_profile_url + '">View Contact Profile</a>').insertBefore("#profile-page > .wp-header-end")
                        }
                    }
                });
            </script>
			<?php
		}
	}

	/**
	 * Adds 'Profit' column header to 'Orders' page immediately after 'Total' column.
	 *
	 * @param $columns
	 *
	 * @return array|mixed
	 */
	function bwfan_add_order_contact_column_header( $columns ) {
		if ( defined( 'BWFAN_Orders_Disable_CRM_Column' ) && 1 === BWFAN_Orders_Disable_CRM_Column ) {
			return $columns;
		}

		$new_columns = array();
		foreach ( $columns as $column_name => $column_info ) {
			$new_columns[ $column_name ] = $column_info;
			if ( 'order_status' === $column_name ) {
				$new_columns['bwfan_order_contact'] = __( 'Total Spent', 'wp-marketing-automations' );
			}
		}

		return $new_columns;
	}

	/**
	 * Adds 'Contact' column content to 'Orders' page immediately after 'Status' column.
	 *
	 * @param string[] $column name of column being displayed
	 * @param int $order_id current order to traverse
	 */
	public function bwfan_add_order_contact_column_content( $column, $order_id ) {
		if ( 'bwfan_order_contact' !== $column ) {
			return;
		}

		if ( defined( 'BWFAN_Orders_Disable_CRM_Column' ) && 1 === BWFAN_Orders_Disable_CRM_Column ) {
			return;
		}

		$order = wc_get_order( $order_id );

		$cid = $order->get_meta( '_woofunnel_cid' );
		if ( empty( $cid ) ) {
			echo '-';

			return;
		}

		$default_currency_symbol = get_option( 'woocommerce_currency' );

		$WooFunnels_Cache_obj = WooFunnels_Cache::get_instance();
		$is_cache             = $WooFunnels_Cache_obj->get_cache( 'bwf_cid_' . $cid . '_wc_columns', 'bwfan_order_contact_data' );
		$result               = [];

		if ( false !== $is_cache ) {
			$result = $is_cache;
		} else {
			global $wpdb;
			$result = $wpdb->get_row( $wpdb->prepare( "SELECT `total_order_value`, `total_order_count` FROM `{$wpdb->prefix}bwf_wc_customers` WHERE `cid` = %d LIMIT 0, 1", $cid ), ARRAY_A );

			$WooFunnels_Cache_obj->set_cache( 'bwf_cid_' . $cid . '_wc_columns', $result, 'bwfan_order_contact_data' );
		}

		if ( empty( $result ) || empty( $result['total_order_count'] ) ) {
			echo '-';

			return;
		}
		?>
        <div style="display: flex; align-items: center">
			<span>
				<?php
				echo wc_price( $result['total_order_value'], array( 'currency' => $default_currency_symbol ) );
				echo ' | ';
				printf( _n( '%s order', '%s orders', intval( $result['total_order_count'] ), 'wp-marketing-automations' ), intval( $result['total_order_count'] ) );
				?>
			</span>
        </div>
		<?php
	}

	/**
	 * Attach meta box of FKA Contact on Single order screen
	 *
	 * @param $post_type
	 * @param $post
	 *
	 * @return void
	 */
	public function bwf_add_single_order_meta_box( $post_type, $post ) {
		if ( ! bwfan_is_autonami_pro_active() ) {
			return;
		}
		if ( 'shop_order' === $post_type || ( $post instanceof WC_Order && 'shop_order' === $post->get_type() ) ) {
			$order = $post instanceof WC_Order ? $post : wc_get_order( $post->ID );
			if ( ! $order instanceof WC_Order ) {
				return;
			}
			$order_contact_id = $order->get_meta( '_woofunnel_cid' );
			if ( $order_contact_id ) {
				$data = array(
					'cid' => $order_contact_id,
				);
				add_meta_box( 'bwfan_contact_info_box', __( 'Contact Profile', 'wp-marketing-automations' ), array(
					$this,
					'bwf_order_meta_box_data'
				), get_current_screen(), 'side', 'high', $data );
			}
		}
	}

	public function bwf_order_meta_box_data( $post, $data ) {
		$args = $data['args'];
		if ( ! isset( $args['cid'] ) || empty( $args['cid'] ) ) {
			echo 'No Contact Mapped';

			return;
		}

		$contact_id = absint( $args['cid'] );
		$contact    = new BWFCRM_Contact( $contact_id );
		if ( false === $contact->is_contact_exists() ) {
			echo 'No Contact Mapped';

			return;
		}
		$user_mail    = $contact->contact->email;
		$user_fname   = $contact->contact->get_f_name();
		$user_lname   = $contact->contact->get_l_name();
		$contact_name = ucfirst( $user_fname ) . ' ' . ucfirst( $user_lname );
		$admin_url    = admin_url( 'admin.php?page=autonami&path=/contact/' . $contact_id );
		$avatar_url   = 'https://www.gravatar.com/avatar/0?s=80&d=blank';
		if ( $user_mail ) {
			$avatar_url = 'https://www.gravatar.com/avatar/' . md5( $user_mail ) . '?s=80&&d=blank';
		}

		$status      = $contact->get_marketing_status();
		$joined_date = $contact->contact->get_creation_date();
		$joined_date = gmdate( get_option( 'date_format' ), strtotime( $joined_date ) );
		?>
        <style type="text/css">
            .bwf-order-edit-wrap {
                display: block;
                text-align: center;
            }

            .bwf-order-edit-wrap a {
                text-decoration: none;
            }

            .bwf-contact-profile {
                padding: 0;
                margin: 10px auto;
                position: relative;
                width: 80px;
            }

            img.bwf-gravatar {
                position: absolute;
                left: 0;
                z-index: 9;
                border-radius: 50%;
            }

            .bwf-c-name-initials {
                border-radius: 50%;
                height: 80px;
                width: 80px;
                display: flex;
                justify-content: center;
                align-items: center;
                text-transform: uppercase;
                color: #fff;
                background: #1daafc;
                font-size: 14px;
                font-weight: 700;
                letter-spacing: 0.06em;
                flex-shrink: 0;
                position: relative;
                text-decoration: none;
            }

            .bwf-contact-wc-detail {
                display: inline-flex;
                flex-direction: column;
            }

            .bwf-contact-wc-detail a {
                text-decoration: none;
            }

            .bwf-contact-wc-status {
                display: inline-block;
                width: auto;
                color: #18b2ff;
                background: #f6fcff;
                border: 1px solid #c3c4c7;
                font-size: 0.6875rem;
                line-height: 1.25rem;
                border-radius: 15px;
                padding: 3px 12px;
                margin: 8px 8px 0 0;
                user-select: none;
                -webkit-user-select: none;
                -moz-user-select: none;
            }

            .bwf-pro-data {
                margin: 10px 0;
            }

            .bwf-pro-data > div > span:nth-child(1) {
                font-weight: 500;
                min-width: 80px;
                display: inline-block;
            }

            .bwf-pro-data > div {
                margin-bottom: 8px;
            }

            .bwf-pro-data .bwf-order-data-gap {
                display: block;
                clear: both;
                height: 1px;
                border-bottom: 1px solid #eee;
                margin-bottom: 10px;
            }

            .bwf-pro-tags-data {
                list-style: none;
                display: flex;
            }

            .bwf-pro-tags-data li {
                list-style: none;
                width: fit-content;
                padding: 0 10px;
                border-radius: 10px;
                display: inline-block;
                color: #8091a7;
                background: #f1f3f5;
                border: 1px solid #f1f3f5;
                font-size: 0.6875rem;
                line-height: 1.25rem;
                margin: 0 2px 5px 0;
            }

            .bwf-pro-tags-data span {
                font-weight: 500;
                display: inline-block;
                margin-bottom: 5px;
            }
        </style>
        <div class="bwf-order-edit-wrap">
            <div class="bwf-contact-profile">
                <a href='<?php echo esc_url( $admin_url ); ?>' target="_blank">
                    <img alt="" class="bwf-gravatar" src="<?php echo esc_url( $avatar_url ); ?>" width="80" height="80"/>
                    <div class="bwf-c-name-initials">
							<span>
								<?php echo substr( $user_fname, 0, 1 ) . substr( $user_lname, 0, 1 ); ?>
							</span>
                    </div>
                </a>
            </div>
            <div class="bwf-contact-wc-detail">
                <b>
					<?php echo esc_html__( empty( $contact_name ) ? $user_mail : $contact_name ); ?>
                </b>
                <span>Joined On <?php echo $joined_date; ?></span>
                <span class="bwf-contact-wc-status">
                    <a href='<?php echo esc_url( $admin_url ); ?>' target="_blank">
                        View Contact
                    </a>
                </span>
            </div>
        </div>
		<?php
		do_action( 'bwfan_crm_order_autonami_metabox', $contact, $status );
	}

	/**
	 * Add link to autonami contact
	 *
	 * @param $actions
	 * @param $user
	 *
	 * @return mixed
	 */
	public function bwf_user_list_add_contact_link( $actions, $user ) {
		$contact_data = BWFAN_Model_Customers::bwf_get_customer_data_by_id( $user->ID, false );
		if ( ! empty( $contact_data ) ) {
			$admin_url           = admin_url( 'admin.php?page=autonami&path=/contact/' . $contact_data->cid );
			$actions['autonami'] = '<a href="' . $admin_url . '">View Contact</a>';
		}

		return $actions;
	}
}

if ( class_exists( 'BWFAN_Core' ) ) {
	BWFAN_Core::register( 'admin', 'BWFAN_Admin' );
}
