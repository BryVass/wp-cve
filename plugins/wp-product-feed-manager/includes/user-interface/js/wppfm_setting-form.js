/*global wppfm_setting_form_vars */
function wppfm_auto_feed_fix_changed() {
	wppfm_auto_feed_fix_mode(
		jQuery( '#wppfm-auto-feed-fix-mode' ).is( ':checked' ),
		function( response ) {
			console.log( 'Auto feed fix setting changed to ' + response );
		}
	);
}

function wppfm_background_processing_mode_changed() {
	wppfm_background_processing_mode(
		jQuery( '#wppfm-background-processing-mode' ).is( ':checked' ),
		function( response ) {
			console.log( 'Background processing setting changed to ' + response );
		}
	);
}

function wppfm_feed_logger_status_changed() {
	wppfm_feed_logger_status(
		jQuery( '#wppfm-process-logging-mode' ).is( ':checked' ),
		function( response ) {
			console.log( 'Feed process logger status changed to ' + response );
		}
	);
}

function wppfm_show_product_identifiers_changed() {
	wppfm_show_pi_status(
		jQuery( '#wppfm-product-identifiers' ).is( ':checked' ),
		function( response ) {
			console.log( 'Show Product Identifiers setting changed to ' + response );
		}
	);
}

function wppfm_wpml_use_full_resolution_urls_changed() {
	wppfm_wpml_use_full_url_resolution(
		jQuery( '#wppfm-wpml-use-full-resolution-urls' ).is( ':checked' ),
		function( response ) {
			console.log( 'WPML Use full resolution URLs setting changed to ' + response );
		}
	);
}

function wppfm_third_party_attributes_changed() {
	var thirdPartyAttributes = wppfm_sanitizeInputString( jQuery( '#wppfm-third-party-attr-keys' ).val() );
	wppfm_change_third_party_attribute_keywords(
		thirdPartyAttributes,
		function( response ) {
			console.log( 'Third party attributes changed to ' + response );
		}
	);
}

function wppfm_notice_mailaddress_changed() {
	var newNoticeEmail = wppfm_sanitizeEmail( jQuery( '#wppfm-notice-mailaddress' ).val() );

	console.log(newNoticeEmail);
	if ( newNoticeEmail ) {
		wppfm_change_notice_mailaddress(
				newNoticeEmail,
				function(response) {
					console.log('Notice recipient setting changed to ' + response);
				}
		);
	} else {
		alert( wppfm_setting_form_vars.invalid_email_address );
	}
}

function wppfm_clear_feed_process() {
	wppfm_showWorkingSpinner();
	wppfm_clear_feed_process_data(
		function( response ) {
			console.log( 'Clear feed process activated' );
			wppfm_hideWorkingSpinner();
		}
	);
}

function wppfm_reinitiate() {
	wppfm_showWorkingSpinner();
	wppfm_reinitiate_plugin(
		function( response ) {
			console.log( 'Re-initialization initiated ' + response );
			wppfm_hideWorkingSpinner();
		}
	);
}

function wppfm_backup() {
	var newFileName = wppfm_sanitizeInputString( jQuery( '#wppfm-backup-file-name' ).val() );

	if ( newFileName !== '' ) {
		jQuery( '#wppfm_backup-wrapper' ).hide();

		wppfm_initiateBackup(
				newFileName,
				function( response ) {
					wppfm_resetBackupsList();

					if ( response !== '1' ) {
						wppfm_showErrorMessage( response );
					} else {
						wppfm_showSuccessMessage( 'New backup file "' + newFileName + '" stored.' );
					}
				}
		);
	} else {
		alert( wppfm_setting_form_vars.first_enter_file_name );
	}
}

function wppfm_deleteBackupFile( fileName ) {
	var userInput = confirm( wppfm_setting_form_vars.confirm_file_deletion.replace( '%backup_file_name%', fileName ) );

	if ( userInput === true ) {
		wppfm_showWorkingSpinner();

		wppfm_deleteBackup(
			fileName,
			function( response ) {

				if ( '1' === response ) {
					wppfm_showSuccessMessage( wppfm_setting_form_vars.file_deleted.replace('%backup_file_name%', fileName));
					console.log('Backup file deleted ' + response);
				} else {
					wppfm_showErrorMessage(response);
				}
				wppfm_resetBackupsList();
				wppfm_hideWorkingSpinner();
			}
		);
	}
}

function wppfm_restoreBackupFile( fileName ) {
	var userInput = confirm( wppfm_setting_form_vars.confirm_file_restoring.replace( '%backup_file_name%', fileName ) );

	if ( userInput === true ) {
		wppfm_showWorkingSpinner();

		wppfm_restoreBackup(
			fileName,
			function( response ) {

				if ( '1' === response ) {
					wppfm_showSuccessMessage( wppfm_setting_form_vars.file_restored.replace( '%backup_file_name%', fileName ) );
					console.log( 'Backup file restored ' + response );
				} else {
					wppfm_showErrorMessage( response );
				}
				wppfm_resetOptionSettings();
				wppfm_hideWorkingSpinner();
			}
		);
	}
}

function wppfm_duplicateBackupFile( fileName ) {

	wppfm_showWorkingSpinner();

	wppfm_duplicateBackup(
		fileName,
		function( response ) {

			if ( '1' === response ) {
				wppfm_showSuccessMessage( wppfm_setting_form_vars.file_duplicated.replace( '%backup_file_name%', fileName ) );
				console.log( 'Backup file duplicated' + response );
			} else {
				wppfm_showErrorMessage( response );
			}
			wppfm_resetBackupsList();
			wppfm_hideWorkingSpinner();
		}
	);
}
