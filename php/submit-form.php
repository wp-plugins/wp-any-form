<?php

function biz_logic_wp_custom_submit_form_action($the_form_post_id, $the_form_submit_arr, $is_in_preview_mode, $the_form_vars_arr) {
    $return_msg = "init";
	$the_date_time = biz_logic_wp_custom_get_date_time_for_time_zone("datetime");
	$the_form_post_title = get_the_title($the_form_post_id);
	$the_post_author_id = "1";
	if (is_user_logged_in()) {
		$current_user = wp_get_current_user();
		$the_post_author_id = $current_user->ID;
	} else {
		$the_post_author_id = biz_logic_wp_custom_get_admin_author_id();
	}
	$new_form_data_post_title = $the_form_post_title . " " . $the_date_time;
	$new_form_data_post_args = array(
        'post_author'    => $the_post_author_id,
        'post_type'      => "wp_any_form_data",
        'post_status'    => "publish",
        'post_title'     => $new_form_data_post_title
    ); 
    $new_form_data_post_id = wp_insert_post($new_form_data_post_args, false);
    if($new_form_data_post_id) {
        add_post_meta($new_form_data_post_id, "form_data_custom_field_form_post_id", $the_form_post_id); 
    	add_post_meta($new_form_data_post_id, "form_data_custom_field_form_post_title", $the_form_post_title); 
    	add_post_meta($new_form_data_post_id, "form_data_custom_field_the_form_submit_arr", $the_form_submit_arr); 
    	add_post_meta($new_form_data_post_id, "form_data_custom_field_the_date_time", $the_date_time);
        $field_name_arr = array();
        foreach ($the_form_submit_arr as $the_control_fields_arr) {
    		$the_control_type = $the_control_fields_arr["the_control_type"];
    		switch ($the_control_type) {
    			case "txt":
    				$the_field_name = $the_control_fields_arr["the_field_name"];
                    $field_name_arr[] = $the_field_name;
    				$the_txt = $the_control_fields_arr["the_txt"];
                    add_post_meta($new_form_data_post_id, $the_field_name, $the_txt);
    				break;
                case "txta":
                    $the_field_name = $the_control_fields_arr["the_field_name"];
                    $field_name_arr[] = $the_field_name;
                    $the_txt = $the_control_fields_arr["the_txt"];
                    add_post_meta($new_form_data_post_id, $the_field_name, $the_txt);
                    break;
                case "ddl":
                    $the_field_name = $the_control_fields_arr["the_field_name"];
                    $field_name_arr[] = $the_field_name;
                    $the_txt = $the_control_fields_arr["the_txt"];
                    add_post_meta($new_form_data_post_id, $the_field_name, $the_txt);
                    break;
                case "cbx":
                    $the_field_name = $the_control_fields_arr["the_field_name"];
                    $field_name_arr[] = $the_field_name;
                    $the_txt = "";
                    $the_cbxs_checked_values_arr = $the_control_fields_arr["the_cbxs_checked_values_arr"];
                    $the_cbxs_checked_values_total_count = count($the_cbxs_checked_values_arr);
                    if($the_cbxs_checked_values_total_count > 0) {
                        $saved_data_values_separator = $the_control_fields_arr["saved_data_values_separator"];
                        for ($i=0; $i < $the_cbxs_checked_values_total_count; $i++) { 
                            $the_checked_value = $the_cbxs_checked_values_arr[$i];
                            $the_txt .= $the_checked_value;
                            if($i < $the_cbxs_checked_values_total_count-1) {
                                switch($saved_data_values_separator) {
                                    case ",": case ";":
                                        $the_txt .= $saved_data_values_separator . " ";        
                                        break;
                                    case "|":
                                        $the_txt .= $saved_data_values_separator;
                                        break;
                                    case "single_space":
                                        $the_txt .= " ";
                                        break;
                                    case "new_line":
                                        $the_txt .= "\n";
                                        break;
                                }
                            }
                        }
                    }
                    add_post_meta($new_form_data_post_id, $the_field_name, $the_txt);
                    break;
                case "rbtn":
                    $the_field_name = $the_control_fields_arr["the_field_name"];
                    $field_name_arr[] = $the_field_name;
                    $the_checked_val = $the_control_fields_arr["the_checked_val"];
                    add_post_meta($new_form_data_post_id, $the_field_name, $the_checked_val);
                    break;
    		}
    	}
        biz_logic_wp_custom_set_session_info("new_form_data_post_id", $new_form_data_post_id);
        $form_submit_save_submission = biz_logic_wp_custom_get_post_meta($the_form_post_id, "form_submit_save_submission", "");
        $save_the_form = false;
        if($form_submit_save_submission == "yes") {
            $save_the_form = true;
        }
        if($save_the_form) {
            biz_logic_wp_custom_submit_form_sync_data_schema($the_form_post_id, $field_name_arr);    
        }
        $form_submit_send_email = biz_logic_wp_custom_get_post_meta($the_form_post_id, "form_submit_send_email", "");
        $send_the_email = false;
        if($form_submit_send_email == "yes") {
            $send_the_email = true;
        }
        if($send_the_email) {
            $the_wpaf_email_templates_saved_arr = get_post_meta($the_form_post_id, "wpaf_email_templates_selected");
            if(!biz_logic_wp_custom_check_if_array_empty($the_wpaf_email_templates_saved_arr)) {
                foreach ($the_wpaf_email_templates_saved_arr as $the_email_template_post_id) {
                    $email_template_custom_field_to_addresses = esc_textarea(biz_logic_wp_custom_get_post_meta($the_email_template_post_id, "email_template_custom_field_to_addresses", ""));
                    $form_fields_send_to_email_address = esc_textarea(biz_logic_wp_custom_get_post_meta($the_email_template_post_id, "form_fields_send_to_email_address", ""));
                    $email_template_custom_field_subject = biz_logic_wp_custom_get_post_meta($the_email_template_post_id, "email_template_custom_field_subject", "Form submission details");
                    $the_email_content_html_str = "";
                    $the_email_template_post = get_post($the_email_template_post_id);
                    $the_email_content_html_str = $the_email_template_post->post_content;
                    $the_email_content_html_str = apply_filters('the_content', $the_email_content_html_str);
                    add_filter('wp_mail_content_type', 'biz_logic_wp_custom_set_html_content_type');
                    if($email_template_custom_field_to_addresses != "") {
                        $multiple_to_recipients = explode(";", $email_template_custom_field_to_addresses);
                        wp_mail($multiple_to_recipients, $email_template_custom_field_subject, $the_email_content_html_str);
                    }
                    if($form_fields_send_to_email_address != "") {
                        $the_control_fields_arr_for_email_address = biz_logic_wp_custom_get_control_fields_arr_from_the_form_submit_arr($new_form_data_post_id, $form_fields_send_to_email_address);
                        $the_auto_reply_email_address = $the_control_fields_arr_for_email_address["the_txt"];
                        wp_mail($the_auto_reply_email_address, $email_template_custom_field_subject, $the_email_content_html_str);
                    }
                    remove_filter('wp_mail_content_type', 'biz_logic_wp_custom_set_html_content_type');
                }
            }    
        }
        if(!$save_the_form) {
            wp_delete_post($new_form_data_post_id, true);
        }
        $return_msg = "ok";
        $return_arr = array(
            "return_msg" => $return_msg,
            "the_form_vars_arr" => $the_form_vars_arr
        );
        return $return_arr;   
    } else {
        $return_arr = array(
            "return_msg" => _x("Form submission error, please try again later.", "Form submission error message", "wp-any-form"),
            "the_form_vars_arr" => $the_form_vars_arr
        );
        return $return_arr;
    }
}

function biz_logic_wp_custom_set_html_content_type() {
    return 'text/html';
}	

?>