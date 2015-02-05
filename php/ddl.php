<?php

function biz_logic_wp_custom_get_ddl_html($ddl_name_str, $ddl_id_str, $ddl_class_str, $style_str, $val_desc_arr, $selected_val) {
	$html_str = "<select";
	if($ddl_name_str != "") {
		$html_str .= " name='" . $ddl_name_str . "'";
	}
	if($ddl_id_str != "") {
		$html_str .= " id='" . $ddl_id_str . "'";
	}
	if($ddl_class_str != "") {
		$html_str .= " class='" . $ddl_class_str . "'";
	}
    if($style_str != "") {
        $html_str .= " style='" . $style_str . "'";    
    }
	$html_str .= " >";
	foreach ($val_desc_arr as $val => $desc) { 
		$html_str .= "<option value='" . $val . "'";	
		if ($val == $selected_val) {
			$html_str .= " selected=selected";		
		}
		$html_str .= " >" . $desc . "</option>";	
	}
	$html_str .= "</select>";
	return $html_str;
}

function biz_logic_wp_custom_get_form_posts_drop_down_html($from_saved_form_data, $ddl_name_str, $ddl_id_str, $ddl_class_str, $first_option_arr, $the_selected_val) {
    $ddl_form_posts_options_arr = array();
    if($first_option_arr) {
        foreach ($first_option_arr as $key => $value) {
            $ddl_form_posts_options_arr[$key] = $value;    
        }
    }
    $form_args = false;
    if($from_saved_form_data) {
        $form_args = array(
            'post_type' => 'wp_any_form_data',
            'numberposts' => -1,
            'post_status' => 'publish'
        );    
    } else {
        $form_args = array(
            'post_type' => 'wp_any_form',
            'numberposts' => -1,
            'post_status' => 'any'
        );    
    }
    if($form_args) {
        $form_arr = get_posts($form_args);
        $form_total_count = count($form_arr);
        if ($form_total_count > 0) {
            $form_post_ids_added = array();
            foreach($form_arr as $form_item) {
                setup_postdata($form_item);
                if($from_saved_form_data) {
                    $the_form_data_post_id = $form_item->ID;;
                    $form_data_custom_field_form_post_id = biz_logic_wp_custom_get_post_meta($the_form_data_post_id, "form_data_custom_field_form_post_id", "-1");
                    $form_data_custom_field_form_post_title = biz_logic_wp_custom_get_post_meta($the_form_data_post_id, "form_data_custom_field_form_post_title", "-1");
                    if($form_data_custom_field_form_post_id != "-1" && $form_data_custom_field_form_post_title != "-1") {
                        if(!in_array($form_data_custom_field_form_post_id, $form_post_ids_added)) {
                            $form_post_ids_added[] = $form_data_custom_field_form_post_id;
                            $ddl_form_posts_options_arr[$form_data_custom_field_form_post_id] = $form_data_custom_field_form_post_title;
                        }
                    }    
                } else {
                    $the_form_post_id = $form_item->ID;
                    $the_form_post_title = get_the_title($the_form_post_id);
                    $ddl_form_posts_options_arr[$the_form_post_id] = $the_form_post_title;
                }
            }
        }
        wp_reset_query();    
        return biz_logic_wp_custom_get_ddl_html($ddl_name_str, $ddl_id_str, $ddl_class_str, "", $ddl_form_posts_options_arr, $the_selected_val);
    } else {
        return false;
    }
}

function biz_logic_wp_custom_get_form_posts_drop_down_tiny_mce_js() {
    $js_str = "<script type='text/javascript' > var the_form_posts_drop_down_tiny_mce_values_obj = [";
    $form_args = array(
        'post_type' => 'wp_any_form',
        'numberposts' => -1,
        'post_status' => 'any'
    );    
    $form_arr = get_posts($form_args);
    $form_total_count = count($form_arr);
    if ($form_total_count > 0) {
        for ($i=0; $i < $form_total_count; $i++) { 
            $form_item = $form_arr[$i];
            setup_postdata($form_item);
            $the_form_post_id = $form_item->ID;
            $the_form_post_title = get_the_title($the_form_post_id);
            $js_str .= "
                {text: '" . $the_form_post_title . "', onclick : function() {
                    tinymce.execCommand('mceInsertContent', false, '[wp_any_form display=\"form\" pid=\"" . $the_form_post_id . "\"]');
                }}";
            if($i < $form_total_count - 1) {
                $js_str .= ", ";
            }
        }
    }
    $js_str .= "]; </script>";
    wp_reset_query();    
    echo $js_str;
}

function biz_logic_wp_custom_get_ddl_control_options_arr($the_action_cmd, $the_control_fields_arr) {
    $html_str = "";
    $the_dialog_height = "700";
    $the_field_name = "";
    $the_field_id = "";
    $the_selected_value = "";
    $the_font_size = "";
    $the_font_weight = "";
    $the_font_colour = "";
    $the_font_colour_use_control_defined = "";
    $the_width = "";
    $the_custom_css_class = "";
    $is_required = "";
    $ddl_item_sets_arr = "";   
    switch ($the_action_cmd) {
        case "add":
            $the_field_id = biz_logic_wp_custom_get_unique_id();    
            $ddl_item_sets_arr = "none";
            break;
        case "edit":
            $the_field_name = $the_control_fields_arr["the_field_name"];    
            $the_field_id = $the_control_fields_arr["the_field_id"];
            $the_selected_value = $the_control_fields_arr["the_selected_value"];    
            $the_font_size = $the_control_fields_arr["the_font_size"];    
            $the_font_weight = $the_control_fields_arr["the_font_weight"]; 
            $the_font_colour = $the_control_fields_arr["the_font_colour"];   
            $the_font_colour_use_control_defined = $the_control_fields_arr["the_font_colour_use_control_defined"];    
            $the_width = $the_control_fields_arr["the_width"];    
            $the_custom_css_class = $the_control_fields_arr["the_custom_css_class"];
            $is_required = $the_control_fields_arr["is_required"]; 
            $ddl_item_sets_arr = $the_control_fields_arr["ddl_item_sets_arr"];   
            break;    
    }
    $cbx_options_is_required = array(
        "id_str" => "cbx_control_is_required",
        "the_label" => CONTROL_OPTIONS_GENERAL_LABEL_VALIDATION_REQUIRED,
        "the_value" => "yes",
        "checked_str" => $is_required
    );    
    $cbx_font_colour_use_control_defined_options = array(
        "id_str" => "cbx_font_colour_use_control_defined",
        "class_str" => "",
        "the_label" => CONTROL_OPTIONS_STYLE_CBX_USE_DEFINED_FONT_COLOUR,
        "the_value" => "yes",
        "checked_str" => $the_font_colour_use_control_defined
    );
    $cbx_is_initial_item_set_options = array(
        "id_str" => "cbx_is_initial_item_set",
        "class_str" => "",
        "the_label" => _x("Is initial item set", "Control options drop down list item sets (Is initial item set)", "wp-any-form"),
        "the_value" => "yes"
    );
    $html_str .= "
    <div class='div-control-options-container' >
        <div id='div-control-options-form-msg' class='lblmsg' ></div>
        <div id='div-control-options-tabs' >
            <ul>
                <li><a href='#tabs-general' >" . CONTROL_OPTIONS_TABS_HEADING_GENERAL . "</a></li>
                <li><a href='#tabs-style' >" . CONTROL_OPTIONS_TABS_HEADING_STYLE . "</a></li>
                <li><a href='#tabs-item-sets' >" . _x("Item sets", "Control options tabs heading (Item sets, ddl)", "wp-any-form") . "</a></li>
            </ul>    
            <div id='tabs-general' >
                " . biz_logic_wp_custom_get_div_clear_html("15") . "
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w135' >
                        " . CONTROL_OPTIONS_GENERAL_LABEL_FIELD_NAME . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_field_name' class='txt_control_options' placeholder='" . CONTROL_OPTIONS_GENERAL_PLACEHOLDER_FIELD_NAME . "' value='" . $the_field_name . "' />
                        &nbsp;
                        " . ASTERISK_HTML_STR . "
                        &nbsp;
                        <a id='acmd-help_formfieldname' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
                    </div>  
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w135' >
                        " . _x("Selected Value:", "Control options drop down list general label (Selected Value)", "wp-any-form") . "
                    </div>
                    <div id='div-any-form-cell-ddl_initial_selected_value' class='div-any-form-cell' >
                        " . biz_logic_wp_custom_get_initial_selected_value_ddl_html($ddl_item_sets_arr, $the_selected_value) . "
                    </div>  
                    <div class='div-any-form-cell' >
                        <a id='acmd-help_ddlselectedvalue' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w135' >
                        " . CONTROL_OPTIONS_GENERAL_LABEL_VALIDATION . "
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell' >
                        " . biz_logic_wp_custom_get_cbx_html($cbx_options_is_required) . "
                    </div>
                </div>
                " . biz_logic_wp_custom_get_div_clear_html("15") . "
            </div>
            <div id='tabs-style' >
                " . biz_logic_wp_custom_get_div_clear_html("15") . "
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w165' >
                        " . CONTROL_OPTIONS_STYLE_LABEL_FONT_SIZE . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_font_size' class='txt_control_options txt_default_font_size numbers_only' value='" . $the_font_size . "' />
                        &nbsp;
                        px
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w165' >
                        " . CONTROL_OPTIONS_STYLE_LABEL_FONT_WEIGHT . "
                    </div>
                    <div class='div-any-form-cell' >
                        " . biz_logic_wp_custom_get_font_weight_ddl_html_str("", "ddl_form_control_font_weight", $the_font_weight) . "
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w165' >
                        " . CONTROL_OPTIONS_STYLE_LABEL_FONT_COLOUR . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_font_colour' class='txt_control_options txt_font_colour' value='" . $the_font_colour . "' />
                        &nbsp;
                        " . biz_logic_wp_custom_get_cbx_html($cbx_font_colour_use_control_defined_options) . "
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w165' >
                        " . CONTROL_OPTIONS_STYLE_LABEL_WIDTH . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_width' class='txt_control_options numbers_only' placeholder='" . CONTROL_OPTIONS_STYLE_PLACEHOLDER_WIDTH . "' value='" . $the_width . "' />
                        &nbsp;
                        px
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w165' >
                        " . CONTROL_OPTIONS_STYLE_CUSTOM_CSS_CLASS . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_custom_css_class' class='txt_control_options txt_custom_css_class' value='" . $the_custom_css_class . "' />
                        &nbsp;
                        <a id='acmd-help_customcssclass' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
                    </div>
                </div>
                " . biz_logic_wp_custom_get_div_clear_html("15") . "
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell fs13' >
                        " . SELECT_NONE_LEAVE_EMPTY_CONTROL_STR . "
                    </div>
                </div>
                " . biz_logic_wp_custom_get_div_clear_html("15") . "
            </div>
            <div id='tabs-item-sets' >
                <div id='div-ddl-options-item-sets-form' class='div-any-form-row' >
                    <div id='div-ddl-item-sets-form-msg' class='lblmsg' ></div>
                    <div class='div-any-form-row' >
                        <div id='ddl_item_sets-container' class='div-any-form-cell lbl w135' >
                            " . biz_logic_wp_custom_get_ddl_options_ddl_item_sets_html($ddl_item_sets_arr, "-1") . "
                        </div>
                    </div>
                    <div id='div-ddl-item-set-name-form' >
                        <div class='div-any-form-row' >
                            <div class='div-any-form-cell' >
                                <input id='txt_item_set_name' class='txt_control_options' placeholder='" . _x("Enter item set name", "Control options drop down list item sets placeholder (Enter item set name)", "wp-any-form") . "' value='" . $the_item_set_name . "' />
                                &nbsp;
                                " . ASTERISK_HTML_STR . "
                                &nbsp;
                                <input id='btn_submit_item_set' class='button button-primary' type='submit' value='" . ADD_DDL_ITEM_SET_BTN_TEXT . "' />
                                &nbsp;
                                <a id='acmd-admin-item-set_delete' class='acmd-admin-item-set' href='javascript:void(0);' title='" . _x("Delete item set", "Control options drop down list item sets image command title (Delete item set)", "wp-any-form") . "' ><img src='" . plugins_url('/img/delete.png', dirname(__FILE__ )) . "' /></a>
                            </div>  
                        </div>
                        <div class='div-any-form-row' >
                            <div class='div-any-form-cell' >
                                " . biz_logic_wp_custom_get_cbx_html($cbx_is_initial_item_set_options) . "
                                &nbsp;
                                <a id='acmd-help_isinitialitemset' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
                            </div>
                        </div>
                    </div>
                    <div class='div-any-form-row' >
                        <div id='div-ddl-items-container' class='div-any-form-cell' >
                            " . biz_logic_wp_custom_get_ddl_options_items_admin_html($the_field_id, $hval_form_builder_arr, $ddl_item_sets_arr, "-1") . "
                        </div>
                    </div>
                </div>
                " . biz_logic_wp_custom_get_div_clear_html("15") . "
            </div>
        </div>
        " . biz_logic_wp_custom_get_div_clear_html("15") . "
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                " . REQUIRED_HTML_STR . "
            </div>
        </div>
        <input id='hval-field-id' type='hidden' value='" . $the_field_id . "' />
        <input id='hval-editing-item' type='hidden' value='-1' />
    </div>";
    $return_arr = array(
        "html_str" => $html_str,
        "the_dialog_height" => $the_dialog_height,
        "ddl_item_sets_arr" => $ddl_item_sets_arr
    );
    return $return_arr;
}

function biz_logic_wp_custom_get_initial_item_set_i($ddl_item_sets_arr) {
    $the_initial_item_set_i = false;
    if($ddl_item_sets_arr != "none") {
        if(count($ddl_item_sets_arr) > 0) {
            $the_initial_item_set_i = 0;
            for ($i=0; $i < count($ddl_item_sets_arr); $i++) { 
                $ddl_item_set_arr = $ddl_item_sets_arr[$i];    
                $is_initial = $ddl_item_set_arr["is_initial"];
                if($is_initial == "yes") {
                    $the_initial_item_set_i = $i;
                }
            }
        }
    }
    return $the_initial_item_set_i;
}

function biz_logic_wp_custom_get_initial_selected_value_ddl_html($ddl_item_sets_arr, $the_selected_value) {
    $html_str = "";
    $val_desc_arr = array(
        biz_logic_wp_custom_get_unique_id() => _x("Select selected value...", "Control general options drop down list selected value first option", "wp-any-form")
    );
    $at_least_one_found = false;
    if($ddl_item_sets_arr != "none") {
        if(count($ddl_item_sets_arr) > 0) {
            $the_initial_item_set_i = biz_logic_wp_custom_get_initial_item_set_i($ddl_item_sets_arr);
            $ddl_item_set_arr = $ddl_item_sets_arr[$the_initial_item_set_i];
            $items_arr = $ddl_item_set_arr["items_arr"];
            if($items_arr != "none") {
                if(count($items_arr) > 0) {
                    $at_least_one_found = true;
                    foreach ($items_arr as $item_arr) {
                        $item_description = $item_arr["item_description"];
                        $item_value = $item_arr["item_value"];
                        $val_desc_arr[$item_value] = $item_description;
                    }        
                }
            }
        }        
    }
    if(!$at_least_one_found) {
        $val_desc_arr = array(
            biz_logic_wp_custom_get_unique_id() => _x("No items found", "Control general options drop down list selected value no items found option", "wp-any-form")
        );       
    }
    $html_str .= biz_logic_wp_custom_get_ddl_html("", "ddl_initial_selected_value", "", "", $val_desc_arr, $the_selected_value);
    return $html_str;
}

function biz_logic_wp_custom_get_form_ddl_html($the_control_fields_arr) {
    $html_str = "";
    $style_str = "";
    $css_class_str = "ddl_control";
    $the_field_id = $the_control_fields_arr["the_field_id"];
    $the_selected_value = $the_control_fields_arr["the_selected_value"];    
    $the_width = $the_control_fields_arr["the_width"];    
    $is_required = $the_control_fields_arr["is_required"];    
    $the_custom_css_class = $the_control_fields_arr["the_custom_css_class"];
    if($the_width != "") {
        $style_str .= " width: " . $the_width . "px;";
    }
    if($is_required == "yes") {
        $css_class_str .= " is_required";
    }
    if($the_custom_css_class != "") {
        $css_class_str .= " " . $the_custom_css_class;
    }
    $val_desc_arr = array();
    $ddl_item_sets_arr = $the_control_fields_arr["ddl_item_sets_arr"];
    if($ddl_item_sets_arr != "none") {
        if(count($ddl_item_sets_arr) > 0) {
            $the_initial_item_set_i = biz_logic_wp_custom_get_initial_item_set_i($ddl_item_sets_arr);
            $ddl_item_set_arr = $ddl_item_sets_arr[$the_initial_item_set_i];
            $items_arr = $ddl_item_set_arr["items_arr"];
            if($items_arr != "none") {
                if(count($items_arr) > 0) {
                    foreach ($items_arr as $item_arr) {
                        $item_description = $item_arr["item_description"];
                        $item_value = $item_arr["item_value"];
                        $is_not_selected_indicator = $item_arr["is_not_selected_indicator"];
                        $item_sub_option = $item_arr["item_sub_option"];
                        $val_desc_arr[$item_value] = $item_description;
                    }        
                }
            }
        }        
    }
    $html_str .= biz_logic_wp_custom_get_ddl_html("", $the_field_id, $css_class_str, $style_str, $val_desc_arr, $the_selected_value);
    if($is_required == "yes") {
        $html_str .= "<div class='div-ddl-required-span-container' >" . ASTERISK_HTML_STR_USER . "</div>";
    }
    return $html_str;
}

function biz_logic_wp_custom_get_ddl_options_ddl_item_sets_html($ddl_item_sets_arr, $the_selected_item_set_id) {
    $html_str = "";
    $val_desc_arr = array(
        "-1" => _x("Select item set...", "Drop down list add / edit item set form drop down first option", "wp-any-form"),
        "add_new_item_set" => _x("Add new", "Drop down list add / edit item set form drop down option (Add new)", "wp-any-form")
    );
    if($ddl_item_sets_arr != "none") {
        if(count($ddl_item_sets_arr) > 0) {
            foreach ($ddl_item_sets_arr as $ddl_item_set_arr) {
                $item_set_id = $ddl_item_set_arr["item_set_id"];
                $item_set_name = $ddl_item_set_arr["item_set_name"];
                $val_desc_arr[$item_set_id] = $item_set_name;
            }
        }        
    }
    $html_str .= biz_logic_wp_custom_get_ddl_html("", "ddl_item_sets", "", "", $val_desc_arr, $the_selected_item_set_id);
    $html_str .= "<input id='hval-new-ddl-item-set-id' type='hidden' value='" . biz_logic_wp_custom_get_unique_id() . "' />";
    return $html_str;
}

function biz_logic_wp_custom_get_ddl_options_items_admin_html($the_field_id, $hval_form_builder_arr, $ddl_item_sets_arr, $the_selected_item_set_id) {
    $cbx_is_not_selected_indicator_options = array(
        "id_str" => "cbx_is_not_selected_indicator",
        "the_label" => _x("Item indicates if field has a selected value", "Drop down list add / edit item form checkbox label text", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => ""
    );    
    $html_str = "";
    if($ddl_item_sets_arr != "none" && $the_selected_item_set_id != "-1") {
        if(count($ddl_item_sets_arr) > 0) {
            $html_str .= "
        <div class='div-any-form-row' >
            <div id='div-ddl-item-set-form-heading' class='div-any-form-cell lbl w135 class-bold' >
                " . ADD_DDL_ITEM_HEADING_TEXT . "
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                <input id='txt_item_description' class='txt_control_options' placeholder='" . _x("Enter item description", "Drop down list add / edit item form placeholder (Enter item description)", "wp-any-form") . "' value='" . $the_item_description . "' />
                &nbsp;
                " . ASTERISK_HTML_STR . "                    
            </div>
            <div class='div-any-form-cell' >
                <input id='txt_item_value' class='txt_control_options' placeholder='" . _x("Enter item value", "Drop down list add / edit item form placeholder (Enter item value)", "wp-any-form") . "' value='" . $the_item_value . "' />
                &nbsp;
                " . ASTERISK_HTML_STR . "
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                " . biz_logic_wp_custom_get_ddl_item_sub_options_ddl_html($hval_form_builder_arr, $the_field_id) . "
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell div-any-form-cell-width-600' >
                " . biz_logic_wp_custom_get_cbx_html($cbx_is_not_selected_indicator_options) . "       
                &nbsp;
                <a id='acmd-help_ddlhasselectedvalueindicator' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                <input id='btn_submit_item_form' class='button button-primary' type='submit' value='" . ADD_DDL_ITEM_BTN_TEXT . "' />
                <input id='btn_cancel_edit_item_form' class='button button-primary' type='submit' value='" . _x("Cancel", "Drop down list add / edit item form button text (cancel)", "wp-any-form") . "' />
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl w135 class-bold' >
                " . _x("Items:", "Drop down list add / edit item form heading (Items)", "wp-any-form") . "
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >";
            if($ddl_item_sets_arr != "none") {
                if(count($ddl_item_sets_arr) > 0) {
                    $has_items = "no";
                    $ddl_item_set_arr = biz_logic_wp_custom_ddl_get_item_set_arr_from_item_sets_arr($ddl_item_sets_arr, $the_selected_item_set_id);
                    if($ddl_item_set_arr) {
                        $items_arr = $ddl_item_set_arr["items_arr"];
                        if($items_arr != "none") {
                            if(count($items_arr) > 0) {
                                $has_items = "yes";
                            }
                        }    
                    }
                    if($has_items == "yes") {
                            $html_str .= "
                <ul id='ul-admin-items-arr' >";
                            $the_items_arr_count = count($items_arr);
                            for ($i=0; $i < $the_items_arr_count; $i++) { 
                                $item_arr = $items_arr[$i];
                                $html_str .= "
                    <li id='ul-admin-ddl-set-item_" . $i . "' >
                        <div class='div-any-form-cell-li-item-arr div-any-form-cell' >" . $item_arr["item_description"] . "</div>
                        <div class='div-any-form-cell' >
                            <a id='acmd-admin-items-arr_edit_" . ($i+1) . "' class='acmd-admin-items-arr' href='javascript:void(0);' title='" . CONTROL_OPTIONS_ITEMS_LIST_CMD_TITLE_EDIT . "' ><img src='" . plugins_url('/img/pencil.png', dirname(__FILE__ )) . "' /></a>
                            <a id='acmd-admin-items-arr_delete_" . ($i+1) . "' class='acmd-admin-items-arr' href='javascript:void(0);' title='" . CONTROL_OPTIONS_ITEMS_LIST_CMD_TITLE_DELETE . "' ><img src='" . plugins_url('/img/delete.png', dirname(__FILE__ )) . "' /></a>
                        </div>
                        " . biz_logic_wp_custom_get_div_clear_html(false) . "
                    </li>";
                            }
                            $html_str .= "
                </ul>";
                    } else {
                        $html_str .= CONTROL_OPTIONS_ITEMS_LIST_MSG_NO_ITEMS_FOUND;            
                    }    
                }
            }
        }
        $html_str .= "
            </div>
        </div>" . biz_logic_wp_custom_get_div_clear_html("15");
    }
    return $html_str;
}

function biz_logic_wp_custom_ddl_get_item_set_arr_from_item_sets_arr($ddl_item_sets_arr, $the_selected_item_set_id) {
    if($ddl_item_sets_arr != "none") {
        if(count($ddl_item_sets_arr) > 0) {
            foreach ($ddl_item_sets_arr as $ddl_item_set_arr) {
                $item_set_id = $ddl_item_set_arr["item_set_id"];
                if($item_set_id == $the_selected_item_set_id) {
                    return $ddl_item_set_arr;
                }
            }
        }
    }
    return false;
}

function biz_logic_wp_custom_get_ddl_item_sub_options_ddl_html($hval_form_builder_arr, $the_field_id) {
    $html_str = "";
    $val_desc_arr = array("none" => _x("No sub drop down list", "Drop down list add / edit item form drop down list option (sub ddl)", "wp-any-form"));
    if(count($hval_form_builder_arr) > 0) {
        foreach ($hval_form_builder_arr as $the_control_fields_arr) {
            switch($the_control_fields_arr["the_control_type"]) {
                case "ddl":
                    if($the_control_fields_arr["the_field_id"] != $the_field_id) {
                        $ddl_item_sets_arr = $the_control_fields_arr["ddl_item_sets_arr"];
                        if($ddl_item_sets_arr != "none") {
                            if(count($ddl_item_sets_arr) > 0) {
                                /*if(!biz_logic_wp_custom_in_array_r($the_selected_item_set_id, $ddl_item_sets_arr)) {*/
                                    foreach ($ddl_item_sets_arr as $ddl_item_set_arr) {
                                        $item_set_id = $ddl_item_set_arr["item_set_id"];
                                        $item_set_name = $ddl_item_set_arr["item_set_name"];
                                        $val_desc_arr[$item_set_id] = $item_set_name;    
                                    }
                                /*}*/
                            }    
                        }    
                    }
                    break;
            }
        }
    }
    $html_str .= biz_logic_wp_custom_get_ddl_html("", "ddl_item_sub_options", "", "", $val_desc_arr, "");    
    return $html_str;    
}

?>