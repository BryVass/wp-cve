<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function sl_move_upload_directories() {
	global $sl_uploads_path, $sl_path;
	
	$sl_uploads_arr=wp_upload_dir();
	if (!is_dir($sl_uploads_arr['baseurl'])) {
		mkdir($sl_uploads_arr['baseurl'], 0755, true);
	}
	if (!is_dir(SL_UPLOADS_PATH)) {
		mkdir(SL_UPLOADS_PATH, 0755, true);
	}
	if (is_dir(SL_ADDONS_PATH_ORIGINAL) && !is_dir(SL_ADDONS_PATH)) {
		sl_copyr(SL_ADDONS_PATH_ORIGINAL, SL_ADDONS_PATH);
		chmod(SL_ADDONS_PATH, 0755);
	}
	if (is_dir(SL_THEMES_PATH_ORIGINAL) && !is_dir(SL_THEMES_PATH)) {
		sl_copyr(SL_THEMES_PATH_ORIGINAL, SL_THEMES_PATH);
		chmod(SL_THEMES_PATH, 0755);
	}
	if (is_dir(SL_LANGUAGES_PATH_ORIGINAL) && !is_dir(SL_LANGUAGES_PATH)) {
		sl_copyr(SL_LANGUAGES_PATH_ORIGINAL, SL_LANGUAGES_PATH);
		chmod(SL_LANGUAGES_PATH, 0755);
	}
	if (is_dir(SL_IMAGES_PATH_ORIGINAL) && !is_dir(SL_IMAGES_PATH)) {
		sl_copyr(SL_IMAGES_PATH_ORIGINAL, SL_IMAGES_PATH);
		chmod(SL_IMAGES_PATH, 0755);
	}
	if (!is_dir(SL_CUSTOM_ICONS_PATH)) {
		mkdir(SL_CUSTOM_ICONS_PATH, 0755, true);
	}
	if (!is_dir(SL_CUSTOM_CSS_PATH)) {
		mkdir(SL_CUSTOM_CSS_PATH, 0755, true);
	}
	if (!is_dir(SL_CACHE_PATH)) {
	      mkdir(SL_CACHE_PATH, 0755, true);
	}
	sl_ht(SL_CACHE_PATH, 'ht');
	sl_ht(SL_ADDONS_PATH);
	sl_ht(SL_UPLOADS_PATH);
}
function sl_ht($path, $type='index'){
	if(is_dir($path) && !is_file($path."/.htaccess") && !is_file($path."/index.php")) {
		if ($type == 'ht') {
$htaccess = '
<FilesMatch "\.(php|gif|jpe?g|png|css|js|csv|xml|json)$">
Allow from all
</FilesMatch>
order deny,allow
deny from all
allow from none
Options All -Indexes
'; #<- v3.98.5 - heredoc to single quotes
			$filename = $path."/.htaccess";
			$file_handle = @ fopen($filename, 'w+');
			@fwrite($file_handle, $htaccess);
			@fclose($file_handle);
			@chmod($file_handle, 0644);
		} elseif ($type == 'index') {
			$index ='<?php /*empty; prevents directory browsing*/ ?>';
			$filename = $path."/index.php";
			$file_handle = @ fopen($filename, 'w+');
			@fwrite($file_handle, $index);
			@fclose($file_handle);
			@chmod($file_handle, 0644);
		}	
	} elseif (is_dir($path) && is_file($path."/.htaccess") && $type == 'index') {
		//switching from .htaccess to blank index.php (.htaccess causing issues on some hosts)
		@unlink($path."/.htaccess");
		$index ='<?php /*empty; prevents directory browsing*/ ?>';
		$filename = $path."/index.php";
		$file_handle = @ fopen($filename, 'w+');
		@fwrite($file_handle, $index);
		@fclose($file_handle);
		@chmod($file_handle, 0644);		
	}
}
/* -----------------*/
function sl_parseToXML($htmlStr) 
{ 
$xmlStr=str_replace('<','&lt;',$htmlStr); 
$xmlStr=str_replace('>','&gt;',$xmlStr); 
$xmlStr=str_replace('"','&quot;',$xmlStr); 
$xmlStr=str_replace("'",'&#39;',$xmlStr); 
$xmlStr=str_replace("&",'&amp;',$xmlStr); 
$xmlStr=str_replace("," ,"&#44;" ,$xmlStr);
$xmlStr=sanitize_text_field($xmlStr); //v3.98.5 - 4/23/22 4:31p -- possibly redudant, but a WP requested update | ref: https://developer.wordpress.org/reference/functions/esc_attr/
$xmlStr=str_replace(array("\r\n", "\n", "\r"), "||sl-nl||", $xmlStr); //v3.76 - done 8/7/15 11:10a
return $xmlStr; 
} 
if (!function_exists('parseToXML')){
	//12/4/18 6:22pm - v3.98.3
	function parseToXML($htmlStr) {
		 return sl_parseToXML($htmlStr);
	}
}
/*-----------------*/
function filter_sl_mdo($the_arr) {
	$input_zone_clause = ( !isset($the_arr['input_zone']) || !isset($GLOBALS['input_zone_type']) || ($the_arr['input_zone'] == $GLOBALS['input_zone_type']) );
	
	$output_zone_clause = ( !isset($the_arr['output_zone']) || !isset($GLOBALS['output_zone_type']) || (!is_array($the_arr['output_zone']) && $the_arr['output_zone'] == $GLOBALS['output_zone_type']) ||  (is_array($the_arr['output_zone']) && in_array($GLOBALS['output_zone_type'], $the_arr['output_zone']) ) );
	
	return ($input_zone_clause && $output_zone_clause);
}

function sl_md_initialize(&$sl_vars) {
	//global $sl_vars;
	include(SL_INCLUDES_PATH."/mapdesigner-options.php");
	
	foreach ($sl_mdo as $value) {
		//if (isset($value['input_template'])) { unset($value['input_template']); }
		//var_dump($value['field_name']);
		
		if (isset($value['field_name']) && !is_array($value['field_name']) ) {
			$value['default'] = (!isset($value['default']))? "" : $value['default'];
			
			$default_not_set = !isset($sl_vars[$value['field_name']]);
			$default_set_but_value_set_to_blank = (isset($sl_vars[$value['field_name']]) && strlen(trim($sl_vars[$value['field_name']])) == 0);
			
			if ( ($default_not_set || $default_set_but_value_set_to_blank) ) {
				//If default value isn't set yet in $sl_vars, and field_name definition isn't an array
				$sl_vars[$value['field_name']] = $value['default'];
			} 
						
			$varname = "sl_".$value['field_name'];  //e.g. "$varname = sl_icon"
			global ${$varname};
			$$varname = $sl_vars[$value['field_name']]; //e.g "$sl_icon = $sl_vars['sl_icon']"
			
		} elseif (isset($value['field_name']) && is_array($value['field_name']) ) {
		   
			$value['default'] = (!isset($value['default']))? array_fill(0, count($value['field_name']), "") : $value['default'];
		
			//If default value isn't set yet in $sl_vars, and field_name definition is an array of fields
			$ctr = 0;	
			foreach ($value['default'] as $the_default) {
				
				$the_field = $value['field_name'][$ctr];
				$d_n_s = !isset($sl_vars[$the_field]);
				$d_s_b_v_s_t_b = (isset($sl_vars[$the_field]) && strlen(trim($sl_vars[$the_field])) == 0);
		
				if ( ($d_n_s || $d_s_b_v_s_t_b) ) {
					$sl_vars[$the_field] = $the_default;
				}
				
				$varname = "sl_".$the_field;  //e.g. "$varname = sl_icon"
				global ${$varname};
				$$varname = $sl_vars[$the_field];
				$ctr++;
			} 
		    
		}
	}
	//sl_data('sl_vars', 'add', $sl_vars);
}

function sl_md_save($data) {
	global $sl_vars;
	
	//MapDesigner header inputs & Geolocate true/false (based on Auto-locate value)
	$sl_vars['map_language']=sanitize_text_field($_POST['sl_map_language']);
	
	$sl_map_region_arr=explode(":", $_POST['map_region']);
	$sl_vars['google_map_country']=sanitize_text_field($sl_map_region_arr[0]);
	$sl_vars['google_map_domain']=sanitize_text_field($sl_map_region_arr[1]);
	$sl_vars['map_region']=sanitize_text_field($sl_map_region_arr[2]);
	$sl_vars['api_key']=sanitize_text_field($_POST['sl_api_key']);
	
	$sl_vars['sensor']=(empty($_POST['sl_geolocate']))? "false" : "true";
	//end

	foreach ($data as $value) {
	    
	    if (!empty($value['field_name'])) {
		$fname = $value['field_name'];
	
		if (!empty($value['field_type']) && $value['field_type'] == "checkbox") {
			//checkbox submissions need to save unchecked (empty) $_POST values as zero
			if (is_array($fname)) {
				foreach ($fname as $the_field) {
					$sl_vars[$the_field] = (empty($_POST["sl_".$the_field]))? 0 : sanitize_text_field($_POST["sl_".$the_field]) ;
				}
			} else {
				$sl_vars[$fname] = (empty($_POST["sl_".$fname]))? 0 : sanitize_text_field($_POST["sl_".$fname]) ;
			}
		} else {
			if (is_array($fname)) {
				$fctr = 0;
				foreach ($fname as $the_field) {
					$post_data = (isset($_POST["sl_".$the_field]))? sanitize_text_field($_POST["sl_".$the_field]) : sanitize_text_field($_POST[$the_field]) ;
					$post_data = (!empty($value['stripslashes'][$fctr]) && $value['stripslashes'][$fctr] == 1)? stripslashes($post_data) : $post_data;
					$post_data = (!empty($value['numbers_only'][$fctr]) && $value['numbers_only'][$fctr] == 1)? preg_replace("@[^0-9]@", "", $post_data) : $post_data;
					$sl_vars[$the_field] = sanitize_text_field($post_data);
					$fctr++;
				}
			} else {
				$post_data = (isset($_POST["sl_".$fname]))? sanitize_text_field($_POST["sl_".$fname]) : sanitize_text_field($_POST[$fname]) ;
				$post_data = (!empty($value['stripslashes']) && $value['stripslashes'] == 1)? stripslashes($post_data) : $post_data;
				$post_data = (!empty($value['numbers_only']) && $value['numbers_only'] == 1)? preg_replace("@[^0-9]@", "", $post_data) : $post_data;
				$sl_vars[$fname] = sanitize_text_field($post_data);
			}
		}
	    }
	    
	}

	sl_data('sl_vars', 'update', $sl_vars);
	
}

function sl_md_display($data, $input_zone, $template, $additional_classes = "") {
    global $sl_vars;
    
    print "<table class='mapdesigner_section {$additional_classes}'>";
    
    $GLOBALS['input_zone_type'] = $input_zone;
    $filtered_data = array_filter($data, "filter_sl_mdo");
    unset($GLOBALS['input_zone_type']);
    
    $labels_ctr = 0;
    foreach ($filtered_data as $key => $value) {
      
      //if ($value['input_zone'] == $input_zone) {
    
    	if ($template == 1) {
		//foreach ($data as $key => $value) {
		$the_row_id = (!empty($value["row_id"]))? " id = '$value[row_id]' " : "";
		$hide_row = (!empty($value['hide_row']) && $value['hide_row'] == true)? "style='display:none' " : "" ;
		$colspan = (!empty($value['colspan']) && $value['colspan'] > 1)? "colspan = '$value[colspan]'" : "" ;
		
		print "<tr {$the_row_id} {$hide_row}>
			<td {$colspan}>".$value['label'];
		if (!empty($value['more_info_label'])) {
			print "&nbsp;(<a href='#$value[more_info_label]' rel='sl_pop'>?</a>)&nbsp;";
		}
		print "</td>";
	   if (empty($value['colspan']) || $value['colspan'] < 2) {
	   	if (!empty($value['field_type']) && $value['field_type'] == 'checkbox') {
	   		if (!is_array($value['field_name'])){
		   		//need to add checked='checked' if value is checked
		   		$value['input_template'] = (isset($sl_vars[$value['field_name']]) && $sl_vars[$value['field_name']] == 1)? preg_replace("@>@", " checked='checked'>", $value['input_template']) : $value['input_template'] ;
		   	} /*else {
		   		foreach ($value['field_name'] as $fname) {
		   			$value['']
		   		}
		   	}*/
	   	}
	   
		print "<td>".$value['input_template'];
		if (!empty($value['more_info'])) {
			print "<div style='display:none;' id='$value[more_info_label]'>";
			print $value['more_info'];
			print "</div>";
		}
		print "</td>";
	    }
	    print "</tr>";
		//}
    	} elseif ($template == 2) {
		
		//foreach ($data as $key => $value) {
		if ($labels_ctr % 3 == 0) {
			$the_row_id = (!empty($value["row_id"]))? " id = '$value[row_id]' " : "";
			print "<tr {$the_row_id}>";
		}	
		$the_more_info_label = (!empty($value['more_info_label']))? "&nbsp;(<a href='#$value[more_info_label]' rel='sl_pop'>?</a>)&nbsp;" : "" ;
		
		print "<td>".$value['input_template']."<br><span style='font-size:80%'>".$value['label']."{$the_more_info_label}</span>";
	
		if (!empty($value['more_info'])) {
			print "<div style='display:none;' id='$value[more_info_label]'>";
			print $value['more_info'];
			print "</div>";
		}
		print "</td>";
		if (($labels_ctr+1) % 3 == 0) {
			print "</tr>";
		}
		$labels_ctr++;
	//}
    	}
    	
      //}
    	
    }
    
    print "</table>";
}
/*-----------------*/
function sl_initialize_variables() {

global $sl_height, $sl_width, $sl_width_units, $sl_height_units, $sl_radii;
global $sl_icon, $sl_icon2, $sl_google_map_domain, $sl_google_map_country, $sl_theme, $sl_base, $sl_uploads_base, $sl_location_table_view;
global $sl_search_label, $sl_zoom_level, $sl_use_city_search, $sl_use_name_search, $sl_name;
global $sl_radius_label, $sl_website_label, $sl_directions_label, $sl_num_initial_displayed, $sl_load_locations_default;
global $sl_distance_unit, $sl_map_overview_control, $sl_admin_locations_per_page, $sl_instruction_message;
global $sl_map_character_encoding, $sl_start, $sl_map_language, $sl_map_region, $sl_sensor, $sl_geolocate;
global $sl_map_type, $sl_remove_credits, $sl_api_key, $sl_location_not_found_message, $sl_no_results_found_message; 
global $sl_load_results_with_locations_default, $sl_vars, $sl_city_dropdown_label, $sl_scripts_load, $sl_scripts_load_home, $sl_scripts_load_archives_404;
global $sl_hours_label, $sl_phone_label, $sl_fax_label, $sl_email_label;

$sl_vars=sl_data('sl_vars'); //important, otherwise may reset vars to default (?) - 11/13/13
//$sl_google_map_domain=sl_data('sl_google_map_domain');
if (empty($sl_vars)){
	//transition from individual variables to single array of variables
	$sl_vars['height']=sl_data('sl_map_height'); $sl_vars['width']=sl_data('sl_map_width'); $sl_vars['width_units']=sl_data('sl_map_width_units'); $sl_vars['height_units']=sl_data('sl_map_height_units'); $sl_vars['radii']=sl_data('sl_map_radii'); $sl_vars['icon']=sl_data('sl_map_home_icon'); $sl_vars['icon2']=sl_data('sl_map_end_icon2'); $sl_vars['google_map_domain']=sl_data('sl_google_map_domain'); $sl_vars['google_map_country']=sl_data('sl_google_map_country'); $sl_vars['theme']=sl_data('sl_map_theme'); $sl_vars['location_table_view']=sl_data('sl_location_table_view'); $sl_vars['search_label']=sl_data('sl_search_label'); $sl_vars['zoom_level']=sl_data('sl_zoom_level'); $sl_vars['use_city_search']=sl_data('sl_use_city_search'); $sl_vars['use_name_search']=sl_data('sl_use_name_search'); $sl_vars['name']=sl_data('sl_name'); $sl_vars['radius_label']=sl_data('sl_radius_label'); $sl_vars['website_label']=sl_data('sl_website_label'); $sl_vars['directions_label']=sl_data('sl_directions_label'); $sl_vars['num_initial_displayed']=sl_data('sl_num_initial_displayed'); $sl_vars['load_locations_default']=sl_data('sl_load_locations_default'); $sl_vars['distance_unit']=sl_data('sl_distance_unit'); $sl_vars['map_overview_control']=sl_data('sl_map_overview_control'); $sl_vars['admin_locations_per_page']=sl_data('sl_admin_locations_per_page'); $sl_vars['instruction_message']=sl_data('sl_instruction_message'); $sl_vars['map_character_encoding']=sl_data('sl_map_character_encoding'); $sl_vars['start']=sl_data('sl_start'); $sl_vars['map_language']=sl_data('sl_map_language'); $sl_vars['map_region']=sl_data('sl_map_region'); $sl_vars['sensor']=sl_data('sl_sensor'); $sl_vars['geolocate']=sl_data('sl_geolocate'); $sl_vars['map_type']=sl_data('sl_map_type'); $sl_vars['remove_credits']=sl_data('sl_remove_credits'); $sl_vars['api_key']=sl_data('store_locator_api_key'); $sl_vars['load_results_with_locations_default']=sl_data('sl_load_results_with_locations_default'); $sl_vars['city_dropdown_label']=sl_data('sl_city_dropdown_label'); 
}

### From MapDesigner Options
sl_md_initialize($sl_vars);

### Dependent Variables
if (strlen(trim($sl_vars['sensor'])) == 0) {	$sl_vars['sensor'] = ($sl_vars['geolocate'] == '1')? "true" : "false";	}
$sl_sensor=$sl_vars['sensor'];

### MapDesigner header row inputs
if ($sl_vars['api_key'] === NULL) {	$sl_vars['api_key']="";	}
$sl_api_key=$sl_vars['api_key'];

if (strlen(trim($sl_vars['google_map_country'])) == 0) {	$sl_vars['google_map_country']="United States";}
$sl_google_map_country=$sl_vars['google_map_country'];

if (strlen(trim($sl_vars['google_map_domain'])) == 0) {	$sl_vars['google_map_domain']="maps.google.com";}
$sl_google_map_domain=$sl_vars['google_map_domain'];

if ($sl_vars['map_region'] === NULL) {	$sl_vars['map_region']="";	}
$sl_map_region=$sl_vars['map_region'];

if (strlen(trim($sl_vars['map_language'])) == 0) {	$sl_vars['map_language']="en";	}
$sl_map_language=$sl_vars['map_language'];

if ($sl_vars['map_character_encoding'] === NULL) {	$sl_vars['map_character_encoding']="";		}
$sl_map_character_encoding=$sl_vars['map_character_encoding'];

### Meta
if (strlen(trim($sl_vars['start'])) == 0) { 	$sl_vars['start']=date("Y-m-d H:i:s"); 	} 
$sl_start=$sl_vars['start']; 

if (strlen(trim($sl_vars['name'])) == 0) {	$sl_vars['name']="LotsOfLocales";	}  
$sl_name=$sl_vars['name'];

### Location Management Page View Control
if (strlen(trim($sl_vars['admin_locations_per_page'])) == 0) {	$sl_vars['admin_locations_per_page']="100";	}
$sl_admin_locations_per_page=$sl_vars['admin_locations_per_page'];

if (strlen(trim($sl_vars['location_table_view'])) == 0) {	$sl_vars['location_table_view']="Normal";	}
$sl_location_table_view=$sl_vars['location_table_view'];

### Maps V2 -> V3 Transition
if (strlen(trim($sl_vars['map_type'])) == 0) {	$sl_vars['map_type']="google.maps.MapTypeId.ROADMAP";}
elseif ($sl_vars['map_type']=="G_NORMAL_MAP"){	$sl_vars['map_type']='google.maps.MapTypeId.ROADMAP';}
elseif ($sl_vars['map_type']=="G_SATELLITE_MAP"){	$sl_vars['map_type']='google.maps.MapTypeId.SATELLITE';}
elseif ($sl_vars['map_type']=="G_HYBRID_MAP"){	$sl_vars['map_type']='google.maps.MapTypeId.HYBRID';}
elseif ($sl_vars['map_type']=="G_PHYSICAL_MAP"){	$sl_vars['map_type']='google.maps.MapTypeId.TERRAIN';}
$sl_map_type=$sl_vars['map_type'];

/*if (strlen(trim($sl_vars['use_name_search'])) == 0) {	$sl_vars['use_name_search']="0";	}
$sl_use_name_search=$sl_vars['use_name_search'];*/

	sl_data('sl_vars', 'add', $sl_vars);
}
/*--------------------------*/
function sl_choose_units($unit, $input_name) {
	$unit_arr[]="%";$unit_arr[]="px";$unit_arr[]="em";$unit_arr[]="pt";
	$select_field="<select name='$input_name'>";
	
	//global $height_units, $width_units;
	
	foreach ($unit_arr as $value) {
		$selected=($value=="$unit")? " selected='selected' " : "" ;
		if (!($input_name=="height_units" && $value=="%")) { //v3.90.1 - should be $value=="%", not $unit=="%" -  it's causing error if % selected
			$select_field.="\n<option value='$value' $selected>$value</option>";
		}
	}
	$select_field.="</select>";
	return $select_field;
}
/*----------------------------*/
function sl_install_tables() {
	global $wpdb, $sl_db_version, $sl_path, $sl_uploads_path, $sl_hook;

	if (!defined("SL_TABLE") || !defined("SL_TAG_TABLE") || !defined("SL_SETTING_TABLE")){ 
		//add_option("sl_db_prefix", $wpdb->prefix); $sl_db_prefix = get_option('sl_db_prefix'); 
		$sl_db_prefix = $wpdb->prefix; //better this way, in case prefix changes vs storing option - 1/29/15
	}
	if (!defined("SL_TABLE")){ define("SL_TABLE", $sl_db_prefix."store_locator");}
	if (!defined("SL_TAG_TABLE")){ define("SL_TAG_TABLE", $sl_db_prefix."sl_tag"); }
	if (!defined("SL_SETTING_TABLE")){ define("SL_SETTING_TABLE", $sl_db_prefix."sl_setting"); }
	
	$table_name = SL_TABLE;
	$sql = "CREATE TABLE " . $table_name . " (
			sl_id mediumint(8) unsigned NOT NULL auto_increment,
			sl_store varchar(255) NULL,
			sl_address varchar(255) NULL,
			sl_address2 varchar(255) NULL,
			sl_city varchar(255) NULL,
			sl_state varchar(255) NULL,
			sl_country varchar(255) NULL,
			sl_zip varchar(255) NULL,
			sl_latitude varchar(255) NULL,
			sl_longitude varchar(255) NULL,
			sl_tags mediumtext NULL,
			sl_description mediumtext NULL,
			sl_url varchar(255) NULL,
			sl_hours varchar(255) NULL,
			sl_phone varchar(255) NULL,
			sl_fax varchar(255) NULL,
			sl_email varchar(255) NULL,
			sl_image varchar(255) NULL,
			sl_private varchar(1) NULL,
			sl_neat_title varchar(255) NULL,
			PRIMARY KEY  (sl_id)
			) ENGINE=innoDB  DEFAULT CHARACTER SET=utf8  DEFAULT COLLATE=utf8_unicode_ci;";
			
	$table_name_2 = SL_TAG_TABLE;
	$sql .= "CREATE TABLE " . $table_name_2 . " (
			sl_tag_id bigint(20) unsigned NOT NULL auto_increment,
			sl_tag_name varchar(255) NULL,
			sl_tag_slug varchar(255) NULL,
			sl_id mediumint(8) NULL,
			PRIMARY KEY  (sl_tag_id)
			) ENGINE=innoDB  DEFAULT CHARACTER SET=utf8  DEFAULT COLLATE=utf8_unicode_ci;";
	
	$table_name_3 = SL_SETTING_TABLE;
	$sql .= "CREATE TABLE " . $table_name_3 . " (
			sl_setting_id bigint(20) unsigned NOT NULL auto_increment,
			sl_setting_name varchar(255) NULL,
			sl_setting_value longtext NULL,
			PRIMARY KEY  (sl_setting_id)
			) ENGINE=innoDB  DEFAULT CHARACTER SET=utf8  DEFAULT COLLATE=utf8_unicode_ci;";
	//$sql .= "INSERT INTO " . $table_name_3 . " (sl_setting_name, sl_setting_value) VALUES ('sl_db_prefix', '" . $wpdb->prefix . "');";
			
	if($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) != $table_name || $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name_2)) != $table_name_2 || $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name_3)) != $table_name_3) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		sl_data("sl_db_version", 'add', $sl_db_version);
	}
	
	$installed_ver = sl_data("sl_db_version");
	if( $installed_ver != $sl_db_version ) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		sl_data("sl_db_version", 'update', $sl_db_version);
	}
	
	if (sl_data("sl_db_prefix")===""){
		sl_data('sl_db_prefix', 'update', $sl_db_prefix);
	}
	
	sl_move_upload_directories();
}
/*-------------------------------*/
function is_on_sl_page() {
   global $sl_dir, $sl_base, $sl_uploads_base, $sl_path, $sl_uploads_path, $wpdb, $pagename, $sl_map_language, $post, $sl_vars; 		
	
   if (!is_admin()) { //v3.88 - moved "!is_admin()" clause into function, instead of wrapping around action hook
	$on_sl_page=""; $sl_code_is_used_in_posts=""; $post_ids_array=""; $the_p = ""; $the_page_id = "";
	$post_ids_array=array(); 
	if (empty($sl_vars['scripts_load']) || $sl_vars['scripts_load'] != 'all') {
		//Check if currently on page with shortcode
		if (!empty($_GET['p'])){ $the_p = sanitize_text_field($_GET['p']); } if (!empty($_GET['page_id'])){ $the_page_id = sanitize_text_field($_GET['page_id']); }
		$query = $wpdb->prepare("SELECT post_name, post_content FROM ".SL_DB_PREFIX."posts WHERE LOWER(post_content) LIKE '%%[store-locator%%' AND (post_name=%s OR ID=%d OR ID=%d)", $pagename, $the_p, $the_page_id);
		$on_sl_page=$wpdb->get_results($query, ARRAY_A);		
		//Checking if code used in posts	
		$sl_code_is_used_in_posts=$wpdb->get_results("SELECT post_name, ID FROM ".SL_DB_PREFIX."posts WHERE LOWER(post_content) LIKE '%[store-locator%' AND post_type='post'", ARRAY_A);
		//If shortcode used in posts, put post IDs into array of numbers
		if ($sl_code_is_used_in_posts) {
			$sl_post_ids=$sl_code_is_used_in_posts;
			foreach ($sl_post_ids as $val) { $post_ids_array[]=$val['ID'];}
		} else {		
			$post_ids_array=array(pow(10,15)); //post number that'll never be reached
		}
		//print_r($post_ids_array);
	}
	
	//If loading on all pages is selected (via MapDesigner), on page with store locator shortcode, on an archive, search, or 404 page while shortcode has been used in a post, on the front page, or a specific post with shortcode, is a custom post type of some kind, or is a using a page template, display code, otherwise, don't
	$show_on_all_pages = ( !empty($sl_vars['scripts_load']) && $sl_vars['scripts_load'] == 'all' );
	$show_on_front_page = ( is_front_page() && (!isset($sl_vars['scripts_load_home']) || $sl_vars['scripts_load_home']==1) );
	$show_on_archive_404_pages = ( (is_archive() || is_404()) && $sl_code_is_used_in_posts && (!isset($sl_vars['scripts_load_archives_404']) || $sl_vars['scripts_load_archives_404']==1) );
	$show_on_custom_post_types = ( is_singular() && !is_singular(array('page', 'attachment', 'post')) && !is_front_page() && !(is_archive() || is_404()) );
	$show_on_page_templates = ( is_page_template() && !is_front_page() && !(is_archive() || is_404()) );
	$on_sl_post = is_single($post_ids_array);
	$sl_scripts_function_exists = ( function_exists('show_sl_scripts') && !is_front_page() && !(is_archive() || is_404()) ); //empty sl_scripts() function can be created to force loading of scripts
	
	if ($show_on_all_pages || $on_sl_page || is_search() || $show_on_archive_404_pages || $show_on_front_page || $on_sl_post || $show_on_custom_post_types || $sl_scripts_function_exists || $show_on_page_templates) {
		$GLOBALS['is_on_sl_page'] = 1;
	} else {
		$GLOBALS['is_on_sl_page'] = 0;
	}
    }
}
add_action('wp', 'is_on_sl_page');

function sl_head_scripts() {
	global $sl_dir, $sl_base, $sl_uploads_base, $sl_path, $sl_uploads_path, $wpdb, $pagename, $sl_map_language, $post, $sl_vars; 		
	
	print "\n<!-- ========= WordPress Store Locator (v".SL_VERSION.") | http://" . SL_HOME_URL . "/store-locator/ ========== -->\n";
	
	if (isset($GLOBALS['is_on_sl_page']) && $GLOBALS['is_on_sl_page'] == 1) {
		//v3.88 added 'isset' clause above
		$google_map_domain=($sl_vars['google_map_domain']!="")? $sl_vars['google_map_domain'] : "maps.google.com";
		
		//print "<meta name='viewport' content='initial-scale=1.0, user-scalable=no' />\n";
		//$sens=(!empty($sl_vars['sensor']) && ($sl_vars['sensor'] === "true" || $sl_vars['sensor'] === "false" ))? "&amp;sensor=".$sl_vars['sensor'] : "&amp;sensor=false" ;
		$sens = ""; // - v3.84 - 11/25/15 - no longer required
		$lang_loc=(!empty($sl_vars['map_language']))? "&amp;language=".$sl_vars['map_language'] : "" ; 
		$region_loc=(!empty($sl_vars['map_region']))? "&amp;region=".$sl_vars['map_region'] : "" ;
		$key=(!empty($sl_vars['api_key']))? "&amp;key=".$sl_vars['api_key'] : "" ;
		wp_enqueue_script("sl_gmaps_api", "https://maps.googleapis.com/maps/api/js?v=3" . esc_attr($sens) . esc_attr($lang_loc) . esc_attr($region_loc) . esc_attr($key) );  //v3.96; id='store-locator' prevents removal by sl_remove_gmaps() on sites not observing action hook priorities
		//print "<script src='//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>\n";

		if (empty($_POST) && 1==1) { //skip, for now always (1==2), dynamic file always causes trouble for some //1==1 - v3.98
			$nm=(!empty($post->post_name))? $post->post_name : $pagename ;
			$p=(!empty($post->ID))? $post->ID : esc_sql($_GET['p']) ;
			//$pg=(!empty($post->page_ID))? $post->post_ID : esc_sql($_GET['page_id']) ;
			wp_enqueue_script("sl_dyn_js", SL_SITEURL."/?sl_engine=js_store-locator-js&v=".SL_VERSION."&nm=$nm&p=$p");
		} else {
			//sl_dyn_js($on_sl_page[0]['post_content']);
			wp_add_inline_script("sl_gmaps_api", "sl_dyn_js();", 'after'); //https://developer.wordpress.org/reference/functions/wp_add_inline_script/
		}

		//if store-locator.css exists in custom-css/ folder in uploads/ dir it takes precedence over, store-locator.css in store-locator plugin directory to allow for css customizations to be preserved after updates
		$has_custom_css=(file_exists(SL_CUSTOM_CSS_PATH."/store-locator.css"))? SL_CUSTOM_CSS_BASE : SL_CSS_BASE; 
		wp_enqueue_style("sl_main_css", esc_url($has_custom_css)."/store-locator.css?v=".SL_VERSION);
		$theme=$sl_vars['theme'];
		if ($theme!="") {wp_enqueue_style("sl_theme_css", SL_THEMES_BASE."/". esc_attr($theme) ."/style.css?v=".SL_VERSION); }
		if (function_exists("do_sl_hook")){do_sl_hook('sl_addon_head_styles');}
		//print "<style></style>";
		sl_move_upload_directories();
	} else {
		print "<!-- No store locator on this page, so no unnecessary scripts for better site performance. -->\n";
	}
	print "<!-- ========= End WordPress Store Locator (";
		$sl_page_ids=$wpdb->get_results("SELECT ID FROM ".SL_DB_PREFIX."posts WHERE LOWER(post_content) LIKE '%[store-locator%' AND post_status='publish'", ARRAY_A);
		if (!empty($sl_page_ids)) {
			foreach ($sl_page_ids as $value) { print "$value[ID],";}
		}
		print ") ========== -->\n\n";
}
function sl_footer_scripts(){
	if (!did_action('wp_head')){ sl_head_scripts();} //if wp_head missing
}
add_action('wp_print_footer_scripts', 'sl_footer_scripts');

function sl_jq() {
	wp_enqueue_script( 'jquery');
}
add_action('wp_enqueue_scripts', 'sl_jq');

function sl_remove_gmaps(){
    global $wp_scripts;
    if (isset($GLOBALS['is_on_sl_page']) && $GLOBALS['is_on_sl_page'] == 1) {
    	// Removing other Google Maps API instances
    	// Attempt - 11/25/15 - no luck - 2:19a - wp_print_scripts must actually be happening before wp_head since the 'is_on_sl_page' is NULL
	// Attempt #2 - 11/30/15 - 12:37a - v3.85

    	if (false != $wp_scripts->queue) {
    		//var_dump($wp_scripts);
       		foreach ($wp_scripts->queue as $script) {
            		if (isset($wp_scripts->registered[$script]) && preg_match("@maps\.google@", $wp_scripts->registered[$script]->src) ) {
               			//$wp_scripts->registered[$script]->deps = array();
 		       		$the_handle = $wp_scripts->registered[$script]->handle;
               			//unset($wp_scripts->queue[$the_handle]);
               			if ($the_handle != "sl_gmaps_api") {
               				wp_dequeue_script($the_handle);
	               			print "<!-- ========= Duplicate Google Maps API JavaScript - Removed by WordPress Store Locator to Avoid Errors. Script Details:\n".str_replace("\\", "", json_encode($wp_scripts->registered[$script]))."\n========= -->\n";
               			}
             		}
		}
	}
    }
}
add_action('wp_print_scripts', 'sl_remove_gmaps');

// Removing other Google Maps API instances from wp_head & wp_footer, if present on Store Locator page - v3.91
// Priorities of '..ob_end_flush' hooks must be less than priority of add_action('wp_head', 'sl_head_scripts') in store-locator.php;
function sl_remove_gmaps_ob_start() {
	if (isset($GLOBALS['is_on_sl_page']) && $GLOBALS['is_on_sl_page'] == 1) {
	    ob_start('sl_remove_gmaps_header_footer');
	}
}
function sl_remove_gmaps_ob_end_flush() {
	if (isset($GLOBALS['is_on_sl_page']) && $GLOBALS['is_on_sl_page'] == 1) {
	    ob_end_flush();
	}
}
function sl_remove_gmaps_header_footer($output) {
  if (isset($GLOBALS['is_on_sl_page']) && $GLOBALS['is_on_sl_page'] == 1) {
    if (preg_match("@<script(?!.*id='sl_gmaps_api-js')[^>]+maps.google[^>]+>[^<]*<\/script>@", $output)) {
         #ref: Negative lookahead: (?!.*id='sl_gmaps_api-js') -- http://stackoverflow.com/a/1240365 - v3.96
    	$output = preg_replace_callback("@<script(?!.*id='sl_gmaps_api-js')[^>]+maps.google[^>]+>[^<]*<\/script>@", 
    		function($matches) { return "<!-- ========= Duplicate Google Maps API JavaScript - Removed by WordPress Store Locator to Avoid Errors. Script HTML:  \n$matches[0] \n========= -->\n"; },
    		$output);
    }
    return $output;
  }
}
add_action('get_header', 'sl_remove_gmaps_ob_start');
add_action('wp_head', 'sl_remove_gmaps_ob_end_flush', 1000); 
add_action('get_footer', 'sl_remove_gmaps_ob_start');
add_action('wp_footer', 'sl_remove_gmaps_ob_end_flush', 1000);

/*function sl_jq_missing_wp_head($content){
      $sl_jq_scripts = "";
      if (!did_action('wp_head')) {
	global $wp_scripts;
        wp_enqueue_script( 'jquery'); //false, array(), false, true);
        ob_start();
        $wp_scripts->print_scripts();
        $sl_jq_scripts = ob_get_contents();
        ob_end_clean();
      }
      return $sl_jq_scripts.$content;
   }
add_action('the_content', 'sl_jq_missing_wp_head', 1000000); */
/*large to make sure not overwritten, even if higher priority hook overwrites content*/
/*commented out - 11/7/14 - causing errors with some themes' jQuery / image porfolios (Jupiter); not critical function*/
/*-----------------------------------*/
function sl_menu_position($start, $increment = 0.0001) {
	#http://stackoverflow.com/a/22382858
	$menus_positions = array_keys($GLOBALS['menu']);
        if (!in_array($start, $menus_positions)) return $start;
        /* the position is already reserved find the closest one */
        while (in_array($start, $menus_positions)) {
            $start += $increment;
        }
        return $start;
}
function sl_add_options_page() {
	global $sl_dir, $sl_base, $sl_uploads_base, $text_domain, $sl_top_nav_links, $sl_vars, $sl_version;
	$parent_url = SL_PARENT_URL; //SL_PAGES_DIR.'/information.php';
	$warning_count = 0;
	$warning_title = __("Update(s) currently available for Store Locator", "store-locator") . ":";
	
	####Base Plugin Update Notification in WP Menu =============
   // if (function_exists("plugins_api")) {
	$sl_vars['sl_latest_version_check_time'] = (empty($sl_vars['sl_latest_version_check_time']))? date("Y-m-d H:i:s") : $sl_vars['sl_latest_version_check_time'];
	if (empty($sl_vars['sl_latest_version']) || (time() - strtotime($sl_vars['sl_latest_version_check_time']))/60>=(60*12)){ //2-hr db caching of version info
		/*if (!function_exists("plugins_api")) {
			$plugin_install_url = ABSPATH."wp-admin/includes/plugin-install.php"; //die($plugin_install_url);
			include_once($plugin_install_url);
		}*/ // Causing fatal errors in some installs -- so must assume it's available to all already -- 2/23/15
		//$sl_api = plugins_api('plugin_information', array('slug' => 'store-locator', 'fields' => array('sections' => false) ) ); 
		$sl_api = @sl_remote_data(array(
			'host' => 'api.wordpress.org',
			'url' => '/plugins/info/1.0/store-locator',
			'ua' => 'none'), 'serial');
		
		/*need 'true' if trying to include changelog info in future*/
		//var_dump($sl_api); die();
		$sl_latest_version = @$sl_api->version; //'@': v3.97.1 //$sl_version="2.6";
		//$sl_latest_changelog = $sl_api->sections['changelog']; //var_dump($sl_latest_changelog); die();
		//preg_match_all("@<ul>(.*)</ul>@", $sl_latest_changelog, $sl_version_matches); var_dump($sl_version_matches); die();
	
		$sl_vars['sl_latest_version_check_time'] = date("Y-m-d H:i:s");
		$sl_vars['sl_latest_version'] = $sl_latest_version;
	} else {
		$sl_latest_version = $sl_vars['sl_latest_version'];
	}
	//$sl_version = 2.6; //testing purposes
	if (version_compare($sl_latest_version, $sl_version) > 0) { 
		$warning_title .= "\n- Store Locator v{$sl_latest_version} " . __("is available, you are using", "store-locator"). " v{$sl_version}";
		$warning_count++;
		
		$sl_plugin = SL_DIR . "/store-locator.php";
		$sl_update_link = admin_url('update.php?action=upgrade-plugin&plugin=' . $sl_plugin);
		$sl_update_link_nonce = wp_nonce_url($sl_update_link, 'upgrade-plugin_' . $sl_plugin);
		$sl_update_msg = "&nbsp;&gt;&nbsp;<a href='$sl_update_link_nonce' style='color:#900; font-weight:bold;' onclick='confirmClick(\"".__("You will now be updating to Store Locator", "store-locator")." v$sl_latest_version, ".__("click OK or Confirm to continue", "store-locator").".\", this.href); return false;'>".__("Update to", "store-locator")." $sl_latest_version</a>";
	} else {
		$sl_update_msg = "";
	}
  //   }	
	
	$notify = ($warning_count > 0)?  " <span class='update-plugins count-$warning_count' title='$warning_title'><span class='update-count'>" . $warning_count . "</span></span>" : "" ;
	
	$sl_menu_pages['main'] = array('title' => __("Store Locator", "store-locator")."$notify", 'capability' => 'administrator', 'page_url' =>  $parent_url, 'icon' => SL_BASE.'/images/logo.ico.png', 'menu_position' => '21.000723');
	$sl_menu_pages['sub']['information'] = array('parent_url' => $parent_url, 'title' => __("News & Upgrades", "store-locator"), 'capability' => 'administrator', 'page_url' => $parent_url);
	$sl_menu_pages['sub']['locations'] = array('parent_url' => $parent_url, 'title' => __("Locations", "store-locator"), 'capability' => 'administrator', 'page_url' => SL_PAGES_DIR.'/locations.php');
	$sl_menu_pages['sub']['mapdesigner'] = array('parent_url' => $parent_url, 'title' => __("MapDesigner", "store-locator"), 'capability' => 'administrator', 'page_url' => SL_PAGES_DIR.'/mapdesigner.php');
	
	/*if (!function_exists("do_sl_hook") && (empty($sl_vars['sl_pt_data_status']) || $sl_vars['sl_pt_data_status'] != 'inactive')) {
		$sl_apn = array("Addons", "Upgrade", "Go Premium");
		$sl_vars['sl_addons_page_name'] = (empty($sl_vars['sl_addons_page_name']))? $sl_apn[mt_rand(0,2)] : $sl_vars['sl_addons_page_name'];
		$sl_menu_pages['sub']['addons'] = array('parent_url' => $parent_url, 'title' => sprintf(__("%s", "store-locator"), $sl_vars['sl_addons_page_name']), 'capability' => 'administrator', 'page_url' => SL_PAGES_DIR.'/addons.php');
	}*/ //v3.98.7
	
	sl_menu_pages_filter($sl_menu_pages);
	//var_dump($GLOBALS['menu']);
	/*
	add_menu_page(__("Store Locator", "store-locator"), __("Store Locator", "store-locator"), 'administrator', SL_PAGES_DIR.'/locations.php', '', SL_BASE.'/images/logo.ico.png', 25.1);
	$sl_pg_loc = add_submenu_page(SL_PAGES_DIR.'/locations.php', __("Locations", "store-locator"), __("Locations", "store-locator"), 'administrator', SL_PAGES_DIR.'/locations.php');
	$sl_pg_md = add_submenu_page(SL_PAGES_DIR.'/locations.php', __("MapDesigner", "store-locator"), __("MapDesigner", "store-locator"), 'administrator', SL_PAGES_DIR.'/mapdesigner.php');*/
	
	//}
}

function sl_menu_pages_filter($sl_menu_pages) {
	if (function_exists('do_sl_hook')){do_sl_hook('sl_menu_pages_filter', '', array(&$sl_menu_pages), "", "", "", false);}
	
	foreach ($sl_menu_pages as $menu_type => $value) {
		if ($menu_type == 'main') {
			add_menu_page ($value['title'], $value['title'], $value['capability'], $value['page_url'], '', $value['icon'], $value['menu_position']);
		}
		if ($menu_type == 'sub'){
			foreach ($value as $sub_value) {
				 add_submenu_page($sub_value['parent_url'], $sub_value['title'], $sub_value['title'], $sub_value['capability'], $sub_value['page_url']);
			}
		}
	}
}
/*---------------------------------------------------*/
function sl_add_admin_javascript() {
        global $sl_base, $sl_uploads_base, $sl_dir, $google_map_domain, $sl_path, $sl_uploads_path, $sl_map_language, $sl_vars;

	wp_enqueue_script( 'prettyPhoto', SL_JS_BASE."/jquery.prettyPhoto.js", "jQuery");
	wp_enqueue_script( 'sl_func', SL_SITEURL."/?sl_engine=js_store-locator-js&admin=1", "jQuery");
        
        $admin_js = "
        var sl_dir='".$sl_dir."';
        var sl_google_map_country='".$sl_vars['google_map_country']."';
        var sl_base='".SL_BASE."';
        var sl_uploads_base='".SL_UPLOADS_BASE."';
        var sl_addons_base=sl_uploads_base+'".str_replace(SL_UPLOADS_BASE, '', SL_ADDONS_BASE)."';
        var sl_includes_base=sl_base+'".str_replace(SL_BASE, '', SL_INCLUDES_BASE)."';
		var sl_cache_base=sl_uploads_base+'".str_replace(SL_UPLOADS_BASE, '', SL_CACHE_BASE)."';
        var sl_pages_base=sl_base+'".str_replace(SL_BASE, '', SL_PAGES_BASE)."';
		var sl_images_base=sl_uploads_base+'".str_replace(SL_UPLOADS_BASE, '', SL_IMAGES_BASE)."'
		var sl_images_base_original=sl_base+'".str_replace(SL_BASE, '', SL_IMAGES_BASE_ORIGINAL)."'";
        $admin_js = preg_replace("@[\\\]@", "\\\\\\", $admin_js); //Windows-based server path backslash escape fix
	wp_add_inline_script("sl_func", $admin_js, 'before');
	
        if (preg_match("@add-locations\.php|locations\.php@", $_SERVER['REQUEST_URI'])) {
			if (!file_exists(SL_ADDONS_PATH."/point-click-add/point-click-add.js")) {
				$sens=(!empty($sl_vars['sensor']))? "sensor=".$sl_vars['sensor'] : "sensor=false" ;
				$lang_loc=(!empty($sl_vars['map_language']))? "&amp;language=".$sl_vars['map_language'] : "" ; 
				$region_loc=(!empty($sl_vars['map_region']))? "&amp;region=".$sl_vars['map_region'] : "" ;
				$key=(!empty($sl_vars['api_key']))? "&amp;key=".$sl_vars['api_key'] : "" ;
				//print "<script src='http://maps.googleapis.com/maps/api/js?{$sens}{$lang_loc}{$region_loc}{$key}' type='text/javascript'></script>\n";
			}
            /*if (file_exists(SL_ADDONS_PATH."/point-click-add/point-click-add.js")) {
				//$sens=(!empty($sl_vars['sensor']))? "sensor=".$sl_vars['sensor'] : "sensor=false" ;
				$sens=""; //- v3.84 - 11/25/15 - no longer required
				$char_enc='&amp;oe='.$sl_vars['map_character_encoding'];
				$google_map_domain=(!empty($sl_vars['google_map_domain']))? $sl_vars['google_map_domain'] : "maps.google.com";
				$api=sl_data('store_locator_api_key');
				print "<script src='https://$google_map_domain/maps?file=api&amp;v=2&amp;key=$api&amp;{$sens}{$char_enc}' type='text/javascript'></script>\n";
				print "<script src='".SL_ADDONS_BASE."/point-click-add/point-click-add.js'></script>\n";
			} */ #3.98.5 -- long-retired addon
        }
		if (function_exists('do_sl_hook')){do_sl_hook('sl_addon_admin_scripts');}
}

function sl_remove_conflicting_scripts(){
	if (preg_match("@".SL_DIR."@", $_SERVER['REQUEST_URI'])){
		wp_dequeue_script('ui-tabs'); //Firefox-only conflict with 'ui-tabs' (jquery.tabs.pack.js) from wp-shopping-cart
	}
}
add_action('admin_enqueue_scripts', 'sl_remove_conflicting_scripts');

function sl_add_admin_stylesheet() {
  global $sl_base;
  wp_enqueue_style("sl_admin_css", SL_CSS_BASE."/admin.css?v=".SL_VERSION);
  wp_enqueue_style("sl_pop_css", SL_CSS_BASE."/sl-pop.css?v=".SL_VERSION);
}
/*---------------------------------*/
function sl_set_query_defaults() {
	global $where, $o, $d, $sl_searchable_columns, $wpdb;
	$extra=""; $the_q="";  //var_dump($sl_searchable_columns); die();
	$the_q = (!empty($_GET['q']))? sanitize_text_field($_GET['q']) : $the_q;
	if (function_exists("do_sl_hook") && !empty($sl_searchable_columns) && !empty($_GET['q'])) {
		foreach ($sl_searchable_columns as $value) {
			$extra .= $wpdb->prepare(" OR $value LIKE '%%%s%%'", $the_q);
		}
	}
	
	$where=(!empty($_GET['q']))? $wpdb->prepare(" WHERE sl_store LIKE '%%%s%%' OR sl_address LIKE '%%%s%%' OR sl_city LIKE '%%%s%%' OR sl_state LIKE '%%%s%%' OR sl_zip LIKE '%%%s%%' OR sl_tags LIKE '%%%s%%'", $the_q, $the_q, $the_q, $the_q, $the_q, $the_q)." ".$extra : "" ; //die($where);
	//$where = (trim($where)!="")? $where." AND sl_private<>'1' " : " WHERE sl_private<>'1' ";
	$o=(!empty($_GET['o']))? sanitize_text_field($_GET['o']) : "sl_store";
	$d=(empty($_GET['d']) || $_GET['d']=="DESC")? "ASC" : "DESC";
}
if (!function_exists('set_query_defaults')){
	//2/26/19 - v3.98.4 - check
	function set_query_defaults() {sl_set_query_defaults();}
}
/*--------------------------------------------------------------*/
function sl_do_hyperlink(&$text, $target="'_blank'", $type="both"){
  if ($type=="both" || $type=="protocol") {	
   // match protocol://address/path/
   $text = preg_replace("@[a-zA-Z]+://([.]?[a-zA-Z0-9_/?&amp;%20,=\-\+\-\#])+@s", "<a href=\"\\0\" target=$target>\\0</a>", $text);
  }
  if ($type=="both" || $type=="noprotocol") {
   // match www.something
   $text = preg_replace("@(^| )(www([.]?[a-zA-Z0-9_/=-\+-\#])*)@s", "\\1<a href=\"http://\\2\" target=$target>\\2</a>", $text);
  }
  return $text;
}
if (!function_exists('do_hyperlink')){
	//12/4/18 6:19pm - v3.98.3
	function do_hyperlink(&$text, $target="'_blank'", $type="both") {
		 return sl_do_hyperlink($text, $target, $type);
	}
}
/*-------------------------------------------------------------*/
function sl_comma($a) {
	$a=str_replace('"', "&quot;", $a);
	$a=str_replace("'", "&#39;", $a);
	$a=str_replace(">", "&gt;", $a);
	$a=str_replace("<", "&lt;", $a);
	$a=str_replace(" & ", " &amp; ", $a);
	$a=str_replace("," ,"&#44;", $a);
	$a=sanitize_text_field($a); //v3.98.5 - 4/23/22 4:55p -- possibly redudant, but a WP requested update | ref: https://developer.wordpress.org/reference/functions/esc_attr/
	return $a;
}
if (!function_exists('comma')){
	//2/26/19 - v3.98.4
	function comma($a) {
		 return sl_comma($a);
	}
}
/*------------------------------------------------------------*/
function sl_addon_activation_message($url_of_upgrade="") {
	global $sl_dir, $text_domain;
	print "<div style='background-color:#eee; border:solid silver 1px; padding:7px; color:black; display:block;'>".__("You haven't activated this upgrade yet", "store-locator").". ";
	if (function_exists('do_sl_hook') && !preg_match("/addons\-platform/", $url_of_upgrade) ){
		print "<a href='".SL_ADDONS_PAGE."'>".__("Activate", "store-locator")."</a></div><br>";
	} else {
		print __("Go to pull-out Dashboard, and activate under 'Activation Keys' section.", "store-locator")."</div><br>";
	}
}
if (!function_exists('addon_activation_message')){
	//2/26/19 - v3.98.4
	function addon_activation_message($url_of_upgrade="") {
		global $sl_dir, $text_domain;
		sl_addon_activation_message($url_of_upgrade);
	}
}
/*-----------------------------------------------------------*/
function sl_url_test($url){
	if (!empty($url) && preg_match("@^https?://@i", $url)) {
		return TRUE; 
	} else {
		return FALSE; 
	}
}
if (!function_exists('url_test')){
	//2/26/19 - v3.98.4
	function url_test($url) {
		 return sl_url_test($url);
	}
}
/*---------------------------------------------------------------*/
function sl_neat_title($ttl,$separator="_") {
	/*$ttl=preg_replace("/@+/", "$separator", preg_replace("/[^[:alnum:]]/", "@", trim(preg_replace("/[^[:alnum:]]/", " ", str_replace("'", "", sl_truncate(trim(strtolower(html_entity_decode(str_replace("&#39;","'",$ttl)))), 100))))));
	return $ttl;*/
	
	//Now also converts foreign chars to un-accented equiv character - 11/7/14;

	$normalizeChars = array(
    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 
    'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T');
	$ttl = strtr($ttl, $normalizeChars);
	$ttl = html_entity_decode( str_replace("&#39;","'",$ttl) );
	
	$ttl = preg_replace("/@+/", "$separator", 
			preg_replace("/[^[:alnum:]]/", "@", 
				trim(
					preg_replace("/[^[:alnum:]]/", " ", 
						str_replace("'", "", 
							sl_truncate(
								trim(
									strtolower($ttl)
								), 
							100)
						)
					)
				)
			)
		);
	return $ttl;
}
/*-------------------------------*/
function sl_truncate($var,$length=50,$mode="return", $type=1) {
	
	if (strlen($var)>$length) {
		if ($type==1) { //avoids cutting words in half
			$var=substr($var,0,$length);
			$var=preg_replace("@[[:space:]]{1}.{1,10}$@s", "", $var); //making sure it doesn't cut word in half
			$var=$var."...";
		}
		elseif ($type==2) { //provides display "more" & "less" link
			$r_num=mt_rand();
			$r_num2=$r_num."_2";
			$var1=substr($var,0,$length);
			$var2=substr($var,$length, strlen($var)-$length);
			$var="<span id='$r_num'>$var1</span><span id='$r_num2' style='display:none'>".$var1.$var2."</span><a href='#' onclick=\"show('$r_num');show('$r_num2');this.innerHTML=(this.innerHTML.indexOf('more')!=-1)?'(...less)':'(more...)';return false;\">(more...)</a>";
		}
		elseif ($type==3) { //exact length truncation
			$var=substr($var,0,$length);
			$var=$var."...";
		}
	}
	if ($mode!="print") {
		return $var;
	}
	else {
		print $var;
	}
}
/*-----------------------------------------------------------*/
function sl_process_tags($tag_string, $db_action="insert", $sl_id="") {
	//v3.94 - commented out - originally meant for Categorizer tags filtering, but no longer needed - 2/22/16
	/*
	global $wpdb;
	$id_string="";
	
	if (!is_array($sl_id) && preg_match("@,@", $sl_id)) {
		$id_string=$sl_id;
		$sl_id=explode(",",$id_string);
		$rplc_arr=array_fill(0, count($sl_id), "%d"); //var_dump($rplc_arr); //die(); 
		$id_string=implode(",", array_map(array($wpdb, "prepare"), $rplc_arr, $sl_id)); 
	} elseif (is_array($sl_id)) {
		$rplc_arr=array_fill(0, count($sl_id), "%d"); //var_dump($rplc_arr); //die(); 
 		$id_string=implode(",", array_map(array($wpdb, "prepare"), $rplc_arr, $sl_id)); 
	} else {
		$id_string=$wpdb->prepare("%d", $sl_id); 
	}
	
	//creating array of tags 
	if (preg_match("@,@", $tag_string)) { 
		$tag_string=preg_replace('/[^A-Za-z0-9_\-, ]/', '', $tag_string); 
		$sl_tag_array=array_map('trim',explode(",",trim($tag_string))); 
		$sl_tag_array=array_map('strtolower', $sl_tag_array); 
	} else { 
		$tag_string=preg_replace('/[^A-Za-z0-9_\-, ]/', '', $tag_string); 
		$sl_tag_array[]=strtolower(trim($tag_string)); 
	} 
	
	if ($db_action=="insert") {
		$wpdb->query("DELETE FROM ".SL_TAG_TABLE." WHERE sl_id IN ($id_string)");  //clear current tags for locations being modified 
		//build insert query
		$query="INSERT INTO ".SL_TAG_TABLE." (sl_tag_slug, sl_id) VALUES ";
		if (!is_array($sl_id)) {
			$main_sl_id=($sl_id==="")? $wpdb->insert_id : $sl_id ; 
			foreach ($sl_tag_array as $value)  {
				if (trim($value)!="") {
					$query.="('$value', '$main_sl_id'),";
				}
			}
		} elseif (is_array($sl_id)) {
			foreach ($sl_id as $value2) {
				$main_sl_id=$value2;
				foreach ($sl_tag_array as $value)  {
					if (trim($value)!="") {
						$query.="('$value', '$main_sl_id'),";
					}
				}
			}
		}
		$query=substr($query, 0, strlen($query)-1); // remove last comma 
		//print($query);
	} elseif ($db_action=="delete") {
		if (trim($tag_string)==="") {
			$query="DELETE FROM ".SL_TAG_TABLE." WHERE sl_id IN ($id_string)";
		} else {
			$t_string=implode("','", $sl_tag_array); //die($t_string); 
			$query="DELETE FROM ".SL_TAG_TABLE." WHERE sl_id IN ($id_string) AND sl_tag_slug IN ('".trim($t_string)."')"; 
			//die($query."\n"); 
		}
	} 
	$wpdb->query($query);
	*/
}
/*-----------------------------------------------------------*/
function sl_admin_init_ty() {
	global $wpdb, $sl_vars;
	
	$sl_time_length = ((time() - strtotime($sl_vars["start"]))/60/60/24>=14)? "week" : "a few days";
	$sl_time_length = ((time() - strtotime($sl_vars["start"]))/60/60/24>=30)? "month" : $sl_time_length;
	$sl_time_length = ((time() - strtotime($sl_vars["start"]))/60/60/24>=90)? "three months" : $sl_time_length;
	$sl_time_length = ((time() - strtotime($sl_vars["start"]))/60/60/24>=183)? "six months" : $sl_time_length;
	$sl_time_length = ((time() - strtotime($sl_vars["start"]))/60/60/24>=365)? "year" : $sl_time_length;
	define("SL_THANKS_TIME_LENGTH", $sl_time_length);
	
	$sl_num_locs_query = $wpdb->get_var("SELECT COUNT(sl_id) from ".SL_DB_PREFIX."store_locator WHERE sl_latitude<>'' AND sl_latitude <>0 AND sl_latitude IS NOT NULL");
	
	$sl_num_locs = ($sl_num_locs_query >= 50)? "50": $sl_num_locs_query;
	$sl_num_locs = ($sl_num_locs_query >= 100)? "100": $sl_num_locs;
	$sl_num_locs = ($sl_num_locs_query >= 200)? "200": $sl_num_locs;
	$sl_num_locs = ($sl_num_locs_query >= 500)? "500": $sl_num_locs;
	$sl_num_locs = ($sl_num_locs_query >= 1000)? "1000": $sl_num_locs;
	define("SL_THANKS_NUM_LOCS", $sl_num_locs);
}
add_action('admin_init', 'sl_admin_init_ty');

function sl_ty($file){
global $sl_vars, $wpdb;
$ty['http'] = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://':'http://';
$ty['url']	= urlencode("http://locl.es/Lcatr"); //urlencode("http://locl.es/Lcatr"); - 9/9/15 - domain needs update
$ty['text'] = urlencode(__("Love it! I've made my site more user-friendly with", "store-locator")." LotsOfLocales #WP #WordPress #StoreLocator #GoogleMaps");
$ty['text2'] = urlencode(__("Great! I can now easily display my locations using", "store-locator")." LotsOfLocales #GoogleMaps #StoreLocator #WordPress #WP");
//$ty['is_included']=(basename($file) != basename($_SERVER['SCRIPT_FILENAME']) )? true : false;
$ty['is_included']=(empty($_GET['ajax']) || $_GET['ajax'] != 'true')? true : false; /*v3.98.3 | 12/4/18 4:44p -- needed update due to new URL structure of loading module (it would've always been true for $is_included)*/

if (!$ty['is_included']) {
	
	sl_admin_init_ty();
	
	$ty['thanks_msg'] = sprintf(__("Hey, noticed you've now successfully added %s+ locations over the past %s or more – fantastic!  Could you do a huge favor and give a rating on WordPress? It will keep our motivation high to continue providing more great features and updates for you.
<br><br>~ Viadat Creations", "store-locator")."<br><br>", SL_THANKS_NUM_LOCS, SL_THANKS_TIME_LENGTH);
	$ty['thanks_msg_style'] = "style='line-height:20px; font-familty:helvetica; text-align:left; font-size:15px'";
	$ty['thanks_heading'] = "<br>".__("My Views", "store-locator")."<br><br>";
	$ty['action_call'] =  __("Buttons to Spread the Word!", "store-locator");
	$ty['action_call_style'] = "style='font-size:20px; text-align:left; display:block;  font-family:Georgia;'";
	$ty['action_buttons_style'] = "style='text-align:left; padding-top:11px; padding-left:0px;font-weight:normal;font-size:15px'";
} else {
	$ty['thanks_msg'] = __("<b>Liking Store Locator? Share your voice:</b><br><a href='#' class='star_button'>Give my review now</a><br><br><b>Any problems? View:</b><br><a href='http://docs.viadat.com/' target='_blank'>Documentation</a> / <a href='http://www.viadat.com/contact/' target='_blank'>Contact us</a>", "store-locator")."<br><br>";
	$ty['thanks_msg_style'] = "";
	$ty['thanks_heading'] ="";
	$ty['action_call'] ="";
	$ty['action_call_style'] = "";
	$ty['action_buttons_style'] = "";
}
$ty['done_msg'] = __("I've already rated it", "store-locator");
$ty['noshow_msg'] = __("No, check with me later", "store-locator");
	return $ty;
}

function sl_foot_ty($text) {
	global $wpdb, $sl_vars;
	
	if (preg_match("@".SL_DIR."@", $_SERVER['REQUEST_URI']) && (empty($sl_vars['thanks']) || $sl_vars['thanks'] != "true") && mt_rand(1,2) == 2 && defined("SL_THANKS_TIME_LENGTH") && SL_THANKS_TIME_LENGTH != "a few days" && SL_THANKS_NUM_LOCS >= 100) {
		$text = sprintf(__("You've successfully added %s+ locations!  Could you do a huge favor and %sgive a WP rating%s?", "store-locator"), SL_THANKS_NUM_LOCS, "<a href='https://wordpress.org/support/view/plugin-reviews/store-locator?filter=5#postform' target='_blank'>", "</a>");
		//$text = "<div class='sl_admin_success' style='width:55%; position:relative; top:35px;'>".$text."</div>";
		$text = "<i>$text</i>";
	}
	//$text = SL_THANKS_TIME_LENGTH ."  " . SL_THANKS_NUM_LOCS;
	return $text;
}
add_filter( 'admin_footer_text', 'sl_foot_ty', 1 );
/*-----------------------------------------------------------*/
function sl_prepare_tag_string($sl_tags) {
	//$sl_tags=preg_replace('/[ ]*\&\#44\;[ ]*/', ', ', $sl_tags); 
	$sl_tags=preg_replace('/\,+/', ', ', $sl_tags); 
	$sl_tags=preg_replace('/(&#44;)+/', ', ', $sl_tags); 
	$sl_tags=preg_replace('/[^A-Za-z0-9_\-,]/', '', $sl_tags); 
	if (substr($sl_tags, 0, 1) == ",") {
		$sl_tags=substr($sl_tags, 1, strlen($sl_tags));
	}
	if (substr($sl_tags, strlen($sl_tags)-1, 1) != "," && trim($sl_tags)!="") {
		$sl_tags.=",";
	}
	$sl_tags=preg_replace('/\,+/', ', ', $sl_tags);
	$sl_tags=preg_replace('/(&#44;)+/', ', ', $sl_tags);
	$sl_tags=preg_replace('/[ ]*,[ ]*/', ', ', $sl_tags); 
 	//$sl_tags=preg_replace('/[ ]*,[ ]*/', ', ', $sl_tags); 
	$sl_tags=trim($sl_tags);
	return $sl_tags;
}
/*-----------------------------------------------------------*/
function sl_data($setting_name, $i_u_d_s="select", $setting_value="") {
	global $wpdb;
	//$$addon_slug[trim($setting[0])] = trim($setting[1]);
	if ($i_u_d_s == "insert" || $i_u_d_s == "add" || $i_u_d_s == "update") {
		//$setting = explode("=", $setting);
		$setting_value = (is_array($setting_value))? serialize($setting_value) : $setting_value;
		$exists = $wpdb->get_var($wpdb->prepare("SELECT sl_setting_id FROM ".SL_SETTING_TABLE." WHERE sl_setting_name = %s", $setting_name));
		if (!$exists) {	
			$q = $wpdb->prepare("INSERT INTO ".SL_SETTING_TABLE." (sl_setting_name, sl_setting_value) VALUES (%s, %s)", $setting_name, $setting_value); 
		} else { 
			$q = $wpdb->prepare("UPDATE ".SL_SETTING_TABLE." SET sl_setting_value = %s WHERE sl_setting_name = %s", $setting_value, $setting_name);
		}
		$wpdb->query($q);
	} elseif ($i_u_d_s == "delete") {
		$q = $wpdb->prepare("DELETE FROM ".SL_SETTING_TABLE." WHERE sl_setting_name = %s", $setting_name);
		$wpdb->query($q);
	} elseif ($i_u_d_s == "select" || $i_u_d_s == "get") {
		$q = $wpdb->prepare("SELECT sl_setting_value FROM ".SL_SETTING_TABLE." WHERE sl_setting_name = %s", $setting_name);
		$r = $wpdb->get_var($q);
		$r = (@unserialize($r) !== false || $r === 'b:0;')? unserialize($r) : $r;  //checking if stored in serialized form
		/*if (function_exists("apply_filters")) {
			return apply_filters( 'option_' . $setting_name, $r);  //Compability for WPML or any plugin that uses option_(option_name) hooks
		} else {*/
			return $r;
		//}
	}
}
/*----------------------------------------------------------------*/
function sl_md_output($output_zone) {
	include(SL_INCLUDES_PATH."/mapdesigner-options.php");
	
	$GLOBALS['output_zone_type'] = $output_zone;
	$output_arr = array_filter($sl_mdo, "filter_sl_mdo");
 	unset($GLOBALS['output_zone_type']);
	unset($sl_mdo);
	
	if ($output_zone == 'sl_dyn_js') {
		foreach ($output_arr as $value) {
			if (!is_array($value['output_zone'])) {
				$the_field = $value['field_name'];
				$$the_field = (trim($sl_vars[$the_field]) != "")? sl_parseToXML($sl_vars[$the_field]) : $value['default'];
			} else {
				$position_arr = array_keys($value['output_zone'], $output_zone); //array position of this sl_dyn_js output_zone, if an array
				foreach ($position_arr as $pos_value) {
					$the_field = $value['field_name'][$pos_value];
					$$the_field = (trim($sl_vars[$the_field]) != "")? sl_parseToXML($sl_vars[$the_field]) : $value['default'][$pos_value];
				}
			}
		}
	}
}
function sl_dyn_js($post_content=""){
	global $sl_dir, $sl_base, $sl_uploads_base, $sl_path, $sl_uploads_path, $wpdb, $sl_version, $pagename, $sl_map_language, $post, $sl_vars;
	print "<script type=\"text/javascript\">\n/*<![CDATA[*/\nvar sl_siteurl='".SL_SITEURL."';\nvar sl_base='".SL_BASE."';\n".$sl_vars['jfxn'];
	if (!empty($_GET['admin']) && $_GET['admin']==1){ print "\n/*]]>*/\n</script>\n"; return; }

	include(SL_INCLUDES_PATH."/mapdesigner-options.php");
	
	$GLOBALS['output_zone_type'] = 'sl_dyn_js';
	$output_arr = array_filter($sl_mdo, "filter_sl_mdo");
 	unset($GLOBALS['output_zone_type']);
	unset($sl_mdo);
	
	//var_dump($output_arr); die();
	
	foreach ($output_arr as $value) {
	    if (isset($value['output_zone'])) {
		if (!is_array($value['output_zone'])) {
			$the_field = $value['field_name'];
			$$the_field = (trim($sl_vars[$the_field]) != "")? $sl_vars[$the_field] : $value['default'];
		 	//print "//Field: ".$the_field;
		 	//print " | label?: ".preg_match("@\_label$@", $the_field);
		 	//print " | message?: ".preg_match("@\_message$@", $the_field)."\n";
			 if (preg_match("@\_label$@", $the_field)) {
			 	$$the_field = addslashes($$the_field); //originally parseToXML(); now stripslashes is applied in MD (since v3.56.2)
			 } elseif (preg_match("@\_message$@", $the_field)) {
			 	$$the_field = addslashes($$the_field);
			 }
		} else {
			$position_arr = array_keys($value['output_zone'], 'sl_dyn_js'); //array position of this sl_dyn_js output_zone, if an array
			foreach ($position_arr as $pos_value) {
				$the_field = $value['field_name'][$pos_value];
				$$the_field = (trim($sl_vars[$the_field]) != "")? $sl_vars[$the_field] : $value['default'][$pos_value];
				if (preg_match("@\_label$@", $the_field)) {
				 	$$the_field = addslashes($$the_field);
				} elseif (preg_match("@\_message$@", $the_field)) {
				 	$$the_field = addslashes($$the_field);
				}
			}
		}
	    }
	}

	## MapDesigner header row inputs
	$gmc=(trim($sl_vars['google_map_country'])!="")? sl_parseToXML($sl_vars['google_map_country']) : "United States" ;
	$gmd=(trim($sl_vars['google_map_domain'])!="")? $sl_vars['google_map_domain'] : "maps.google.com" ;
	

	//WPML Display Integration
	//if (function_exists('icl_t')) {
		ob_start();
		include(SL_INCLUDES_PATH."/mapdesigner-options.php");
		ob_end_clean(); //elimating any output that could disrupt dynamic js
		$GLOBALS['input_zone_type'] = 'labels';
		$GLOBALS['output_zone_type'] = 'sl_dyn_js';
		
		$labels_arr = array_filter($sl_mdo, "filter_sl_mdo");
		unset($GLOBALS['input_zone_type']); unset($GLOBALS['output_zone_type']);
		unset($sl_mdo);
		//var_dump($labels_arr); die();
		
		foreach ($labels_arr as $value) {
			$the_field = $value['field_name'];
			$$the_field = addslashes(apply_filters("wpml_translate_single_string", $$the_field, SL_DIR, $value['label']));
	}
	//}
	//End WPML
		
	//Polylang Display Integration
	if (function_exists('pll__')) { 
		//ob_start();
		//include(SL_INCLUDES_PATH."/mapdesigner-options.php");
		//ob_end_clean(); //elimating any output that could disrupt dynamic js
		//$GLOBALS['input_zone_type'] = 'labels';
		//$GLOBALS['output_zone_type'] = 'sl_dyn_js';
		
		//$labels_arr = array_filter($sl_mdo, "filter_sl_mdo");
		//unset($GLOBALS['input_zone_type']); unset($GLOBALS['output_zone_type']);
		//unset($sl_mdo);
		//var_dump($labels_arr); die();
		
		foreach ($labels_arr as $value) {
			$the_field = $value['field_name'];
			$$the_field = addslashes(pll__($$the_field));
		}
	}
	//End Polylang
		
print  
"\nvar sl_base='".SL_BASE."';
var sl_uploads_base='".SL_UPLOADS_BASE."';
var sl_addons_base=sl_uploads_base+'".str_replace(SL_UPLOADS_BASE, '', SL_ADDONS_BASE)."';
var sl_includes_base=sl_base+'".str_replace(SL_BASE, '', SL_INCLUDES_BASE)."';
var sl_google_map_country='".esc_attr($gmc)."'; 
var sl_google_map_domain='".esc_attr($gmd)."';\n";

$icon_array = array('icon' => 'map_home_icon', 'icon2' => 'map_end_icon');
$without_quotes = array('map_type', 'zoom_level'); //Google map type, zoom level can't have quotes around theme

foreach ($output_arr as $value) {
	if (isset($value['output_zone'])) {
		if (!is_array($value['output_zone'])) {
			$the_field = $value['field_name'];
			$$the_field = (!in_array($the_field, $without_quotes))? "'{$$the_field}'" : $$the_field ;
			if ( in_array($the_field, array_keys($icon_array)) ) { //if-else needed due to inconsistency between 'icon'/'map_home_icon', etc labels
				$the_field_converted = $icon_array[$the_field];  
				print "var sl_{$the_field_converted}=". $$the_field .";\n";
			} elseif (is_array($$the_field) && $the_field == "map_type") { #v3.98.9 - 12/29/23 - new clause since 'map_type' var is an array
				//var_dump($$the_field);
				print "var sl_{$the_field}=". $sl_vars[$the_field] .";\n";
			} else {
				print "var sl_{$the_field}=". $$the_field .";\n";
			}
		} else {
			$position_arr = array_keys($value['output_zone'], 'sl_dyn_js'); //array position of this sl_dyn_js output_zone, if an array
			foreach ($position_arr as $pos_value) {
				$the_field = $value['field_name'][$pos_value];
				$$the_field = (!in_array($the_field, $without_quotes))? "'{$$the_field}'" : $$the_field ;
				if ( in_array($the_field, array_keys($icon_array)) ) { 
					$the_field_converted = $icon_array[$the_field];  
					print "var sl_{$the_field_converted}=". $$the_field .";\n";
				} else {
					print "var sl_{$the_field}=". $$the_field .";\n";
				}
			}
		}
	}
}
	
	print ((!function_exists("do_sl_hook"))?$sl_vars['jsl']:"")."\n/*]]>*/\n</script>\n";
	if (function_exists("do_sl_hook")){do_sl_hook('sl_addon_head_scripts'); }
	if (function_exists("do_sl_hook")){ 
		print "<script>\n/*<![CDATA[*/\n";
		sl_js_hooks();
		print "\n".$sl_vars['jsl']."\n/*]]>*/\n</script>\n";
	}
}

function sl_js_out($buff) {
	preg_match("@\/\*sl\-dyn\-js\-start\*\/.*\/\*sl\-dyn\-js\-end\*\/@s", $buff, $the_js);
	$the_js[0]=preg_replace("@<script([^>]*)?src=('|\")?([A-Za-z0-9\.\ \_\:\/-]*)('|\")?([^>]*)?>(\r)?(\n)?@s", "jQuery.getScript(\"\\3\");\n", $the_js[0]);
	$the_js[0]=preg_replace("@<\/script>(\r)?(\n)?@s", "", $the_js[0]);
	$the_js[0]=preg_replace("@<script[^>]*>(\r)?(\n)?@s", "", $the_js[0]);
	$the_js[0]=preg_replace("@\/\*[^(\*\/)]*\*\/@s", "", $the_js[0]);
	//$the_js[0]=preg_replace("@[^http(s)?:]\/\/[^(\r|\n)]*@s", "", $the_js[0]);
	foreach (token_get_all($the_js[0]) as $token ) {
   			if ($token[0] != T_COMMENT || preg_match("@\/\/[A-Za-z]+[ ]*\)@",$token[1]) ) {
   				//not a comment or not a js regex match with modifier using '//i', for example
        			continue;
			 }
			 //print_r($token);
   			 $the_js[0] = str_replace($token[1], '', $the_js[0]);
   	}
   	$the_js[0]=str_replace(")\n", ");\n", $the_js[0]);
	$the_js[0]=(!empty($_GET['debug']) && $_GET['debug']==1)? $the_js[0] : preg_replace("@\r|\n|\t|[[:space:]]{2,}@s","",$the_js[0]); #v3.98.9: empty clause; #v3.98.9 - 12/29/23 reversed empty clause to check that debug is not empty + '&&' operator, instead of whether debug was empty + '||' operator -- helped avoid 'debug undefined' warning msgs 
	$the_js[0]=preg_replace("@\(\)([a-z])@s","();\\1",$the_js[0]);
	$the_js[0]=preg_replace("@\<\?php@", "", $the_js[0]);
	$the_js[0]=preg_replace("@\?\>@", "", $the_js[0]);
	return $the_js[0];
}
if (!function_exists('js_out')){
	//2/26/19 - v3.98.4
	function js_out($buff) {
		 return sl_js_out($buff);
	}
}
/*---------------------------------------*/
function sl_location_form($mode="add", $pre_html="", $post_html=""){
	$html="<form name='manualAddForm' method='post'>
	$pre_html
	<table cellpadding='0' class='widefat'>
	<thead><tr><th>".__("Type&nbsp;Address", "store-locator")."</th></tr></thead>
	<tr>
		<td>
		<div style='display:inline; width:50%'>
		<b>".__("The General Address Format", "store-locator").": </b>(<a href=\"#\" onclick=\"show('format'); return false;\">".__("show/hide", "store-locator")."</a>)
		<span id='format' style='display:none'><br><i>".__("Name of Location", "store-locator")."<br>
		".__("Address (Street - Line1)", "store-locator")."<br>
		".__("Address (Street - Line2 - optional)", "store-locator")."<br>
		".__("City, State Zip", "store-locator")."</i></span><br><hr>
		".__("Name of Location", "store-locator")."<br><input name='sl_store' size=45><br><br>
		".__("Address", "store-locator")."<br><input name='sl_address' size=21>&nbsp;<small>(".__("Street - Line1", "store-locator").")</small><br>
		<input name='sl_address2' size=21>&nbsp;<small>(".__("Street - Line 2 - optional", "store-locator").")</small><br>
		<table cellpadding='0px' cellspacing='0px' style='width:200px'><tr><td style='padding-left:0px' class='nobottom'><input name='sl_city' size='21'><br><small>".__("City", "store-locator")."</small></td>
		<td><input name='sl_state' size='7'><br><small>".__("State", "store-locator")."</small></td>
		<td><input name='sl_zip' size='10'><br><small>".__("Zip", "store-locator")."</small></td></tr></table><br>
		</div><div style='display:inline; width:50%'>
		".__("Additional Information", "store-locator")."<br>
		<textarea name='sl_description' rows='5' cols='18'></textarea>&nbsp;&nbsp;<small>".__("Description", "store-locator")."</small><br>
		<input name='sl_tags'>&nbsp;<small>".__("Tags (seperate with commas)", "store-locator")."</small><br>		
		<input name='sl_url'>&nbsp;<small>".__("URL", "store-locator")."</small><br>
		<textarea name='sl_hours' rows='1' cols='18'></textarea>&nbsp;&nbsp;<small>".__("Hours", "store-locator")."</small><br>
		<input name='sl_phone'>&nbsp;<small>".__("Phone", "store-locator")."</small><br>
		<input name='sl_fax'>&nbsp;<small>".__("Fax", "store-locator")."</small><br>
		<input name='sl_email'>&nbsp;<small>".__("Email", "store-locator")."</small><br>
		<input name='sl_image'>&nbsp;<small>".__("Image URL (shown with location)", "store-locator")."</small>";
		
		$html.=(function_exists("do_sl_hook"))? do_sl_hook("sl_add_location_fields",  "append-return") : "" ;
		$html.=wp_nonce_field("add-location_single", "_wpnonce", true, false);
		$html.="<br><br>
	<input type='submit' value='".__("Add Location", "store-locator")."' class='button-primary'>
	</div>
	</td>
		</tr>
	</table>
	$post_html
</form>";
	return $html;
}
function sl_add_location() {
	global $wpdb;
	$fieldList=""; $valueList="";
	foreach ($_POST as $key=>$value) {
		if (preg_match("@sl_@", $key)) {
			if ($key=="sl_tags") {
				$value=sl_prepare_tag_string($value);
			}
			$fieldList.="$key,";
			
			if (is_array($value)){
				$value=serialize($value); //for arrays being submitted
				$valueList.="'$value',";
				//$field_value_str.=$key."='$value',";
			} else {
				$valueList.=$wpdb->prepare("%s", sl_comma(stripslashes($value))).",";
				//$field_value_str.=$key."=".$wpdb->prepare("%s", trim(sl_comma(stripslashes($value)))).", "; 
			}
		}
	}
	$fieldList=substr($fieldList, 0, strlen($fieldList)-1);
	$valueList=substr($valueList, 0, strlen($valueList)-1);
	$wpdb->query("INSERT INTO ".SL_TABLE." ($fieldList) VALUES ($valueList)");
	$new_loc_id=$wpdb->insert_id;
	$address=sanitize_text_field("$_POST[sl_address], $_POST[sl_address2], $_POST[sl_city], $_POST[sl_state] $_POST[sl_zip]");
	sl_do_geocoding($address);
	if (!empty($_POST['sl_tags'])){
		sl_process_tags(sanitize_text_field($_POST['sl_tags']), "insert", $new_loc_id);
	}
}
/*--------------------------------------------------*/
function sl_define_db_tables() {
	//since it can't use sl_data() in the sl-define.php, placed here
	//$sl_db_prefix = get_option('sl_db_prefix'); 
	global $wpdb; 
	$sl_db_prefix = $wpdb->prefix; //better this way, in case prefix changes vs storing option - 1/29/15
	if (!defined('SL_DB_PREFIX')){ define('SL_DB_PREFIX', $sl_db_prefix); }
	if (!empty($sl_db_prefix)) {
		if (!defined('SL_TABLE')){ define('SL_TABLE', SL_DB_PREFIX."store_locator"); }
		if (!defined('SL_TAG_TABLE')){ define('SL_TAG_TABLE', SL_DB_PREFIX."sl_tag"); }
		if (!defined('SL_SETTING_TABLE')){ define('SL_SETTING_TABLE', SL_DB_PREFIX."sl_setting"); }
	}
}
sl_define_db_tables(); 
/*----------------------------------------------------*/
function sl_single_location_info($value, $colspan, $bgcol_class) {
	global $sl_hooks;
	$_GET['edit'] = $value['sl_id']; //die("edit: ".var_dump($_GET)); die();
	
	print "<tr class='$bgcol_class' id='sl_tr_data-$value[sl_id]'>";
	
	print "<td colspan='$colspan'><form name='manualAddForm' method='post'>
	<a name='a$value[sl_id]'></a>
	<table cellpadding='0' class='manual_update_table'>
	<tr>
		<td style='vertical-align:top !important; width:20%'><b>".__("Name of Location", "store-locator")."</b><br><input name='sl_store-$value[sl_id]' id='sl_store-$value[sl_id]' value='$value[sl_store]' size=30><br><br>
		<b>".__("Address", "store-locator")."</b><br><input name='sl_address-$value[sl_id]' id='sl_address-$value[sl_id]' value='$value[sl_address]' size='13'>&nbsp;<small>(".__("Street - Line1", "store-locator").")</small><br>
		<input name='sl_address2-$value[sl_id]' id='sl_address2-$value[sl_id]' value='$value[sl_address2]' size='13'>&nbsp;<small>(".__("Street - Line 2 - optional", "store-locator").")</small><br>
		<table cellpadding='0px' cellspacing='0px' style='width:200px'><tr><td style='padding-left:0px' class='nobottom'><input name='sl_city-$value[sl_id]' id='sl_city-$value[sl_id]' value='$value[sl_city]' size='13'><br><small>".__("City", "store-locator")."</small></td>
		<td><input name='sl_state-$value[sl_id]' id='sl_state-$value[sl_id]' value='$value[sl_state]' size='4'><br><small>".__("State", "store-locator")."</small></td>
		<td><input name='sl_zip-$value[sl_id]' id='sl_zip-$value[sl_id]' value='$value[sl_zip]' size='6'><br><small>".__("Zip", "store-locator")."</small></td></tr></table>";
		
		if (function_exists("do_sl_hook")) {
			sl_show_custom_fields();
		}
		
		$cancel_onclick = "location.href=\"".wp_sanitize_redirect(str_replace("&edit=$_GET[edit]", "",$_SERVER['REQUEST_URI']))."\"";
		print "<br><br>
		<nobr><input type='submit' value='".__("Update", "store-locator")."' class='button-primary'><input type='button' class='button' value='".__("Cancel", "store-locator")."' onclick='$cancel_onclick'></nobr>
		</td><td style='width:40%; vertical-align:top !important;'>
		<b>".__("Additional Information", "store-locator")."</b><br>
		<textarea name='sl_description-$value[sl_id]' id='sl_description-$value[sl_id]' rows='5' cols='18'>$value[sl_description]</textarea>&nbsp;&nbsp;<small>".__("Description", "store-locator")."</small><br>
		<input name='sl_tags-$value[sl_id]' id='sl_tags-$value[sl_id]' value='$value[sl_tags]' >&nbsp;<small>".__("Tags (seperate with commas)", "store-locator")."</small><br>		
		<input name='sl_url-$value[sl_id]' id='sl_url-$value[sl_id]' value='$value[sl_url]' >&nbsp;<small>".__("URL", "store-locator")."</small><br>
		<textarea name='sl_hours-$value[sl_id]' id='sl_hours-$value[sl_id]' rows='1' cols='18'>$value[sl_hours]</textarea>&nbsp;&nbsp;<small>".__("Hours", "store-locator")."</small><br>
		<input name='sl_phone-$value[sl_id]' id='sl_phone-$value[sl_id]' value='$value[sl_phone]' >&nbsp;<small>".__("Phone", "store-locator")."</small><br>
		<input name='sl_fax-$value[sl_id]' id='sl_fax-$value[sl_id]' value='$value[sl_fax]' >&nbsp;<small>".__("Fax", "store-locator")."</small><br>
		<input name='sl_email-$value[sl_id]' id='sl_email-$value[sl_id]' value='$value[sl_email]' >&nbsp;<small>".__("Email", "store-locator")."</small><br>
		<input name='sl_image-$value[sl_id]' id='sl_image-$value[sl_id]' value='$value[sl_image]' >&nbsp;<small>".__("Image URL (shown with location)", "store-locator")."</small>";
		
		print "</td><td style='vertical-align:top !important; width:40%'>";
	if (function_exists("do_sl_hook")) {do_sl_hook("sl_single_location_edit", "select-top");}
	print "</td></tr>
	</table>
</form></td>";

print "</tr>";
	}
/*-------------------------------------------*/
function sl_module($mod_name, $mod_heading="", $height="") {
	global $sl_vars, $wpdb;
	if (file_exists(SL_INCLUDES_PATH."/module-{$mod_name}.php")) {
		$css=(!empty($height))? "height:$height;" : "" ;
		print "<table class='widefat' style='background-color:transparent; border:0px; padding:4px; {$css}'>";
		if ($mod_heading){
			print "<thead><tr><th style='font-weight:bold; height:22px;'>$mod_heading</th></tr></thead>";
		}
		print "<tbody style='background-color:transparent; border:0px;'><tr><td style='background-color:transparent; border:0px;'>";
		include(SL_INCLUDES_PATH."/module-{$mod_name}.php");
		print "</td></tr></tbody></table><br>";
	}
}
/*--------------------------------------------*/
function sl_env_var_filt($arr_val) { 
	return !is_array($arr_val) && !(preg_match("@time|start|function|hide|weeks?|months?|".SL_VERSION."@", $arr_val) || preg_match("/^(((\d{4})(-)(0[13578]|10|12)(-)(0[1-9]|[12][0-9]|3[01]))|((\d{4})(-)(0[469]|1‌​1)(-)([0][1-9]|[12][0-9]|30))|((\d{4})(-)(02)(-)(0[1-9]|1[0-9]|2[0-8]))|(([02468]‌​[048]00)(-)(02)(-)(29))|(([13579][26]00)(-)(02)(-)(29))|(([0-9][0-9][0][48])(-)(0‌​2)(-)(29))|(([0-9][0-9][2468][048])(-)(02)(-)(29))|(([0-9][0-9][13579][26])(-)(02‌​)(-)(29)))(\s([0-1][0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9]))$/", $arr_val)); 
	//4/15/17 - added "!is_array($array_val)" clause since storing wp api json array data, but preg_match doesn't take arrays
	}

function sl_readme_parse($path_to_readme, $path_to_env=""){
	//include($path_to_env);
//print "<span style='font-size:14px; font-family:Helvetica'>";
ob_start();
include($path_to_readme);
$txt=ob_get_contents();
ob_clean();

//TOC pt.1
$toc=$txt;
	preg_match_all("@\=\=\=[ ]([^\=\=\=]+)[ ]\=\=\=@", $toc, $toc_match_0);
	preg_match_all("@\=\=[ ]([^\=\=]+)[ ]\=\=@", $toc, $toc_match_1); //var_dump($toc_match_1); die();
	preg_match_all("@\=[ ]([^\=]+)[ ]\=@", $toc, $toc_match_2); //var_dump($toc_match_2); die();
	$toc_cont="";
	foreach ($toc_match_2[1] as $heading) {
	    if (!in_array($heading, $toc_match_1[1]) && !in_array($heading, $toc_match_0[1]) && !preg_match("@^[0-9]+\.[0-9]+@", $heading)) {
		$toc_cont.="<li style='margin-left:30px; list-style-type:circle'><a href='#".sl_comma($heading)."' style='text-decoration:none'>$heading</a></li></li>";
	    } elseif (!in_array($heading, $toc_match_0[1]) && !preg_match("@^[0-9]+\.[0-9]+@", $heading)) { 
	    //!preg_match("@^[0-9]+\.[0-9]+@", $heading) prevents changelog numbers from showing up
	    	$toc_cont.="<li style='margin-left:15px; list-style-type:disc'><b><a href='#".sl_comma($heading)."' style='text-decoration:none'>$heading</a></b></li>";
	    }
	}

//parsing
$th_start="<th style='font-size:125%; font-weight:bold;'>";
$h2_start="<h2 style='font-family:Georgia; margin-bottom:0.05em;'>";
$h3_start="<h3 style='font-family:Georgia; margin-bottom:0.05em; margin-top:0.3em'>";
$txt=str_replace("=== ", "$h2_start", $txt);
$txt=str_replace(" ===", "</h2>", $txt);
//$txt=str_replace("== ", "<div id='wphead' style='color:black; background: -moz-linear-gradient(center bottom, #D7D7D7, #E4E4E4) repeat scroll 0 0 transparent'><h1 id='site-heading'><span id='site-title'>", $txt);
$txt=str_replace("== ", "<table class='widefat' ><thead>$th_start", $txt);
$txt=str_replace(" ==", "</th></thead></table><!--a style='float:right' href='#readme_toc'>Table of Contents</a-->", $txt);
$txt=str_replace("= ", "$h3_start", $txt);
$txt=str_replace(" =", "</h3><a style='float:right; position:relative; top:-1.5em; font-size:10px' href='#readme_toc'>".__("table of contents", "store-locator")."</a>", $txt);
$txt=preg_replace("@Tags:[ ]?[^\r\n]+\r\n@", "", $txt);

//TOC pt. 2
$txt=str_replace("</h2>", "</h2><a name='readme_toc'></a><div style='float:right;  width:500px; border-radius:1em; border:solid silver 1px; padding:7px; padding-top:0px; margin:10px; margin-right:0px;'><h3>".__("Table of Contents", "store-locator")."</h2>$toc_cont</div>", $txt);
$txt=preg_replace_callback("@$h2_start<u>([^<.]*)</u></h1>@s", function($matches) { 
	return "<h2 style='font-family:Georgia; margin-bottom:0.05em;'><a name='".sl_comma($matches[1])."'></a>$matches[1]</u></h1>"; }, $txt);
$txt=preg_replace_callback("@$th_start([^<.]*)</th>@s", function($matches) {
	return "<th style='font-size:125%; font-weight:bold;'><a name='".sl_comma($matches[1])."'></a>$matches[1]</th>"; }, $txt);
$txt=preg_replace_callback("@$h3_start( )*([^<.]*)( )*</h3>@s", function($matches) {
	return "<h3 style='font-family:Georgia; margin-bottom:0.05em; margin-top:0.3em'><a name=\"".sl_comma($matches[2])."\"></a>{$matches[1]}$matches[2]</h3>"; }, $txt);

//creating hyperlinks on top of labeled URLs (ones preceded w/a label in brackets)
$txt=preg_replace("@\[([a-zA-Z0-9_/?&amp;\&\ \.%20,=\-\+\-\']+)*\]\(([a-zA-Z]+://)(([.]?[a-zA-Z0-9_/?&amp;%20,=\-\+\-\#]+)*)\)@s", "<a onclick=\"window.parent.open('\\2'+'\\3');return false;\" href=\"#\">\\1</a>", $txt);

//converting asterisked lines into HTML list items
/*$txt=preg_replace("@\*[ ]?[ ]?([a-zA-Z0-9_/?&amp;\&\ \.%20,=\-\+\-\(\)\{\}\`\'\<\>\"\#\:]+)*(\r\n)?@s", "<li style='margin-left:15px; margin-bottom:0px;'>\\1</li>", $txt);*/
$txt=preg_replace("@\*[ ]?[ ]?([^\r\n]+)*(\r\n)?@s", "<li style='margin-left:15px; margin-bottom:0px;'>\\1</li>", $txt);

//additional formatting
$txt=preg_replace("@`([^`]+)*`@", "<strong class='sl_code code' style='padding:2px; border:0px'>\\1</strong>", $txt);
$txt=preg_replace("@__([^__]+)__@", "<strong>\\1</strong>", $txt);
$txt=preg_replace("@\r\n([0-9]\.)@", "\r\n&nbsp;&nbsp;&nbsp;\\1", $txt);
$txt=preg_replace("@([A-Za-z-0-9\/\\&;# ]+): @", "<strong>\\1: </strong>", $txt);

//$txt=preg_replace("@\[(.*)\]\(([a-zA-Z]+\://[.]?[a-zA-Z0-9_/?&amp;%20,=-\+-])*\)@s", "<a href=\"\\2\" target=_blank>\\1</a>", $txt);

//creating hyperlinks out of text URLs (which have 'http:' in the front)
$txt=sl_do_hyperlink($txt, "'_blank'", "protocol");

print nl2br($txt);
//print "</span>";

}
/*---------------------------------------------------------------*/
function sl_translate_stamp($dateVar="",$mode="return", $date_only=0, $abbreviate_month=0) {
if ($dateVar!="") {
		$mm=substr($dateVar,4,2);
		$dd=substr($dateVar,6,2);
		if ($dd<10) {$dd=str_replace("0","",$dd); } 		$yyyy=substr($dateVar,0,4);
		if (strlen($yyyy)==2 && $yyyy>=50) {
			$yyyy="19".$yyyy;
		}
		elseif (strlen($yyyy)==2 && $yyyy>=00 && $yyyy<50) {
			$yyyy="20".$yyyy;
		}
}
$months=array("January","February","March","April","May","June","July","August","September","October","November","December");
$dt="";
if (!empty($mm)) {
	$dt=$months[$mm-1];
	
	if ($abbreviate_month!=0) 
		$dt=substr($dt,0,3).".";
	
	if ($dd!="" && $yyyy!="")
		$dt.=" $dd, $yyyy";
}

if ($date_only==0) {

$hr=substr($dateVar,8,2);
$min=substr($dateVar,10,2);
$sec=substr($dateVar,12,2);

if ($hr<12) {$hr=str_replace("0","",$hr); }
if ($hr>12) {$hr=$hr-12; $suffix="pm";} else {$suffix="am";}
if ($hr==12) {$suffix="pm";}
if ($hr==0) {$hr=12;}

$dt.=" $hr:$min:$sec $suffix";

}

if ($mode!="print")
	return $dt;
elseif ($mode=="print")
	print $dt;

}
/*---------------------------------------------------------------*/
function sl_translate_date($dateVar="",$mode="return") {
if ($dateVar!="") {
		$parts=explode("/",$dateVar);
		$mm=trim($parts[0]);
		$dd=trim($parts[1]);
		if ($dd<10) {$dd=str_replace("0","",$dd); } 		$yyyy=trim($parts[2]);
		if (strlen($yyyy)==2 && $yyyy>=50) {
			$yyyy="19".$yyyy;
		}
		elseif (strlen($yyyy)==2 && $yyyy>=00 && $yyyy<50) {
			$yyyy="20".$yyyy;
		}
}
$months=array("January","February","March","April","May","June","July","August","September","October","November","December");

if ($mm!="") {
	$dt=$months[$mm-1];
	
	if ($dd!="" && $yyyy!="")
		$dt.="&nbsp;$dd,&nbsp;$yyyy";
}

if ($mode=="return")
	return $dt;
elseif ($mode=="print")
	print $dt;

}
/*-----------------------------------------------*/
add_action('admin_bar_menu', 'sl_admin_toolbar', 183);
function sl_admin_toolbar($admin_bar){
	if (!current_user_can("create_users")) { //limiting viewing of admin toolbar to admins - v3.87
		return;
	}
	
	$sl_admin_toolbar_array[] = array(
		'id'    => 'sl-menu',
		'title' => __('Store Locator', "store-locator"),
		'href'  => preg_replace('@wp-admin\/[^\.]+\.php|index\.php@', 'wp-admin/admin.php', SL_INFORMATION_PAGE),	
		'meta'  => array(
			'title' => 'LotsOfLocales&trade; - WordPress Store Locator',			
		),
	);
	$sl_admin_toolbar_array[] = array(
		'id'    => 'sl-menu-news-upgrades',
		'parent' => 'sl-menu',
		'title' => __('News & Upgrades', "store-locator"),
		'href'  => preg_replace('@wp-admin\/[^\.]+\.php|index\.php@', 'wp-admin/admin.php', SL_INFORMATION_PAGE),
		'meta'  => array(
			'title' => __('News & Upgrades', "store-locator"),
			'target' => '_self',
			'class' => 'sl_menu_class'
		),
	);
	$sl_admin_toolbar_array[] = array(
		'id'    => 'sl-menu-locations',
		'parent' => 'sl-menu',
		'title' => __('Locations', "store-locator"),
		'href'  => preg_replace('@wp-admin\/[^\.]+\.php|index\.php@', 'wp-admin/admin.php', SL_MANAGE_LOCATIONS_PAGE),
		'meta'  => array(
			'title' => __('Locations', "store-locator"),
			'target' => '_self',
			'class' => 'sl_menu_class'
		),
	);
	$sl_admin_toolbar_array[] = array(
		'id'    => 'sl-menu-mapdesigner',
		'parent' => 'sl-menu',
		'title' => __('Settings', "store-locator"),
		'href'  => preg_replace('@wp-admin\/[^\.]+\.php|index\.php@', 'wp-admin/admin.php', SL_MAPDESIGNER_PAGE),
		'meta'  => array(
			'title' => "MapDesigner ".__('Settings', "store-locator"),
			'target' => '_self',
			'class' => 'sl_menu_class'
		),
	);
	
	if (function_exists('do_sl_hook')){ do_sl_hook('sl_admin_toolbar_filter', '', array(&$sl_admin_toolbar_array)); }
	
	foreach ($sl_admin_toolbar_array as $toolbar_page) {
		$admin_bar -> add_menu($toolbar_page);
	}
	
} 
/*---------------------------------------------------------------*/
function sl_permissions_check() {
	global $sl_vars, $sl_uploads;

	if (!empty($_POST['sl_folder_permission'])) {
		@array_map("chmod", sanitize_text_field($_POST['sl_folder_permission']), array_fill(0, count(sanitize_text_field($_POST['sl_folder_permission'])), 0755) );
	}
	if (!empty($_POST['sl_file_permission'])) {
		//var_dump($_POST['sl_file_permission']); die();
		@array_map("chmod", sanitize_text_field($_POST['sl_file_permission']), array_fill(0, count(sanitize_text_field($_POST['sl_file_permission'])), 0644) );
	}

	//checks permissions of files & folders
	$f_to_check = array(SL_ADDONS_PATH, SL_THEMES_PATH);

	clearstatcache();
	$needs=0;

	foreach ($f_to_check as $slf) {
		$dir_iterator = new RecursiveDirectoryIterator($slf);
		$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
		$files = new RegexIterator($iterator, "/\.(php|gif|jpe?g|png|css|js|csv|xml|json|txt)/");
		// could use CHILD_FIRST if you so wish
		//foreach ($files as $file) {
    		//	$all_sl_files[]=$file;
		//}
	
		foreach($iterator as $value) {
		//print $value."<br>";
			if (is_dir($value) && 0755 !== (@fileperms($value) & 0777)) {
				$needs_update["folder"][] = "$value - <b>".@decoct(@fileperms($value) & 0777)."</b>";
				$needs++;
			}
		}

		foreach($files as $value) {
			if (!is_dir($value) && 0644 !== (@fileperms($value) & 0777) && !preg_match("@(index|dummy)\.php$@", $value) ) {
				//&& !preg_match("@(index|dummy)\.php@", $value) - v3.89.4 - was constantly showing index.php or dummy.php too much
				$needs_update["file"][] = "$value - <b>".@decoct(@fileperms($value) & 0777)."</b>";
				$needs++;
			}
		}

	}
	//v3.89 - checking folder permissions of WP's 'uploads', 'plugins' & SL's 'store-Locator', 'sl-uploads' folders too now
	$extra_folders = array($sl_uploads['basedir'], WP_CONTENT_DIR."/plugins", SL_PATH, SL_UPLOADS_PATH);
	foreach($extra_folders as $value) {
		if (is_dir($value) && 0755 !== (@fileperms($value) & 0777)) {
			$needs_update["folder"][] = "$value - <b>".@decoct(@fileperms($value) & 0777)."</b>";
			$needs++;
		}
	}
	//end - v3.89
	
	$button_note = __("Note: Clicking this button should update permissions, however, if it doesn\'t, you may need to update permissions by using an FTP program.  Click &quot;OK&quot; or &quot;Confirm&quot; to continue ...", "store-locator");
	
	if ($needs > 0){
		$output = "";
		print "<br><div class='sl_admin_warning' style='width:97%'><b>".__("Important Note", "store-locator").":</b><br>".__("Some of your folders / files may need updating to the proper permissions (folders: 755 / files: 644), otherwise, all functionality may not work as intended.  View folders / files below", "store-locator")." - <a href='#' onclick='show(\"file_perm_table\"); return false;'>".__("display / hide list of folders & files", "store-locator")."</a>:<br>
		<div style='float:right'>(<a href='".$_SERVER['REQUEST_URI']."&file_perm_msg=1'>".__("Hide This Notice Permanently", "store-locator")."</a>)</div><br><br><table cellpadding='7px' id='file_perm_table' style='display:none;'><tr>";
	}
	if (!empty($needs_update["folder"])) {
		$output .= "<td style='vertical-align: top; width:50%'><form method='post' onsubmit=\"return confirm('".$button_note."');\"><strong>".__("Folders", "store-locator").":</strong><br><input type='submit' class='button-primary' value=\"".__("Update Checked Folders' Permissions", "store-locator")."\"><br><br>";
		foreach ($needs_update["folder"] as $value) {
			$output .= "\n<input name='sl_folder_permission[]' checked='checked' type='checkbox' value='".substr($value, 0, -13)."'>&nbsp;/".str_replace(ABSPATH, '', $value)."<br>"; // "-13", removes 13 chars: " - <b> 777 </b>" at end of value
		}
		$output .= "</form></td>";	
	}
	if (!empty($needs_update["file"])) {
		$output .= "<td style='vertical-align: top; style: 50%;'><form method='post' onsubmit=\"return confirm('".$button_note."');\"><strong>".__("Files", "store-locator").":</strong><br><input type='submit' class='button-primary' value=\"".__("Update Checked Files' Permissions", "store-locator")."\"><br><br>";
		foreach ($needs_update["file"] as $value) {
			$output .= "\n<input name='sl_file_permission[]' checked='checked' type='checkbox' value='".substr($value, 0, -13)."'>&nbsp;/".str_replace(ABSPATH, '', $value)."<br>";
		}
		$output .= "</form></td>";	
	}
	if ($needs > 0){
		//print sl_truncate($output, 500, "return", 2);
		print $output."</tr></table></div>";
		$sl_vars['perms_need_update'] = 1;
	}
	
	if ($needs == 0) {
		$sl_vars['perms_need_update'] = 0;
	}
	
}
/*---------------------------------------------------------------*/
function sl_remote_data($val_arr, $decode_mode = 'json') {
	$pagetype = (!empty($val_arr['pagetype']))? $val_arr['pagetype'] : "none" ;
	$dir = (!empty($val_arr['dir']))? $val_arr['dir'] : "none" ;
	$key = (!empty($val_arr['key']))? "__".$val_arr['key'] : "" ;
	$start = (!empty($val_arr['start']))? $val_arr['start'] : 0 ;
	$val_host = (!empty($val_arr['host']))? $val_arr['host'] : SL_HOME_URL;
	$val_url = (!empty($val_arr['url']))? $val_arr['url'] : "/show-data/". $pagetype ."/". $dir ."$key" ."/". $start ;
	$useragent = (!empty($val_arr['ua']))? $val_arr['ua'] : "LotsOfLocales Store Locator Plugin" ;
	
	$target = "http://" . $val_host . $val_url;
  	//exit($target);
	$remote_access_fail = false;
	$request = wp_remote_get( $target,
		array(
			'timeout' => 10,
		        'user-agent' => $useragent
		)
	);
	$returned_value = wp_remote_retrieve_body($request);
	//die($val_url);
	//var_dump(json_decode($returned_value, true));

	if (!empty($returned_value)) {
		if ($decode_mode == "none") {
			$the_data = $returned_value;
		} elseif ($decode_mode == "serial") {
			$the_data = unserialize($returned_value);
		} else {
			$the_data = json_decode($returned_value, true);
		}
		return $the_data;
	} else {
		return false;
	}
}
/*-----------------------------------*/
function sl_pricing_tables() {
	global $sl_vars, $sl_version;
	
	$from = "From cache<br>" . (time() - strtotime($sl_vars['sl_pt_data_time']))/60;
	
	$sl_vars['sl_pt_data_time'] = (empty($sl_vars['sl_pt_data_time']))? date("Y-m-d H:i:s") : $sl_vars['sl_pt_data_time'];
	if (empty($sl_vars['sl_pt_data']) || ((time() - strtotime($sl_vars['sl_pt_data_time']))/60)>=60*6) { //60*6 = 6-hr cache
	//if (false === ($sl_pt_data = get_transient('sl_pt_data'))) { 
		
		$sl_vars['sl_pt_data'] = @sl_remote_data(array(
		'host' => SL_HOME_URL,
		'url' => '/wp-json/wp/v2/pages/3561?fields=sl_pt_post_meta',
		'ua' => 'none'));
	
		if (!empty($sl_vars['sl_pt_data']['status']) && $sl_vars['sl_pt_data']['status'] == 'inactive') {
			$sl_vars['sl_pt_data_status'] = 'inactive';
		} elseif ((empty($sl_vars['sl_pt_data']) || is_null($sl_vars['sl_pt_data'])) && $sl_vars['sl_pt_data_status'] == 'inactive') {
			$sl_vars['sl_pt_data_status'] = 'inactive';
		} else {
			$sl_vars['sl_pt_data_status'] = 'active';
		}
	
		//if ( is_wp_error( $response ) ) {
		if (empty($sl_vars['sl_pt_data']) || is_null($sl_vars['sl_pt_data'])) {
			//unset($sl_vars['sl_pt_data_time']);
			//unset($sl_vars['sl_pt_data']);
			//sl_data('sl_pt_data_time', 'delete'); sl_data('sl_pt_data', 'delete');
		} else {
			//if ( isset( $response[ 'body' ] ) && strlen( $response[ 'body' ] ) > 0 ) {
			//	$sl_pt_data = json_decode( wp_remote_retrieve_body( $response ), true );
				$from = "From json data<br>" . (time() - strtotime($sl_vars['sl_pt_data_time']))/60;
			//}
	
			//$expiration = empty( $sl_pt_data ) ? MINUTE_IN_SECONDS : 1* MINUTE_IN_SECONDS;  
			//set_transient('sl_pt_data', $sl_pt_data, $expiration);	

			$sl_vars['sl_pt_data_time'] = date("Y-m-d H:i:s");
		}
	}
	
	sl_data('sl_vars', 'update', $sl_vars); //needs to be outside below if statement for case when unsetting values above - 4/15/17 2:04 PM
	
	if ( !empty($sl_vars['sl_pt_data']) && is_array($sl_vars['sl_pt_data']) && (empty($sl_vars['sl_pt_data_status']) || $sl_vars['sl_pt_data_status'] != 'inactive') ) {
		foreach ($sl_vars['sl_pt_data']['sl_pt_post_meta'] as $value) {
			$value = str_replace("<i class=\"fa fa-times-circle red\"></i>", "<b style='color: red'>X</b>",  $value);
		}
		$css_news_upgrades = '
<style>
.ptp-dg6-col {
    display: inline-block;
    margin-bottom: 20px !important;
    margin-top: 10px !important;
    padding-left: 5px !important;
    padding-right: 5px !important;
    padding-top: 10px !important;
    vertical-align: top;
    width: 46%;
}
#ptp-3837 .cd-pricing-list .ptp-dg6-col .ptp-dg6-price {
    font-size: 30px;
}
.ptp-dg6-button.ptp-checkout-button {
    /*display: block;*/
    padding: 10px;
    text-align: center;
    text-decoration: none;
    /*width: 60%;*/
}
.ptp-dg6-pricing-header > h2 {
    font-size: 30px !important;
    padding-bottom: 10px;
}
.ptp-dg6-col {
	padding: 10px;
}
</style>
'; #<- v3.98.5 - heredoc to single quotes
		$css_addons = '
<style>
.ptp-dg6-col {
    display: inline-block;
    font-size: 8px;
    padding: 10px 5px 0;
    vertical-align: top;
    width: 22.5%;
}
#ptp-3837 .cd-pricing-list .ptp-dg6-col .ptp-dg6-price {
    font-size: 30px;
}
.ptp-dg6-button.ptp-checkout-button {
    display: block;
    padding: 10px;
    text-align: center;
    text-decoration: none;
    width: 60%;
}
.ptp-dg6-pricing-header > h2 {
    font-size: 30px !important;
    padding-bottom: 10px;
}
</style>
'; #<- v3.98.5 - heredoc to single quotes

$css_both = '
<style>
.has-tip {
    color: #009 !important; /*#e97d68 !important;*/
    border-bottom: 1px dotted #ccc;
    cursor: help;
}
.ptp-dg6-pay-duration::before {
    content: "/";
    margin-right: 2px;
}
.ptp-dg6-pricing-header h2, .ptp-dg6-pricing-header .cd-price {
    font-family:  \'Trajan Pro\',Georgia,serif;
    font-variant: small-caps;
    text-align: center;
}
#ptp-3837 .ptp-dg6-bullet-item {
    font-size: 12px !important;
    padding: 6px 3px !important;
}
.sl_pt_info {
	text-align: center; 
	background-color: lightYellow; 
	margin: 7px; 
	margin-left: 0px; margin-top: 0px;
	padding: 6px
}
</style>
<script>
jQuery(document).ready(function(){
	jQuery(\'.has-tip\').each(function() {
		tip_html=jQuery(this).attr(\'title\', jQuery(this).attr(\'title\').replace(/<br>/gi, \' \'));
		/*console.log(tip_html.attr(\'title\'));*/ 
		jQuery(this).attr(\'data-tooltip\', tip_html.attr(\'title\')); 
		jQuery(this).removeAttr(\'title\');
	});
	jQuery("a[href*=\'edd_action\']").each(function() {
		jQuery(this).attr(\'href\', jQuery(this).attr(\'href\') + \'&utm_source=wp-admin&utm_medium=plugin&utm_campaign=' . $sl_version . '&utm_term=' . $sl_vars['sl_addons_page_name']. '\');
	});
});
</script>
'; #<- v3.98.5 - heredoc to single quotes
		$styling = (preg_match("@addons\.php@", $_SERVER["REQUEST_URI"]) )? $css_addons : $css_news_upgrades;
		$from = "<span style='display:none'>$from</span>"; //debug info
		return $from.$styling.$css_both.$value;
	} else {
		if (empty($_GET['prem_refresh']) && preg_match("@addons\.php@", $_SERVER["REQUEST_URI"]) ){
			print "<script>location.replace('".$_SERVER['REQUEST_URI']."&prem_refresh=1')</script>";
		}
		return false;
	}
}
function do_sl_premium_pricing() {
	print sl_pricing_tables();
}
/*-----------------------------------------------*/
### Ref: ottopress.com/2010/dont-include-wp-load-please/ -- 4/11/18 ###
add_filter('query_vars','sl_plugin_add_trigger');
function sl_plugin_add_trigger($vars) {
    $vars[] = 'sl_engine';
    return $vars;
}
add_action('template_redirect', 'sl_plugin_trigger_check');
function sl_plugin_trigger_check() {
    global $wpdb, $sl_xml_columns, $sl_vars;
    if ( !empty(get_query_var('sl_engine') ) ) {
	$translate = preg_replace("@_@", "/", "/" . get_query_var('sl_engine') . ".php");
	if (file_exists(SL_PATH . $translate)) {
		include(SL_PATH . $translate);
	}
       exit;
     }
}
/*-----------------------------------------------*/
### Loading SL Variables ###
$sl_vars=sl_data('sl_vars');

if (!is_array($sl_vars)) {
	//print($sl_vars."<br><br>");
	function sl_fix_corrupted_serialized_string($string) {
		$tmp = explode(':"', $string);
		$length = count($tmp);
		for($i = 1; $i < $length; $i++) {    
			list($string) = explode('"', $tmp[$i]);
        		$str_length = strlen($string);    
        		$tmp2 = explode(':', $tmp[$i-1]);
        		$last = count($tmp2) - 1;    
        		$tmp2[$last] = $str_length;         
        		$tmp[$i-1] = join(':', $tmp2);
    		}
    		return join(':"', $tmp);
	}
	$sl_vars = sl_fix_corrupted_serialized_string($sl_vars); //die($sl_vars);
	sl_data('sl_vars', 'update', $sl_vars);
	$sl_vars = unserialize($sl_vars); //var_dump($sl_vars);
	//die($sl_vars);
}

function sl_ap_load() {
global $wpdb; //needed if AP loaded inside of function - 4/28/17
### Addons Platform Load ###
    if (defined('SL_ADDONS_PLATFORM_FILE') && file_exists(SL_ADDONS_PLATFORM_FILE) && !function_exists('do_sl_hook') ) {
// && (preg_match("@$sl_dir@", $_SERVER['REQUEST_URI']) || preg_match('@widgets@', $_SERVER['REQUEST_URI']) || !preg_match('@wp-admin@', $_SERVER['REQUEST_URI']))) {
	include_once(SL_ADDONS_PLATFORM_FILE);
	sl_initialize_variables(); // needed
}
######
}  
add_action('plugins_loaded', 'sl_ap_load', '0.0000001'); //very early priority -- need to happen before any other addons
/*-------------------------------*/
function sl_second_pass_script() {
	global $sl_vars, $wpdb;
	#v3.98.5 - seperated from sl_second_pass function for script enqueuing; used in sl_do_geocoding() function
		#4/24/22 - printing now, called too late (after page has fully loaded) to actually use the 'admin_enqueue_scripts' action
	//if (empty($GLOBALS['sp_first_fun']) || $GLOBALS['sp_first_fun'] == 0) {
		//$sens=(!empty($sl_vars['sensor']) && ($sl_vars['sensor'] === "true" || $sl_vars['sensor'] === "false" ))? "&amp;sensor=".$sl_vars['sensor'] : "&amp;sensor=false" ;
		$sens=""; //- v3.84 - 11/25/15 - no longer required
		$lang_loc=(!empty($sl_vars['map_language']))? "&amp;language=".$sl_vars['map_language'] : "" ; 
		$region_loc=(!empty($sl_vars['map_region']))? "&amp;region=".$sl_vars['map_region'] : "" ;
		$key=(!empty($sl_vars['api_key']))? "&amp;key=".$sl_vars['api_key'] : "" ;
		
		print "<script src='https://maps.googleapis.com/maps/api/js?v=3{$sens}{$lang_loc}{$region_loc}{$key}' type='text/javascript'></script>\n";
		#wp_enqueue_script("sl_second_pass", "https://maps.googleapis.com/maps/api/js?v=3" . esc_attr($sens) . esc_attr($lang_loc) . esc_attr($region_loc) . esc_attr($key) ); -- switched back to printing - #4/24/22
	//}

}
function sl_second_pass($address, $sl_id) {
	global $sl_vars, $wpdb;
	
	$the_sl_id = ($sl_id==="")? $wpdb->insert_id : $sl_id;
	$the_edit = (!empty($_GET['edit']))? sanitize_text_field($_GET['edit']) : "" ;
	
	print "<br><b>Second Attempt ...</b> <span id='sl_second_pass_status-$the_sl_id'></span><br><br>";
	
	#sl_second_pass_script() -- v3.98.5

	print "<script type='text/javascript'>
	    jQuery(document).ready(function() {
		sl_geocoder = new google.maps.Geocoder();
		sl_geocoder.geocode( {'address': \"".trim($address)."\"}, function(results, status) {
			//alert(status);
			if (status == google.maps.GeocoderStatus.OK) {
				center = results[0].geometry.location;
				
				jQuery.get('".SL_SITEURL."/?sl_engine=sl-inc_includes_sl-geo&sl_id=".$the_sl_id."&lat=' + center.lat() + '&lng=' + center.lng() + '&_wpnonce=".wp_create_nonce('second-pass-geo_'.$the_sl_id)."', function() {
					document.getElementById('sl_second_pass_status-{$the_sl_id}').innerHTML = \"<font color='DarkGreen'>Success!</font>\"; ";	
				
					if (!empty($_GET['edit'])) {
						print "document.getElementById('sl_second_pass_status-{$the_sl_id}').innerHTML += \"<br><br>This page will refresh this in <span id='time_left_span'>5</span> secs ...\";
						setTimeout(function(){location.replace('".esc_url_raw(str_replace("&edit={$the_edit}", "", $_SERVER['REQUEST_URI']))."#a".$the_edit."');}, 5000);";
					}
						
			print "});
					
			} else {
				document.getElementById('sl_second_pass_status-{$the_sl_id}').innerHTML = \"<font color='red'>Failed again</font>, with status \" + status + \". Check <a href='https://".$sl_vars['google_map_domain']."?q=".urlencode(trim($address))."' target='_blank'>Google search</a> to make sure address is valid.\";";  
				/*if (!empty($_GET['edit'])) {
					print "document.getElementById('sl_second_pass_status-{$the_sl_id}').innerHTML += \"<br><br>This page will refresh this in <span id='time_left_span'>10</span> secs ...\";
					setTimeout(function(){location.replace('".str_replace("&edit={$the_edit}", "", $_SERVER['REQUEST_URI'])."#a".$the_edit."');}, 10000);";
				}*/
				
		print "}
		});
	    });
	</script>";
	//$GLOBALS['sp_first_run'] = 1;
}
/*------------------------------------*/
## Overridable ##
/*Needs !SL_PLUGIN_ACTIVATING check because any addon w/overriding functions that is being activated will load after 'plugins_loaded' hook, thus giving 'Fatal error: Cannot redeclare {function}' error.  Only 'activated_plugin' & 'shutdown' hooks happen after plugin activation. Ref: https://codex.wordpress.org/Function_Reference/register_activation_hook#Process_Flow */
if (!SL_PLUGIN_ACTIVATING) {	 add_action('plugins_loaded', 'sl_set_default_functions', "100000000"); } //late priority to allow all addons to be loaded
function sl_set_default_functions() {
 #ref: http://stackoverflow.com/a/20594790 -- creates overridable functions if not already created by an addon

if (!function_exists("sl_do_geocoding")){
 function sl_do_geocoding($address, $sl_id="") {
   if (empty($_POST['no_geocode']) || $_POST['no_geocode']!=1){
	global $wpdb, $text_domain, $sl_vars;

	// Initialize delay in geocode speed
	$delay = 100000; $ccTLD=$sl_vars['map_region']; $sensor=$sl_vars['sensor'];
	$base_url = "https://maps.googleapis.com/maps/api/geocode/json?";

	//if ($sensor!="" && !empty($sensor) && ($sensor === "true" || $sensor === "false" )) {$base_url .= "sensor=".$sensor;} else {$base_url .= "sensor=false";}  - v3.84 - 11/25/15 - no longer required

	//Adding ccTLD (Top Level Domain) to help perform more accurate geocoding according to selected Google Maps Domain - 12/16/09
	if ($ccTLD!="") {
		$base_url .= "&region=".$ccTLD;
		//die($base_url);
	}

	//Map Character Encoding
	if (!empty($sl_vars['map_language'])) {
		$base_url .= "&language=".$sl_vars['map_language'];
	}
	
	//API Key
	if (!empty($sl_vars['api_key'])) {
		$base_url .= "&key=".$sl_vars['api_key'];
	}

	// Iterate through the rows, geocoding each address
		$request_url = $base_url . "&address=" . urlencode(trim($address)); //print($request_url );
   
	//Updated to WP HTTP API - 12/4/18 4:49p
	$request = wp_remote_get( $request_url,
		array(
			'timeout' => 10
		)
	);
	$resp_json = wp_remote_retrieve_body($request);
	//End of new code

	$resp = json_decode($resp_json, true); //var_dump($resp);
    $status = $resp['status']; //$status = "";
    $lat = (!empty($resp['results'][0]['geometry']['location']['lat']))? $resp['results'][0]['geometry']['location']['lat'] : "" ;
    $lng = (!empty($resp['results'][0]['geometry']['location']['lng']))? $resp['results'][0]['geometry']['location']['lng'] : "" ;
	//die("<br>compare: ".strcmp($status, "OK")."<br>status: $status<br>");
    if (strcmp($status, "OK") == 0) {
		// successful geocode
		$geocode_pending = false;
		$lat = $resp['results'][0]['geometry']['location']['lat'];
		$lng = $resp['results'][0]['geometry']['location']['lng'];
		
		$GLOBALS['sdg_reply'] = '1st_attempt'; //message to control refreshing of page after successful geocode in processLocationData.php

		if ($sl_id==="") {
			$query = $wpdb->prepare("UPDATE ".SL_TABLE." SET sl_latitude = %s, sl_longitude = %s WHERE sl_id = %s LIMIT 1;", esc_sql($lat), esc_sql($lng), esc_sql($wpdb->insert_id)); //die($query); 
		} else {
			$query = $wpdb->prepare("UPDATE ".SL_TABLE." SET sl_latitude = %s, sl_longitude = %s WHERE sl_id = %s LIMIT 1;", esc_sql($lat), esc_sql($lng), esc_sql($sl_id)); 
		}
		$update_result = $wpdb->query($query);
		if ($update_result === FALSE) {
			die("Invalid query: " . $wpdb->last_error);
		}
    } else if (strcmp($status, "OVER_QUERY_LIMIT") == 0) {
		// sent geocodes too fast
		$delay += 100000;
    } else {
		// failure to geocode
		$geocode_pending = false;
		echo __("Address ", "store-locator") . esc_attr($address) . __(" <font color=red>failed to geocode</font>. ", "store-locator");
		//if (!empty($status)) {
			echo __("Received status ", "store-locator") . esc_attr($status) ."\n<br>";
		/*} else {
			echo __("No status received from Google", "store-locator")."\n<br>"; 
		}*/
		//var_dump($_POST);
		if (!isset($_POST['total_entries']) && (empty($_POST['sl_id']) || !is_array($_POST['sl_id']) || (is_array($_POST['sl_id']) && (empty($_POST['act']) || $_POST['act'] != 'regeocode'))) ) {
			//add_action("admin_print_scripts", "sl_second_pass_script"); #v3.98.5 -- doesn't work. Occurs too late / after page, scripts have loaded, thus needs to be printed directly within sl_second_pass() - 4/24/22 3:06a
			sl_second_pass_script();
			sl_second_pass($address, $sl_id); 
		}
		//|| (is_array($_POST['sl_id']) && count($_POST['sl_id']) == 1) - removed for now
    }
    usleep($delay);
  } else {
  	//print __("Geocoding bypassed ", "store-locator");
  } @ob_flush(); flush();
 }
}
/*-------------------------------*/
if (!function_exists("sl_template")){
   function sl_template($content) {

	global $sl_dir, $sl_base, $sl_uploads_base, $sl_path, $sl_uploads_path, $text_domain, $wpdb, $sl_vars;
	if(! preg_match('|\[store-locator|i', $content)) {
		return $content;
	}
	else {
		$height=($sl_vars['height'])? $sl_vars['height'] : "500" ;
		$width=($sl_vars['width'])? $sl_vars['width'] : "100" ;
		$radii=($sl_vars['radii'])? $sl_vars['radii'] : "1,5,10,(25),50,100,200,500" ;
		$r_array=explode(",", $radii);
		$height_units=($sl_vars['height_units'])? $sl_vars['height_units'] : "px";
		$width_units=($sl_vars['width_units'])? $sl_vars['width_units'] : "%";
		
		$sl_instruction_message=($sl_vars['instruction_message'])? $sl_vars['instruction_message'] : "Enter Your Address or Zip Code Above.";
		$sl_radius_label=$sl_vars['radius_label'];
		$sl_search_label=($sl_vars['search_label'])? $sl_vars['search_label'] : "Address" ;
		$sl_city_dropdown_label=$sl_vars['city_dropdown_label'];
		
		$sl_search_button = "search_button.png";
		$sl_search_button_down = "search_button_down.png";
		$sl_search_button_over = "search_button_over.png";

		//WPML Display Integration
		//if (function_exists('icl_t')) { 
			include(SL_INCLUDES_PATH."/mapdesigner-options.php");
			$GLOBALS['input_zone_type'] = 'labels';
			$GLOBALS['output_zone_type'] = 'sl_template';
		
			$labels_arr = array_filter($sl_mdo, "filter_sl_mdo");
			unset($GLOBALS['input_zone_type']); unset($GLOBALS['output_zone_type']);
			//var_dump($labels_arr); die();
		
			foreach ($labels_arr as $value) {
				$the_field = $value['field_name'];
				$varname = "sl_".$the_field;
				
				$$varname = apply_filters("wpml_translate_single_string", $$varname, SL_DIR, $value['label']);
			}
			
			### Search Button States
			$sl_search_button = apply_filters("wpml_translate_single_string", $sl_search_button, SL_DIR,"Search Button Filename");
			$sl_search_button_down = apply_filters("wpml_translate_single_string", $sl_search_button_down, SL_DIR,"Search Button Filename (Down State)");
			$sl_search_button_over = apply_filters("wpml_translate_single_string", $sl_search_button_over, SL_DIR,"Search Button Filename (Over State)");
		//}
		//End WPML
				
		//Polylang Display Integration
		if (function_exists('pll__')) { 
			//include(SL_INCLUDES_PATH."/mapdesigner-options.php");
			//$GLOBALS['input_zone_type'] = 'labels';
			//$GLOBALS['output_zone_type'] = 'sl_template';
		
			//$labels_arr = array_filter($sl_mdo, "filter_sl_mdo");
			//unset($GLOBALS['input_zone_type']); unset($GLOBALS['output_zone_type']);
			//var_dump($labels_arr); die();
		
			foreach ($labels_arr as $value) {
				$the_field = $value['field_name'];
				$varname = "sl_".$the_field;
				
				$$varname = pll__($$varname);
			}
			
			### Search Button States
			$sl_search_button =  pll__($sl_search_button);
			$sl_search_button_down =  pll__($sl_search_button_down);
			$sl_search_button_over =  pll__($sl_search_button_over);
		}
		//End Polylang
				
		$unit_display=($sl_vars['distance_unit']=="km")? "km" : "mi";
		$r_options="";
		foreach ($r_array as $value) {
			$s=(preg_match("@\(.*\)@", $value))? " selected='selected' " : "" ;
			$value=preg_replace("@[^0-9]@", "", $value);
			$r_options.="<option value='$value' $s>$value $unit_display</option>";
		}
		
		if ($sl_vars['use_city_search']==1) {
			$cs_array=$wpdb->get_results("SELECT CONCAT(TRIM(sl_city), ', ', TRIM(sl_state)) as city_state FROM ".SL_TABLE." WHERE sl_city<>'' AND sl_state<>'' AND sl_latitude<>'' AND sl_longitude<>'' GROUP BY city_state ORDER BY city_state ASC", ARRAY_A);
			//var_dump($cs_array); die();
			$cs_options="";
			if (!empty($cs_array)) {
				foreach($cs_array as $value) {
$cs_options.="<option value='$value[city_state]'>$value[city_state]</option>";
				}
			} else {
				$sl_vars['use_city_search']=0; // if no full city-state combos to populate dropdown, turn off - 2/4/15 - v.3.32
			}
		}
		/*if ($sl_vars['use_name_search']==1) {
			$name_array=$wpdb->get_results("SELECT sl_store FROM ".SL_TABLE." WHERE sl_store<>'' ORDER BY sl_store ASC", ARRAY_A);
			//var_dump($cs_array); die();
			if ($name_array) {
				foreach($name_array as $value) {
					$name_options.="<option value='".sl_comma($value[sl_store])."'>".sl_comma($value[sl_store])."</option>";
				}
			}
		}*/
	
	if ($sl_vars['theme']!="") {
		$theme_base=SL_UPLOADS_BASE."/themes/".$sl_vars['theme'];
		$theme_path=SL_UPLOADS_PATH."/themes/".$sl_vars['theme'];	
	}
	else {
		$theme_base=SL_UPLOADS_BASE."/images";
		$theme_path=SL_UPLOADS_PATH."/images";
	}
	
	if (!file_exists($theme_path."/".$sl_search_button)) {
		$theme_base=SL_BASE."/images";
		$theme_path=SL_PATH."/images";
	}
	$submit_img=$theme_base."/".$sl_search_button;
	$loading_img=(file_exists(SL_UPLOADS_PATH."/images/loading.gif"))? SL_UPLOADS_BASE."/images/loading.gif" : SL_BASE."/images/loading.gif"; //for loading/processing gif image
	$mousedown=(file_exists($theme_path."/".$sl_search_button_down))? "onmousedown=\"this.src='$theme_base/".$sl_search_button_down."'\" onmouseup=\"this.src='$theme_base/".$sl_search_button."'\"" : "";
	$mouseover=(file_exists($theme_path."/".$sl_search_button_over))? "onmouseover=\"this.src='$theme_base/".$sl_search_button_over."'\" onmouseout=\"this.src='$theme_base/".$sl_search_button."'\"" : "";
	$button_style=(file_exists($theme_path."/".$sl_search_button))? "type='image' src='$submit_img' $mousedown $mouseover" : "type='submit'";
	$button_style.=" onclick=\"showLoadImg('show', 'loadImg');\""; //added 3/30/12 for loading/processing gif image
	//print "$submit_img | ".SL_UPLOADS_PATH."/themes/".$sl_vars['theme']."/search_button.png";
	$hide=($sl_vars['remove_credits']==1)? "display:none;" : "";
	
$form="
<div id='sl_div'>
  <form onsubmit='searchLocations(); return false;' id='searchForm' action=''>
    <table border='0' cellpadding='3px' class='sl_header' style='width:$width$width_units;'><tr>
	<td valign='top' id='search_label'>$sl_search_label&nbsp;</td>
	<td ";
	
	if ($sl_vars['use_city_search']!=1) {$form.=" colspan='4' ";}
	
	$form.=" valign='top'><input type='text' id='addressInput' size='50' /></td>
	";
	
	if (!empty($cs_array) && $sl_vars['use_city_search']==1) {
		$form.="<td valign='top'></td>";
	}
	
	if (!empty($cs_array) && $sl_vars['use_city_search']==1) {
		$form.="<td id='addressInput2_container' colspan='2'>";
		$form.="<select id='addressInput2' onchange='aI=document.getElementById(\"searchForm\").addressInput;if(this.value!=\"\"){oldvalue=aI.value;aI.value=this.value;}else{aI.value=oldvalue;}'>";
		if (!empty($sl_city_dropdown_label)) {
			$form.="<option value=''>".$sl_city_dropdown_label."</option>";
		}
		$form.="$cs_options</select></td>";
	}
	
	/*if ($name_array && $sl_vars['use_name_search']==1) {
		$form.="<td valign='top'><nobr>&nbsp;<b>OR</b>&nbsp;</nobr></td>";
	}
	
	if ($name_array && $sl_vars['use_name_search']==1) {
	$form.="
	<td valign='top'>";
	$form.="<select id='addressInput3' onchange='aI=document.getElementById(\"searchForm\").addressInput;if(this.value!=\"\"){oldvalue=aI.value;aI.value=this.value;}else{aI.value=oldvalue;}'>
	<option value=''>--Search By Name--</option>
	$name_options
    </select>";
	
	//$form.="<input name='addressInput3'><input type='hidden' value='1' name='name_search'></td>";
	}*/
	
	
	$form.="
	</tr><tr>
	 <td id='radius_label'>$sl_radius_label</td>
	 <td id='radiusSelect_td' ";
	
	if ($sl_vars['use_city_search']==1) {$form.="colspan='2'";}
	 
	$form.="><select id='radiusSelect'>$r_options</select>
	</td>
	<td valign='top' ";
	
	if ($sl_vars['use_city_search']!=1) {$form.="colspan='2'";}
	
	$form.=" ><input $button_style value='Search Locations' id='addressSubmit'/></td>
	<td><img src='$loading_img' id='loadImg' style='opacity:0; filter:alpha(opacity=0); height:28px; vertical-align:bottom; position:relative; '></td>
	</tr></table>";
	$form.=(function_exists("do_sl_hook"))? do_sl_header() : "" ;
$form.="<table style='width:100%;/*border:solid silver 1px*/' cellspacing='0px' cellpadding='0px' > 
     <tr>
        <td style='width:100%' valign='top' id='map_td'> <div id='sl_map' style='width:$width$width_units; height:$height$height_units'></div><table cellpadding='0px' class='sl_footer' style='width:$width$width_units;{$hide}' ><tr><td class='sl_footer_left_column'><a href='http://www.viadat.com/store-locator' target='_blank' title='WordPress Store Locator -- LotsOfLocales&trade;'>WordPress Store Locator</a></td><td class='sl_footer_right_column'> <a href='http://www.viadat.com' target='_blank' title='Map Maker for Creating Store Locators or Any Address Maps Using WordPress & Google Maps'>Viadat Creations</a></td></tr></table>
		</td>
      </tr>
	  <tr id='cm_mapTR'>
        <td width='' valign='top' style='/*display:hidden; border-right:solid silver 1px*/' id='map_sidebar_td'> <div id='map_sidebar' style='width:$width$width_units;/* $height$height_units; */'> <div class='text_below_map'>$sl_instruction_message</div></div>
        </td></tr>
  </table></form>
</div>";

	//preg_match("@\[STORE-LOCATOR [tag=\"(.*)\"]?\]@", $matched); 
	//global $map_tag=$matched[1];
	
	return preg_replace("@\[store-locator(.*)?\]@i", $form, $content);
	}
    }
}
}
?>
