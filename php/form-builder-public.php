<?php

function biz_logic_wp_custom_build_form_from_saved_data_public($the_post_id, $hval_form_builder_arr, $the_form_vars_arr) {
    $html_str = "";
    $pop_up_form_is_pop_up = $the_form_vars_arr["pop_up_form_is_pop_up"];
    if($pop_up_form_is_pop_up == "yes") {
        $html_str .= "<div id='div-popup-form_" . $the_post_id . "' class='div-popup-form mfp-hide' >";
    }
	$html_str .= "
	<div id='div-wp-any-form-container_" . $the_post_id . "' class='div-wp-any-form-container' >
        <div id='div-wp-any-form_" . $the_post_id . "' class='div-wp-any-form' >
            <div id='div-wp-any-form-msg_" . $the_post_id . "' class='div-wp-any-form-msg' ></div>
            <div id='div-wp-any-form-rows-container_" . $the_post_id . "' class='div-wp-any-form-rows-container' >";
	foreach ($hval_form_builder_arr as $key => $row) {
	    $the_cell_no_val[$key] = $row['the_cell_no_val'];
	    $the_row_no_val[$key] = $row['the_row_no_val'];
	}
	array_multisort($the_row_no_val, SORT_ASC, $the_cell_no_val, SORT_ASC, $hval_form_builder_arr);
	$the_current_row_no_val = "-1";
	$the_current_cell_no_val = "-1";
	$the_row_counti = 0;
	foreach ($hval_form_builder_arr as $the_control_fields_arr) {
		if($the_control_fields_arr["the_row_no_val"] != $the_current_row_no_val) {
			$the_row_counti += 1;
			if($the_row_counti > 1) {
				$html_str .= "
				</div><div class='div-clear div-clear-row_has_cells_wider_than_screen' ></div>" . biz_logic_wp_custom_get_div_clear_html(false);	
			}
			$the_current_row_no_val = $the_control_fields_arr["the_row_no_val"];
			$html_str .= "<div id='div-wp-any-form-row_" . $the_current_row_no_val . "' class='div-wp-any-form-row' >";
		}
		$the_current_cell_no_val = $the_control_fields_arr["the_cell_no_val"];
		$the_control_html_str = biz_logic_wp_custom_get_control_html_public($the_control_fields_arr);
		$html_str .= "<div id='div-wp-any-form-cell_" . $the_current_row_no_val . "_" . $the_current_cell_no_val . "' class='div-wp-any-form-cell' >" . $the_control_html_str . "</div>";				
	}
	$html_str .= "
				</div>
				" . biz_logic_wp_custom_get_div_clear_html(false) . "
			</div>
        </div>
    </div>";
    if($pop_up_form_is_pop_up == "yes") {
        $pop_up_form_link_text = $the_form_vars_arr["pop_up_form_link_text"];
        if($pop_up_form_link_text == "") {
            $pop_up_form_link_text = _x("Open Form", "Pop up form default open form link text", "wp-any-form");
        }
        $pop_up_form_link_type = $the_form_vars_arr["pop_up_form_link_type"];
        $the_form_link_html_str = "";
        switch($pop_up_form_link_type) {
            case "link":
                $the_form_link_html_str = "<a id='aopen-popup-form_" . $the_post_id . "' class='aopen-popup-form' href='javascript:void(0);' >" . $pop_up_form_link_text . "</a>";
                break;
            case "btn":
                $the_form_link_html_str = "<input id='btn-open-popup-form_" . $the_post_id . "' class='btnopen-popup-form' type='submit' value='" . $pop_up_form_link_text . "' />";
                break;
        }
        $html_str .= "</div>" . $the_form_link_html_str;
    }
    $html_str .= biz_logic_wp_custom_get_div_clear_html("25");
    $server_error_msg_public = _x("Server error, please try again later.", "Server error message for public forms", "wp-any-form");
    $validation_str_required = _x("required", "Required text for public form validation", "wp-any-form");
    $validation_str_email = _x("Valid email address required for", "Invalid email address text for public form validation", "wp-any-form");
    $validation_str_confirm = _x("Matching values required for", "Confirm Text Box control text for public form validation", "wp-any-form");
    $validation_str_confirm_and = _x("and", "Confirm Text Box control text for public form validation (and)", "wp-any-form");
    $validation_str_cbx = _x("At least one checked value required for", "Checkbox at least one required string for public form validation", "wp-any-form");
    $form_validated_ok_str = _x("Form validated successfully.", "Default successful text for public form validation when form is not saved", "wp-any-form");
    $duplicate_form_error_msg = _x("Duplicate form found, a form can only be included once per page.", "Duplicate form error message for public forms", "wp-any-form");
    $recaptcha_language = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_recaptcha_language", "auto"));
    $js_str = "
    <script type='text/javascript' >
        var the_form_container_arr = {};
        the_form_container_arr.the_post_id = " . $the_post_id . ";
        the_form_container_arr.the_form_arr = " . json_encode($hval_form_builder_arr) . ";
        the_form_container_arr.the_form_vars_arr = " . json_encode($the_form_vars_arr) . ";
        the_forms_container_arr.push(the_form_container_arr);
        var the_asterisk_html_str_user_html_str = \"" . ASTERISK_HTML_STR_USER . "\";
        var server_error_msg_val = '" . $server_error_msg_public . "';
        var validation_str_required_val = '" . $validation_str_required . "';
        var validation_str_email_val = '" . $validation_str_email . "';
        var validation_str_confirm_val = '" . $validation_str_confirm . "';
        var validation_str_confirm_and_val = '" . $validation_str_confirm_and . "';
        var validation_str_cbx_val = '" . $validation_str_cbx . "';
        var form_validated_ok_str_val = '" . $form_validated_ok_str . "';
        var duplicate_form_error_msg_val = '" . $duplicate_form_error_msg . "';
        if(the_recaptcha_language_val == '-1') {
            the_recaptcha_language_val = '" . $recaptcha_language . "';
        }
    </script>";
    $css_str = "";
    $wp_any_form_custom_css = biz_logic_wp_custom_get_post_meta($the_post_id, "wp_any_form_custom_css", "");
    if($wp_any_form_custom_css != "") {
    	$css_str .= "<style type='text/css' >" . $wp_any_form_custom_css . "</style>";
    }
    return $html_str . $js_str . $css_str;
}

function biz_logic_wp_custom_get_the_form_vars_arr($the_post_id) {
	$return_arr = array();
	$return_arr["form_post_id"] = $the_post_id;
    $return_arr["form_default_form_width"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_form_width", "");
    $return_arr["form_default_layout"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_layout", "auto");
    $return_arr["form_default_cells_per_row"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_cells_per_row", "");
    $return_arr["form_default_cell_spacing"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_cell_spacing", "");
    $return_arr["form_default_row_spacing"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_row_spacing", "");
    $return_arr["form_default_cell_padding"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_cell_padding", "");
    $return_arr["form_default_font_size"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_font_size", "");
    $return_arr["form_default_font_weight"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_font_weight", "");
    $return_arr["form_default_font_colour"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_font_colour", "#000000");
    $return_arr["form_default_font_colour_use_defined"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_font_colour_use_defined", "no");
    $return_arr["form_default_message_font_colour"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_message_font_colour", "#cc0000");
    $return_arr["form_default_required_field_font_colour"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_required_field_font_colour", "#cc0000");
    $return_arr["form_cell_vertical_align"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_cell_vertical_align", "");
    $return_arr["form_submit_save_submission"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_submit_save_submission", "no");
    $return_arr["form_submit_send_email"] = biz_logic_wp_custom_get_post_meta($the_post_id, "form_submit_send_email", "no");
    $form_messages_required_fields = esc_textarea(biz_logic_wp_custom_get_post_meta($the_post_id, "wpa_form_messages_required_fields", "* Required fields."));
    /*$the_asterisk_pos = strpos($form_messages_required_fields, "*");
    if ($the_asterisk_pos !== false) {
        $form_messages_required_fields = str_replace("*", ASTERISK_HTML_STR_USER, $form_messages_required_fields);
    }*/
    $return_arr["form_messages_required_fields"] = $form_messages_required_fields;
    $return_arr["form_messages_contacting_server"] = esc_textarea(biz_logic_wp_custom_get_post_meta($the_post_id, "wpa_form_messages_contacting_server", ""));
    $return_arr["form_messages_success"] = esc_textarea(biz_logic_wp_custom_get_post_meta($the_post_id, "wpa_form_messages_success", "Form submitted successfully."));
    $return_arr["form_submit_revert_to_required_msg"] = biz_logic_wp_custom_get_post_meta($the_post_id, "wpa_form_submit_revert_to_required_msg", "no");
    $return_arr["form_submit_scroll_to_msg"] = biz_logic_wp_custom_get_post_meta($the_post_id, "wpa_form_submit_scroll_to_msg", "no");
    $return_arr["pop_up_form_is_pop_up"] = biz_logic_wp_custom_get_post_meta($the_post_id, "pop_up_form_is_pop_up", "no");
    $return_arr["pop_up_form_bg_colour"] = biz_logic_wp_custom_get_post_meta($the_post_id, "pop_up_form_bg_colour", "#ffffff");
    $return_arr["pop_up_form_link_text"] = biz_logic_wp_custom_get_post_meta($the_post_id, "pop_up_form_link_text", "Open Form");
    $return_arr["pop_up_form_link_type"] = biz_logic_wp_custom_get_post_meta($the_post_id, "pop_up_form_link_type", "link");
    return $return_arr;
}

?>