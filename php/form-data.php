<?php

function biz_logic_wp_custom_wp_any_form_get_saved_data($the_form_post_id) {
	$html_str = "";
	$the_form_submit_arr = biz_logic_wp_custom_get_post_meta($the_form_post_id, "form_data_custom_field_the_form_submit_arr", "");
	if($the_form_submit_arr != "") {
		$html_str .= "<div id='div-wpaf-table-form-data' class='div-wpaf-table' >";
		foreach ($the_form_submit_arr as $the_control_fields_arr) {
			$html_str .= "<div class='div-wpaf-table-row' >";
			$the_control_type = $the_control_fields_arr["the_control_type"];
			switch ($the_control_type) {
				case "txt":
					$the_field_name = $the_control_fields_arr["the_field_name"];
					$the_txt = $the_control_fields_arr["the_txt"];
					$is_email = $the_control_fields_arr["is_email"];
					if($is_email == "yes") {
						$the_txt = "<a href='mailto:" . $the_txt . "' >" . $the_txt . "</a>";
					}
					$html_str .= "<div class='div-wpaf-table-cell class-bold' >" . $the_field_name . ":</div><div class='div-wpaf-table-cell' >" . $the_txt . "</div>";	
					break;
				case "txta":
					$the_field_name = $the_control_fields_arr["the_field_name"];
					$the_txt = $the_control_fields_arr["the_txt"];
					$html_str .= "<div class='div-wpaf-table-cell class-bold' >" . $the_field_name . ":</div><div class='div-wpaf-table-cell' >" . nl2br($the_txt) . "</div>";	
					break;
				case "ddl":
					$the_field_name = $the_control_fields_arr["the_field_name"];
					$the_txt = $the_control_fields_arr["the_txt"];
                    $html_str .= "<div class='div-wpaf-table-cell class-bold' >" . $the_field_name . ":</div><div class='div-wpaf-table-cell' >" . $the_txt . "</div>";	
					break;
				case "cbx":
					$the_field_name = $the_control_fields_arr["the_field_name"];
					$saved_data_values_separator = $the_control_fields_arr["saved_data_values_separator"];
					$the_txt = biz_logic_wp_custom_get_post_meta($the_form_post_id, $the_field_name, "");	
					switch($saved_data_values_separator) {
                        case "new_line":
                            $the_txt = nl2br($the_txt);
                            break;
                        default:
                        	break;
                    }
					$html_str .= "<div class='div-wpaf-table-cell class-bold' >" . $the_field_name . ":</div><div class='div-wpaf-table-cell' >" . $the_txt . "</div>";	
					break;
				case "rbtn":
					$the_field_name = $the_control_fields_arr["the_field_name"];
					$the_checked_val = $the_control_fields_arr["the_checked_val"];
					$html_str .= "<div class='div-wpaf-table-cell class-bold' >" . $the_field_name . ":</div><div class='div-wpaf-table-cell' >" . $the_checked_val . "</div>";	
					break;
			}
			$html_str .= "</div>";
		}
		$html_str .= "</div>" . biz_logic_wp_custom_get_div_clear_html(false);
	}
	return $html_str;
}

function biz_logic_wp_custom_wp_any_form_get_saved_field_data_for_submit_email($the_field_name) {
	$form_data_post_id = biz_logic_wp_custom_get_session_info("new_form_data_post_id");
	if($form_data_post_id) {
        switch($the_field_name) {
            case "sitenameurlstr":
                return "<a href='" . get_site_url() . "' >" . get_bloginfo('name') . "</a>";
                break;
            default:
                return biz_logic_wp_custom_get_saved_form_field_data_to_html($form_data_post_id, $the_field_name, "email");     
                break;
        }
	} else {
		return "";	
	}
}

function biz_logic_wp_custom_get_control_fields_arr_from_the_form_submit_arr($form_data_post_id, $the_field_name) {
    $the_form_submit_arr = biz_logic_wp_custom_get_post_meta($form_data_post_id, "form_data_custom_field_the_form_submit_arr", false);
    if($the_form_submit_arr) {
        foreach ($the_form_submit_arr as $the_control_fields_arr) {
            if($the_control_fields_arr["the_field_name"] == $the_field_name) {
                return $the_control_fields_arr;
            }
        }    
    }
    return false;
}

function biz_logic_wp_custom_get_saved_form_field_data_to_html($form_data_post_id, $the_field_name, $for_what) {
    $the_custom_field_value = biz_logic_wp_custom_get_post_meta($form_data_post_id, $the_field_name, "");
    $the_control_fields_arr = biz_logic_wp_custom_get_control_fields_arr_from_the_form_submit_arr($form_data_post_id, $the_field_name);
    if($the_control_fields_arr) {
        $the_control_type = $the_control_fields_arr["the_control_type"];
        switch ($the_control_type) {
            case "txt":
            	if($the_custom_field_value != "") {
					$is_email = $the_control_fields_arr["is_email"];
					if($is_email == "yes") {
						$the_custom_field_value = "<a href='mailto:" . $the_custom_field_value . "' >" . $the_custom_field_value . "</a>";
					}
				}
                break;
            case "txta":
            	$the_custom_field_value = nl2br($the_custom_field_value);
                break;
            case "ddl":
                break;
            case "cbx":
                $saved_data_values_separator = $the_control_fields_arr["saved_data_values_separator"];
                switch($saved_data_values_separator) {
                    case "new_line":
                        $the_custom_field_value = nl2br($the_custom_field_value);
                        break;
                    default:
                        break;
                }
                break;
            case "rbtn":
                break;   
        }
    }
    return $the_custom_field_value;
}

?>