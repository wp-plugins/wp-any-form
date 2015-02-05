<?php

function biz_logic_wp_custom_email_options_get_email_templates_form_fields_ddl($the_form_post_id) {
	$html_str = "";
	$ddl_form_fields_options_arr = array();
	$hval_form_builder_arr = biz_logic_wp_custom_get_post_meta($the_form_post_id, "hval_form_builder_arr", "");
	$have_saved_arr_not_empty = false;
    if($hval_form_builder_arr != "") {
        $hval_form_builder_arr = json_decode($hval_form_builder_arr, true);
        if (!biz_logic_wp_custom_check_if_array_empty($hval_form_builder_arr)) {
            $have_saved_arr_not_empty = true;            
        }
    }
    if($have_saved_arr_not_empty) {
    	foreach ($hval_form_builder_arr as $the_control_fields_arr) {
    		switch($the_control_fields_arr["the_control_type"]) {
				case "lbl":
					break;
				case "txt":
					$the_field_name = $the_control_fields_arr["the_field_name"];
					$ddl_form_fields_options_arr[$the_field_name] = $the_field_name;
					break;
                case "txta":
                    $the_field_name = $the_control_fields_arr["the_field_name"];
                    $ddl_form_fields_options_arr[$the_field_name] = $the_field_name;
                    break;
                case "ddl":
                    $the_field_name = $the_control_fields_arr["the_field_name"];
                    $ddl_form_fields_options_arr[$the_field_name] = $the_field_name;
                    break;
                case "cbx":
                    $the_field_name = $the_control_fields_arr["the_field_name"];
                    $ddl_form_fields_options_arr[$the_field_name] = $the_field_name;
                    break;
                case "rbtn":
                    $the_field_name = $the_control_fields_arr["the_field_name"];
                    $ddl_form_fields_options_arr[$the_field_name] = $the_field_name;
                    break;
				case "btnsubmit":
					break;	
				case "emptycell":
					break;	
                case "recaptcha":
                    break;
			}
    	}
    }
    if(count($ddl_form_fields_options_arr) > 0) {
        $ddl_form_fields_options_arr["sitenameurlstr"] = "Site title, link";
    	$html_str = biz_logic_wp_custom_get_ddl_html("", "ddl_form_fields", "", "", $ddl_form_fields_options_arr, "-1"); 
    } else {
    	$html_str = _x("No form fields found.", "Email template form fields drop down list informational message", "wp-any-form");	
    }
	return $html_str;
}

function biz_logic_wp_custom_email_options_get_auto_reply_send_to_email_address_form_fields_ddl($the_form_post_id, $selected_val) {
    $html_str = "";
    $ddl_form_fields_options_arr = array("" => "No auto reply");
    if($the_form_post_id != "") {
        $hval_form_builder_arr = biz_logic_wp_custom_get_post_meta($the_form_post_id, "hval_form_builder_arr", "");
        $have_saved_arr_not_empty = false;
        if($hval_form_builder_arr != "") {
            $hval_form_builder_arr = json_decode($hval_form_builder_arr, true);
            if (!biz_logic_wp_custom_check_if_array_empty($hval_form_builder_arr)) {
                $have_saved_arr_not_empty = true;            
            }
        }
        if($have_saved_arr_not_empty) {
            foreach ($hval_form_builder_arr as $the_control_fields_arr) {
                switch($the_control_fields_arr["the_control_type"]) {
                    case "txt":
                        $the_field_name = $the_control_fields_arr["the_field_name"];
                        $is_email = $the_control_fields_arr["is_email"];
                        if($is_email == 'yes') {
                            $ddl_form_fields_options_arr[$the_field_name] = $the_field_name;
                        }
                        break;
                    default:
                        break;
                }
            }
        }    
    }
    $html_str = biz_logic_wp_custom_get_ddl_html("form_fields_send_to_email_address", "ddl_form_fields_send_to_email_address", "", "", $ddl_form_fields_options_arr, $selected_val);
    return $html_str;
}

function biz_logic_wp_custom_email_options_get_email_templates_for_form($the_form_post_id) {
	$html_str = "";
	$the_wpaf_email_templates_saved_arr = get_post_meta($the_form_post_id, "wpaf_email_templates_selected");    
	$email_templates_args = array(
        'post_type' => 'wpaf_email_templates',
        'numberposts' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => "email_template_custom_field_form_post_id",
                'value' => $the_form_post_id,
                'compare' => '='
            )
        )
    );    
    $email_templates_arr = get_posts($email_templates_args);
    $email_templates_total_count = count($email_templates_arr);
    if ($email_templates_total_count > 0) {
        foreach($email_templates_arr as $email_template_item) {
            setup_postdata($email_template_item);
            $the_email_template_post_id = $email_template_item->ID;
            $the_email_template_post_title = get_the_title($the_email_template_post_id);
            $checked_str = "";
            if(in_array($the_email_template_post_id, $the_wpaf_email_templates_saved_arr)) {
            	$checked_str = $the_email_template_post_id;
            }
            $cbx_email_template_options = array(
		        "name_str" => "wpaf_email_templates_selected[]",
		        "the_label" => $the_email_template_post_title,
		        "the_value" => $the_email_template_post_id,
		        "checked_str" => $checked_str
		    );
			$html_str .= "<div class='div-wpaf-table-cell' >" . biz_logic_wp_custom_get_cbx_html($cbx_email_template_options) . "</div>";
        }
    } else {
    	$html_str .= _x("No email templates found for form.", "Email templates form options informational message", "wp-any-form");
    }
    wp_reset_query();    
	return $html_str;
}

?>