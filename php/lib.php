<?php

if(!function_exists("biz_logic_wp_custom_get_url_string_val")) {
	function biz_logic_wp_custom_get_url_string_val($urlStringName, $returnIfNotSet) {
	  if (isset($_GET[$urlStringName]) && $_GET[$urlStringName] != "") return $_GET[$urlStringName]; else return $returnIfNotSet; 
	}
}

if(!function_exists("biz_logic_wp_custom_get_unique_id")) {
	function biz_logic_wp_custom_get_unique_id() {
		$the_unique_id = str_replace('.', '', uniqid('', true));
		return $the_unique_id;
	}
}

/*if(!function_exists("biz_logic_wp_custom_i18n_text")) {
	function biz_logic_wp_custom_i18n_text($the_str, $return_str_or_echo_str, $the_context = "") {
		switch($return_str_or_echo_str) {
			case "return_str":
				if($the_context == "") {
					return __($the_str, BIZLOGIC_UNIQUE_PLUGIN_TEXT_DOMAIN);	
				} else {
					return _x($the_str, $the_context, BIZLOGIC_UNIQUE_PLUGIN_TEXT_DOMAIN);
				}
				break;
			case "echo_str":
				if($the_context == "") {
					_e($the_str, BIZLOGIC_UNIQUE_PLUGIN_TEXT_DOMAIN);
				} else {
					_ex($the_str, $the_context, BIZLOGIC_UNIQUE_PLUGIN_TEXT_DOMAIN);
				}
				break;
		}
	}
}*/

if(!function_exists("biz_logic_wp_custom_sendmail")) {
	function biz_logic_wp_custom_sendmail($to, $from, $replyto, $subject, $message) {
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: ' . $from . "\r\n" . 'Reply-To: ' . $replyto . "\r\n" . 'X-Mailer: PHP/' . phpversion();
		if(mail($to, $subject, $message, $headers)){ return true; } else { return false; }
	}
}

if(!function_exists("biz_logic_wp_custom_get_site_option")) {
	function biz_logic_wp_custom_get_site_option($the_site_option_name_str, $default_val = "-1") {
	    $the_site_option = get_option($the_site_option_name_str);
	    if (!$the_site_option) { $the_site_option = $default_val; }
	    return $the_site_option;
	}
}

if(!function_exists("biz_logic_wp_custom_add_update_option")) {
	function biz_logic_wp_custom_add_update_option($option_name, $new_value) {
	    $allok = false;
	    if (get_option($option_name) == $new_value) {
	        return "same";
	    } else {
	        $updateok = update_option($option_name, $new_value);
	    } 
	    if ($updateok) { return "updateok"; }
	    else {
	        $deprecated = ' ';
	        $autoload = 'no';
	        $addok = add_option($option_name, $new_value, $deprecated, $autoload);
	    }
	    if ($addok) {
	        return "addok";
	    } else {
	        return "notok";
	    }
	}
}

if(!function_exists("biz_logic_wp_custom_get_post_meta")) {
	function biz_logic_wp_custom_get_post_meta($the_post_id, $post_meta_name, $default_val = "-1") {
	    $custom_field_val = get_post_meta($the_post_id, $post_meta_name, true);
	    if (!$custom_field_val) { $custom_field_val = $default_val; }
	    return $custom_field_val;
	}
}

if(!function_exists("biz_logic_wp_custom_sort_by_column")) {
	function biz_logic_wp_custom_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
	    $sort_col = array();
	    foreach ($arr as $key=> $row) {
	        $sort_col[$key] = $row[$col];
	    }
	    array_multisort($sort_col, $dir, $arr);
	}
}

if(!function_exists("biz_logic_wp_custom_get_mail_tr_if_not_empty")) {
	function biz_logic_wp_custom_get_mail_tr_if_not_empty($the_field_name, $the_field_val) {
	    $html_str = "";
	    if ($the_field_val != "") {
	        $html_str = "
	        <tr>
	            <td style='font-weight:bold; padding:5px;' >" . $the_field_name . ":&nbsp;</td>
	            <td style='padding:5px;' >" . $the_field_val . "</td>
	        </tr>";
	    }
	    return $html_str;
	}
}

if(!function_exists("biz_logic_wp_custom_get_str_to_length")) {
	function biz_logic_wp_custom_get_str_to_length($the_str, $the_length, $the_more_str = "...") {
	    if (strlen($the_str) >= $the_length) {
	        $the_str = substr($the_str, 0, $the_length - (strlen($the_more_str) + 1)) . $the_more_str;
	    }
	    return $the_str;
	}
}

if(!function_exists("biz_logic_wp_custom_get_session_info")) {
	function biz_logic_wp_custom_get_session_info($session_field_name) {
		if(isset($_SESSION[$session_field_name])) {
			return $_SESSION[$session_field_name];
		} else {
			return false;
		} 
	}
}

if(!function_exists("biz_logic_wp_custom_set_session_info")) {
	function biz_logic_wp_custom_set_session_info($session_field_name, $session_field_value) {
		$_SESSION[$session_field_name] = $session_field_value;
	}
}

if(!function_exists("biz_logic_wp_custom_remove_session_info")) {
	function biz_logic_wp_custom_remove_session_info($session_field_names_arr) {
		foreach ($session_field_names_arr as $session_field_name) {
			unset($_SESSION[$session_field_name]);	
		}
		session_destroy();
	}
}

if(!function_exists("biz_logic_wp_custom_get_ui_dialog_form_html")) {
	function biz_logic_wp_custom_get_ui_dialog_form_html($dialog_container_id_str, $dialog_content_container_id_str) {
		$html_str = "
		<!-- Admin UI Form -->
		<div id='" . $dialog_container_id_str . "' style='display:none;' >
		    <br />
		    <div id='" . $dialog_content_container_id_str . "' >&nbsp;</div>
		</div>";
		return $html_str;
	}
}

if(!function_exists("biz_logic_wp_custom_get_default_admin_content")) {
	function biz_logic_wp_custom_get_default_admin_content($display) {
	    return "<input type='hidden' class='" . BIZLOGIC_UNIQUE_PLUGIN_NAME . "_admin_jslib' value='" . $display . "' />";
	}
}

if(!function_exists("biz_logic_wp_custom_check_if_array_empty")) {
	function biz_logic_wp_custom_check_if_array_empty($the_arr) {
	    $the_arr = array_filter($the_arr);
		if (empty($the_arr)) {
			return true;
		} else {
			return false;
		}
	}
}

if(!function_exists("biz_logic_wp_custom_in_array_r")) {
	function biz_logic_wp_custom_in_array_r($needle, $haystack, $strict = false) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && biz_logic_wp_custom_in_array_r($needle, $item, $strict))) {
	            return true;
	        }
	    }
	    return false;
	}
}

if(!function_exists("biz_logic_wp_custom_get_date_format_site_option")) {
	function biz_logic_wp_custom_get_date_format_site_option() {
		$the_date_format_str = get_option('date_format');
		if($the_date_format_str == "" || !$the_date_format_str) {
			$the_date_format_str = "Y/m/d";
		}
		return $the_date_format_str;
	}
}

if(!function_exists("biz_logic_wp_custom_get_date_time_for_time_zone")) {
	function biz_logic_wp_custom_get_date_time_for_time_zone($get_what) {
		$date_time_for_time_zone = new DateTime("now");	
		$the_timezone_string = get_option('timezone_string');
		if($the_timezone_string == "") {
			$the_gmt_offset = get_option('gmt_offset');
			if($the_gmt_offset != "") {
				$the_timezone_string = biz_logic_wp_custom_get_time_zone_from_gmt($the_gmt_offset);	
			}
		}
		if($the_timezone_string != "") {
			$date_time_for_time_zone = new DateTime("now", new DateTimeZone($the_timezone_string));	
		}
		$the_date_time_format_str = "Y/m/d H:i";
		$the_date_format_str = biz_logic_wp_custom_get_date_format_site_option();
		$the_time_format_str = get_option('time_format');
		if($the_time_format_str == "" || !$the_time_format_str) {
			$the_time_format_str = "H:i";
		}
		if($the_date_format_str != "" && $the_time_format_str != "") {
			$the_date_time_format_str = $the_date_format_str . " " . $the_time_format_str;
		}
		switch($get_what) {
			case "datetime":
				return $date_time_for_time_zone->format($the_date_time_format_str);	
				break;
			case "date":
				return $date_time_for_time_zone->format($the_date_format_str);	
				break;
			case "time":
				return $date_time_for_time_zone->format($the_time_format_str);	
				break;
		}
	}
}

if(!function_exists("biz_logic_wp_custom_get_time_zone_from_gmt")) {
	function biz_logic_wp_custom_get_time_zone_from_gmt($GMT) {
	    $timezones = array(
	        '-12'=>'Pacific/Kwajalein',
	        '-11'=>'Pacific/Samoa',
	        '-10'=>'Pacific/Honolulu',
	        '-9'=>'America/Juneau',
	        '-8'=>'America/Los_Angeles',
	        '-7'=>'America/Denver',
	        '-6'=>'America/Mexico_City',
	        '-5'=>'America/New_York',
	        '-4'=>'America/Caracas',
	        '-3.5'=>'America/St_Johns',
	        '-3'=>'America/Argentina/Buenos_Aires',
	        '-2'=>'Atlantic/Azores',// no cities here so just picking an hour ahead
	        '-1'=>'Atlantic/Azores',
	        '0'=>'Europe/London',
	        '1'=>'Europe/Paris',
	        '2'=>'Africa/Johannesburg',
	        '3'=>'Europe/Moscow',
	        '3.5'=>'Asia/Tehran',
	        '4'=>'Asia/Baku',
	        '4.5'=>'Asia/Kabul',
	        '5'=>'Asia/Karachi',
	        '5.5'=>'Asia/Calcutta',
	        '6'=>'Asia/Colombo',
	        '7'=>'Asia/Bangkok',
	        '8'=>'Asia/Singapore',
	        '9'=>'Asia/Tokyo',
	        '9.5'=>'Australia/Darwin',
	        '10'=>'Pacific/Guam',
	        '11'=>'Asia/Magadan',
	        '12'=>'Asia/Kamchatka'
	    );
	    return($timezones[$GMT]); 
	}
}

if(!function_exists("biz_logic_wp_custom_get_admin_author_id")) {
	function biz_logic_wp_custom_get_admin_author_id() {
		$the_post_author_id = "1";
		$site_admins = get_users(array(
			'role' => 'administrator',
			'orderby' => 'registered',
			'order' => 'ASC',
			'fields' => array('ID')
		));	
		foreach ($site_admins as $the_first_site_admin) {
			$the_post_author_id = $the_first_site_admin->ID;
			break;
		}
		return $the_post_author_id;
	}
}

if(!function_exists("biz_logic_wp_custom_get_div_clear_html")) {
	function biz_logic_wp_custom_get_div_clear_html($height_val) {
		$html_str = "<div class='div-clear'";
		if($height_val) {
			$html_str .= " style='height: " . $height_val . "px;'";
		}
		$html_str .= " ></div>";
		return $html_str;
	}
}

if(!function_exists("biz_logic_wp_custom_get_current_post_type_admin")) {
	function biz_logic_wp_custom_get_current_post_type_admin() {
		global $post, $typenow, $current_screen, $pagenow;
		if(in_array($pagenow, array('post-new.php')) && !isset($_REQUEST['post_type'])) {
			return "post";
		}
		//we have a post so we can just get the post type from that
		if ( $post && $post->post_type )
		return $post->post_type;
		//check the global $typenow - set in admin.php
		elseif( $typenow )
		return $typenow;
		//check the global $current_screen object - set in sceen.php
		elseif( $current_screen && $current_screen->post_type )
		return $current_screen->post_type;
		//lastly check the post_type querystring
		elseif( isset( $_REQUEST['post_type'] ) )
		return sanitize_key( $_REQUEST['post_type'] );
		elseif( isset( $_REQUEST['post'] ) )
		return get_post_type(sanitize_key( $_REQUEST['post'] ));
		//we do not know the post type!
		return false;
	}
}

if(!function_exists("biz_logic_wp_custom_get_unique_post_title")) {
	function biz_logic_wp_custom_get_unique_post_title($the_title, $the_post_type) {
		$the_new_title_i = 1;
		$original_title = $the_title;
		while (biz_logic_wp_custom_post_exists($the_title, $the_post_type)) {
			$the_title = $original_title . " " . $the_new_title_i;
			$the_new_title_i += 1;
		}
		return $the_title;
	}
}

if(!function_exists("biz_logic_wp_custom_post_exists")) {
	function biz_logic_wp_custom_post_exists($title, $the_post_type) {
	    global $wpdb;
	    $post_title = wp_unslash(sanitize_post_field('post_title', $title, 0, 'db'));
	    $query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
	    $args = array();
	    if (!empty($title)) {
	        $query .= ' AND post_title = %s';
	        $args[] = $post_title;
	    }
	    if (!empty($the_post_type)) {
	        $query .= ' AND post_type = %s';
	        $args[] = $the_post_type;
	    }
	    if (!empty($args))
	        return (int) $wpdb->get_var($wpdb->prepare($query, $args));
	    return 0;
	}
}

?>