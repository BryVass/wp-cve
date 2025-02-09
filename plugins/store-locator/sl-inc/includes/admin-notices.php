<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//2/23/18 9:12:06p - last saved

# v3.0 -- What's New
$sl_notice_id = 'sl_a_s_v3_98_3';
if (!empty($_GET[$sl_notice_id]) && $_GET[$sl_notice_id] == 1){$sl_vars['sl_a_s_check_v3_98_3'] = 'hide'; }
if ( (empty($sl_vars['sl_a_s_check_v3_98_3']) || $sl_vars['sl_a_s_check_v3_98_3'] != 'hide') && sl_data('sl_a_s_check_v3_98_3')!='hide' ) { 
//shifted to $sl_vars in v3.18, but sl_data('sl_a_s_check_v3_98_3')!='hide' left there so it won't show again to those who've already hidden it
$sl_po_features = "- results sorting [alphabetical or otherwise],<br>
- custom map coloring, <br>
- starting search location,<br>
- search by country,<br>
- search by name, <br>
- custom search button styling, <br> 
- KML map overlays, and much more<br>";
$sl_dc_features = "- by showing hook names & locations, <br>
- providing sample code, <br>
- API documentation, <br>
- and an API browser of current hooked functions.";

print "<br><div class='sl_admin_success' style='line-height: 22px; width:97%'>
<div style='background-color:lightYellow; border-top:solid gold 1px; border-bottom:solid gold 1px; padding:5px; /*background-image:url(".SL_IMAGES_BASE_ORIGINAL."/logo.small.png); background-repeat:no-repeat; padding-left:45px; background-position-y:10px;*/'><img src='".SL_IMAGES_BASE_ORIGINAL."/logo.small.png' style='vertical-align:middle'>&nbsp;<b>Welcome to LotsOfLocales&trade; -- WordPress Store Locator, <span style='color:#900; font-size:16px;'>v{$sl_version}</span>. What's New?: </b></div>

<div style='background-color:lightYellow; /*border-top:solid gold 1px; border-bottom:solid gold 1px; */padding:7px'>

<strong style='font-size:1.0em'>A.</strong>&nbsp;".__("Since June 2018, you now need a Google Maps API Key for all websites", "store-locator").". <a target='_blank' href='https://developers.google.com/maps/documentation/javascript/get-api-key'>".__("Get your key here", "store-locator")."</a>, ".__("and submit your API Key to MapDesigner settings page", "store-locator").".

<div style='padding:7px; padding-left:0px;'>
<strong style='font-size:1.0em'>B.</strong>&nbsp;".__("<a href='https://docs.viadat.com/Main_Page#New_in_Version_4.0' target='_blank'>Store Locator v4.0+</a>, which is PHP 7+ compatible, is now available, featuring 2 major new addons thus far: 
<div style='padding-left:25px;'>
<li><a href='https://docs.viadat.com/Power_Options' target='_blank'>Power Options</a> featuring beneficial, highly-requested features like: 
<div style='padding-left: 40px'>".sl_truncate($sl_po_features, 46, 'return', 2)."
</div>
</li> 
<li><a href='https://docs.viadat.com/Developer_Console' target='_blank'>Developer Console</a> which helps your create your own addons:
<div style='padding-left: 40px'>".sl_truncate($sl_dc_features, 36, 'return', 2)."
</div>", "store-locator")."</li>
</div>
</div>

<strong style='font-size:1.0em'>C.</strong>&nbsp;".__("If encountering geocoding issues (red highlighted locations, no coordinates, location not showing up on your map) <a href='https://docs.viadat.com/Super_Geocoder' target='_blank'>Super Geocoder</a> addon has proven to help many users", "store-locator").".
</div>


<div style='background-color:lightYellow; border-top:solid gold 1px; border-bottom:solid gold 1px; padding:7px;'><b>Note:</b> To display the Store Locator, type <strong style='font-size:1.0em'>[STORE-LOCATOR]</strong> into a <a href='".admin_url("edit.php?post_type=page")."'>Page</a> or <a href='".admin_url("edit.php")."'>Post</a> <br>or use the code <b>&lt;?php if (function_exists('sl_template')) {print sl_template('[STORE-LOCATOR]');} ?&gt;</b> in a page template.</div>
<br>
(<a href='". esc_url_raw($_SERVER['REQUEST_URI']) ."&". esc_attr($sl_notice_id) ."=1'>".__("Hide Message", "store-locator")."</a>)
</div>

 <div id='las-info' style='display:none; line-height:20px;'>
 <b style='font-size:1.3em'><br>LotsOfLocales&trade; ".__("Addons Platform Lite", "store-locator")."</b>
<ol style='line-height:20px'>
 <li>".__("Provides you with an addons management page for updating settings and activating each of your addons", "store-locator")."</li>
 <!--li>".__("Allows you to conveniently browse and install addons directly from this admin area from the Addons Marketplace to enhance your Store Locator's abilities", "store-locator")."</li-->
<li>".__("Create multiple Store locators on different Pages or Posts on your website using the format: <div class='code sl_code'>[STORE-LOCATOR {tag_name}={tag_value}, ...] (One of the many addon features available)</div> ", "store-locator")."</li>
<li>".__("Backwards-compatible with addons purchased before version 2.0, including the CSV Importer, DB Importer, & Multiple Field Updater", "store-locator")."</li>
<li>".__("Learn more about G2 Addons that come with the purchase of the LotsOfLocales&trade; Addons Platform Lite from the 'ReadMe Instructions' in the pull-out Dashboard", "store-locator")."</li>
</ol>
</div>";
}

# Notice about having cURL off
$sl_notice_id = 'curl_msg';
if (!extension_loaded("curl")) {
	if (!empty($_GET[$sl_notice_id]) && $_GET[$sl_notice_id] == 1){$sl_vars[$sl_notice_id] = 'hide'; }
	if (empty($sl_vars[$sl_notice_id]) || $sl_vars[$sl_notice_id] != 'hide') {
		print "<br><div class='sl_admin_warning' style='line-height: 22px; width:97%'><b>".__("Important Note", "store-locator").":</b><br>
		".__("It appears that you do not have <a href='http://us3.php.net/manual/en/book.curl.php' target='_blank'>cURL</a> actively running on this website.  cURL or <a href='http://us3.php.net/manual/en/function.file-get-contents.php' target='_blank'>file_get_contents()</a> needs to be active in order to receive coordinates for locations, validate addons, and other important actions performed by Store Locator", "store-locator").".
		<br>
(<a href='". esc_url_raw($_SERVER['REQUEST_URI']) ."&". esc_attr($sl_notice_id) ."=1'>".__("Hide Message", "store-locator")."</a>)
		</div>";
			
	}
}

# Notice about file permissions
$sl_notice_id = 'file_perm_msg';
if (!empty($_GET[$sl_notice_id]) && $_GET[$sl_notice_id] == 1){$sl_vars[$sl_notice_id] = 'hide'; }
if (empty($sl_vars[$sl_notice_id]) || $sl_vars[$sl_notice_id] != 'hide') {
	$sl_vars['file_perm_check_time'] = (empty($sl_vars['file_perm_check_time']))? date("Y-m-d H:i:s") : $sl_vars['file_perm_check_time'];
	
	if (!isset($sl_vars['perms_need_update']) || $sl_vars['perms_need_update'] == 1 || ($sl_vars['perms_need_update'] == 0 && (time() - strtotime($sl_vars['file_perm_check_time']))/60 >= (60*6)) ) { // 6-hr checks, when last check showed no files needed permissions updates (1-hr checks feel like it's a bit too often / pestering
		//print "test";
		sl_permissions_check();
		$sl_vars['file_perm_check_time'] = date("Y-m-d H:i:s");
	}
}

#Notice for CSV Importer
$sl_notice_id = 'csv_imp_msg';
if (!empty($_GET[$sl_notice_id]) && $_GET[$sl_notice_id] == 1){$sl_vars[$sl_notice_id] = 'hide'; }
if (empty($sl_vars[$sl_notice_id]) || $sl_vars[$sl_notice_id] != 'hide') {
	$max_input_vars_value = ini_get('max_input_vars');
	$max_input_default = ( !empty($max_input_vars_value) && $max_input_vars_value <= 1000 ); 
	$csv_needs_mod = 
	( 
		( (file_exists(SL_ADDONS_PATH."/csv-xml-importer-exporter/csv-xml-importer-exporter.php") 
			&& sl_data('sl_activation_csv-xml-importer-exporter')!==NULL) 
			|| (file_exists(SL_ADDONS_PATH."/csv-importer-exporter-g2/csv-importer-exporter-g2.php") 
			&& sl_data('sl_activation_csv-importer-exporter-g2')!==NULL)
		) 
		&& version_compare(phpversion(), '5.3.9') >= 0 
		&& $max_input_default
	);
	
	if ($csv_needs_mod) {
		print "<br><div class='sl_admin_warning' style='line-height: 22px; width:97%'><b>".__("Important Note", "store-locator").":</b><br>
		".__("You have the CSV Importer installed.  Due to a new directive added to PHP 5.3.9 called 'max_input_vars', which restricts maximum array sizes to 1000 by default, you may need to update your 'php.ini' & '.htaccess' files to allow for larger imports (<b>Your current 'max_input_vars' value is", "store-locator")." $max_input_vars_value</b>). <a href='http://docs.viadat.com/CSV_Importer_Geocoder_Exporter_XML_Exporter#When_I_try_to_upload_a_CSV_file.2C_nothing_happens.2C_I_get_no_error_message_and_remain_on_the_.22Add_Locations.22_page.2C_or_I_get_a_blank_screen' target='_blank'>".__("View instructions on making updates", "store-locator")."</a>.
		<br><br>
(<a href='". esc_url_raw($_SERVER['REQUEST_URI']) ."&". esc_attr($sl_notice_id) ."=1'>".__("Hide Message", "store-locator")."</a>)
		</div>";
	}
}

# Notice for addon files being in addons directory. There should only be folders (excludes index.php & zip files)
# v3.94 - exclude .csv
$sl_notice_id = 'files_in_addons_dir';
if (!empty($_GET[$sl_notice_id]) && $_GET[$sl_notice_id] == 1){$sl_vars[$sl_notice_id] = 'hide'; }
if (empty($sl_vars[$sl_notice_id]) || $sl_vars[$sl_notice_id] != 'hide') {
	$addons_contents = glob(SL_ADDONS_PATH."/*.*", GLOB_NOSORT);
	if (!empty($addons_contents)) {
	   foreach ($addons_contents as $a_item) {
		$the_a_file = str_replace(SL_ADDONS_PATH."/", "", $a_item);
		if (!is_dir($a_item) && $the_a_file != "index.php" && $the_a_file != "dummy.php" && !preg_match("@\.(zip|csv)$@", $the_a_file) && !preg_match("@error@", $the_a_file) ) {
			$not_a_dir[] = $the_a_file;
		}
	   }
	   if (!empty($not_a_dir)) {
		print "<br><div class='sl_admin_warning' style='line-height: 22px; width:97%'><b>".__("Important Note", "store-locator").":</b><br>
		".__("You have placed files in your 'addons' directory here", "store-locator").": <b>/".str_replace(ABSPATH, "", SL_ADDONS_PATH)."/</b>. ".__("There should only be folders.  All addon-related files need to be inside of their proper addon folder in order to work with Store Locator", "store-locator").". (<b>e.g.</b> <b style='color:DarkGreen'>".__("Correct", "store-locator").":</b> /addons<b>/addons-platform/</b>addons-platform.php, <b style='color: DarkRed'>".__("Incorrect", "store-locator").":</b> /addons/addons-platform.php) <br><br><b>".__("Files that need to be moved", "store-locator")."</b>: ";
		print "".implode($not_a_dir, ",  ");
		print "<br><div style='float:right'>
(<a href='". esc_url_raw($_SERVER['REQUEST_URI']) ."&". esc_attr($sl_notice_id) ."=1'>".__("Hide Message Permanently", "store-locator")."</a>)</div>
<br clear='all'>
		</div>";
	   }
      }
}

#Notice for wp-content hardening
$sl_notice_id = 'wp_content_harden';
if (!empty($_GET[$sl_notice_id]) && $_GET[$sl_notice_id] == 1){$sl_vars[$sl_notice_id] = 'hide'; }
if (empty($sl_vars[$sl_notice_id]) || $sl_vars[$sl_notice_id] != 'hide') {
	$wp_content_security = (file_exists(WP_CONTENT_DIR."/.htaccess"));
	
	if ($wp_content_security) {
		print "<br><div class='sl_admin_warning' style='line-height: 22px; width:97%'><b>".__("Important Note", "store-locator").":</b><br>
		".__("It appears that you have a <b>.htaccess</b> file in your 'wp-content' directory here", "store-locator").": <b>".WP_CONTENT_DIR."/</b><br>  ".__("If you're using a security plugin or other security method to 'harden' or block PHP files from working in  '/wp-content/', it will probably block Store Locator from fully working.  This includes (1) validating addons and (2) displaying locations.", "store-locator")."
		<br><br>
(<a href='". esc_url_raw($_SERVER['REQUEST_URI']) ."&". esc_attr($sl_notice_id) ."=1'>".__("Hide Message", "store-locator")."</a>)
		</div>";
	}
}

#Updating language file prefixes - Notice for updating language file prefix (should probably almost never show up -- only show if update fails)	
/* v3.80 - Updating translation files' prefixes in /sl-uploads/languages/ in order for translations to still work since text domain needed to be updated to 'store-locator' from 'lol' in preparation for WordPress plugin translation automation via translate.wordpress.org*/
$sl_notice_id = "sl_lang_file_prefix";
if (!empty($_GET[$sl_notice_id]) && $_GET[$sl_notice_id] == 1){$sl_vars[$sl_notice_id] = 'hide'; }
if (empty($sl_vars[$sl_notice_id]) || $sl_vars[$sl_notice_id] != 'hide') {

	$sl_lang_files = glob(SL_LANGUAGES_PATH."/lol-*", GLOB_NOSORT);
	$sl_lang_fail_ctr = 0;

	//var_dump($sl_lang_files); die();
	if (!empty($sl_lang_files)) {
		foreach ($sl_lang_files as $a_lang_file) {
			$new_lang_file_name = str_replace("lol-", "store-locator-", $a_lang_file);
			@chmod($a_lang_file, 0644);
			$sl_lang_file_update = @rename($a_lang_file, $new_lang_file_name);
		
			if ($sl_lang_file_update === FALSE) { $sl_lang_fail_ctr++; }
		}
	
		if ($sl_lang_fail_ctr > 0) {
			print "<br><div class='sl_admin_warning' style='line-height: 22px; width:97%'><b>".__("Important Note", "store-locator").":</b><br>".__("As of Store Locator v3.80, the prefixes of language files need to be updated from", "store-locator")." '<b>lol-'</b> to <b>'store-locator-'</b> in <b>'/".str_replace(ABSPATH, "", SL_LANGUAGES_PATH)."'</b>.  <br>".__("This update should have automatically happened already, but if not, make sure to do so manually if you are using a translation for your Store Locator installation.", "store-locator")."<br><br>
(<a href='". esc_url_raw($_SERVER['REQUEST_URI']) ."&". esc_attr($sl_notice_id) ."=1'>".__("Hide Message", "store-locator")."</a>)
		</div>";
		}
	}
}

# Notice about PHP data file response codes (sl-xml.php, store-locator-js.php), if not 200 OK
$sl_notice_id = 'data_response_codes';
if (!empty($_GET[$sl_notice_id]) && $_GET[$sl_notice_id] == 1){$sl_vars[$sl_notice_id] = 'hide'; }
if (empty($sl_vars[$sl_notice_id]) || $sl_vars[$sl_notice_id] != 'hide') {
   $sl_vars[$sl_notice_id.'_check_time'] = (empty($sl_vars[$sl_notice_id.'_check_time']))? date("Y-m-d H:i:s") : $sl_vars[$sl_notice_id.'_check_time'];
   
   if ( (time() - strtotime($sl_vars[$sl_notice_id.'_check_time']))/60 >= (60*1) ) { //60*1:  1-hr interval between checks
	$sl_data_files_msg = "";
	$sl_data_files = array(SL_BASE . '/sl-xml.php', SL_JS_BASE . '/store-locator-js.php');
	
	foreach ($sl_data_files as $value) {
		$sl_data_files_status = get_headers($value, 1);
		$sl_data_files_msg .= (preg_match("@200 OK@", $sl_data_files_status[0]))? "" : "<b>". __("File", "store-locator") .":</b>&nbsp;<a href='$value' target='_blank'>$value</a> | <b>". __("Status", "store-locator"). ":</b> {$sl_data_files_status[0]}<br>";
	}
		
	if (!empty($sl_data_files_msg)) { print "<div class='sl_admin_warning' style='line-height: 22px; width:97%'><b>".__("Important Note", "store-locator").":</b><br>".__("One or more of your important data files may not be loading properly (<b>'200 OK'</b> status).  Make sure nothing on your server is blocking them, such as .htaccess rules, robots.txt rules, or security plugins.  More information below:", "store-locator")."<br><br>".$sl_data_files_msg."<br><br>
(<a href='". esc_url_raw($_SERVER['REQUEST_URI']) ."&". esc_attr($sl_notice_id) ."=1'>".__("Hide Message Permanently", "store-locator")."</a>)</div>"; }
	$sl_vars[$sl_notice_id.'_check_time'] = date("Y-m-d H:i:s");
    }
}

?>