<?php

function biz_logic_wp_custom_get_cbx_html($cbx_options) {
	$html_str = "";
	$name_str = "";
	if (array_key_exists("name_str", $cbx_options)) {
		$name_str = $cbx_options["name_str"];	
	}
	$id_str = "";
	if (array_key_exists("id_str", $cbx_options)) {
		$id_str = $cbx_options["id_str"];	
	}
	$class_str = "";
	if (array_key_exists("class_str", $cbx_options)) {
		$class_str = $cbx_options["class_str"];	
	}
	$style_str = "";
	if (array_key_exists("style_str", $cbx_options)) {
		$style_str = $cbx_options["style_str"];	
	}
	$the_value = "";
	if (array_key_exists("the_value", $cbx_options)) {
		$the_value = $cbx_options["the_value"];	
	}
	$the_label = "";
	if (array_key_exists("the_label", $cbx_options)) {
		$the_label = $cbx_options["the_label"];	
	}
	$checked_str = "";
	if (array_key_exists("checked_str", $cbx_options)) {
		$checked_str = $cbx_options["checked_str"];	
	}
	$html_str .= "<input ";
	if ($name_str != "") {
		$html_str .= "name='" . $name_str . "' ";
	} 
	if ($id_str != "") {
		$html_str .= "id='" . $id_str . "' ";
	}
	if ($class_str != "") {
		$html_str .= "class='" . $class_str . "' ";
	}
	if ($style_str != "") {
		$html_str .= "style='" . $style_str . "' ";
	}
	$html_str .= "type='checkbox' value='" . $the_value . "' ";
	if ($the_value == $checked_str) {
		if($the_value != "" || $checked_str != "") {
			$html_str .= "checked=checked ";	
		}
	}
	$html_str .= "/> " . $the_label;
	return $html_str;
}

function biz_logic_wp_custom_get_cbx_control_options_arr($the_action_cmd, $the_control_fields_arr) {
    $html_str = "";
    $the_dialog_height = "700";
    $the_field_name = "";
    $the_field_id = "";
    $the_font_size = "";
    $the_font_weight = "";
    $the_font_colour = "";
    $the_font_colour_use_control_defined = "";
    $use_flexi_width = "";
    $cbxs_per_row = "";
    $the_custom_css_class = "";
    $is_required = "";
    $saved_data_values_separator = "";
    $items_arr = "";   
    switch ($the_action_cmd) {
        case "add":
            $the_field_id = biz_logic_wp_custom_get_unique_id();    
            $items_arr = "none";
            break;
        case "edit":
            $the_field_name = $the_control_fields_arr["the_field_name"];    
            $the_field_id = $the_control_fields_arr["the_field_id"];
            $the_font_size = $the_control_fields_arr["the_font_size"];    
            $the_font_weight = $the_control_fields_arr["the_font_weight"]; 
            $the_font_colour = $the_control_fields_arr["the_font_colour"];   
            $the_font_colour_use_control_defined = $the_control_fields_arr["the_font_colour_use_control_defined"];    
            $use_flexi_width = $the_control_fields_arr["use_flexi_width"];
            $cbxs_per_row = $the_control_fields_arr["cbxs_per_row"];    
            $the_custom_css_class = $the_control_fields_arr["the_custom_css_class"];
            $is_required = $the_control_fields_arr["is_required"]; 
            $saved_data_values_separator = $the_control_fields_arr["saved_data_values_separator"];
            $items_arr = $the_control_fields_arr["items_arr"];   
            break;    
    }
    $cbx_options_is_required = array(
        "id_str" => "cbx_control_is_required",
        "the_label" =>  _x("Required (At least one checked)", "Checkbox control options required checkbox label text", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => $is_required
    );   
    $ddl_saved_data_values_separator_options = array(
        "," => _x("Comma", "Checkbox control options saved data values separator options (Comma)", "wp-any-form") . " ,",
        ";" => _x("Semicolon", "Checkbox control options saved data values separator options (Semicolon)", "wp-any-form") . " ;",
        "|" => _x("Vertical bar", "Checkbox control options saved data values separator options (Vertical bar)", "wp-any-form") . " |",
        "single_space" => _x("Single space", "Checkbox control options saved data values separator options (Single space)", "wp-any-form"),
        "new_line" => _x("New line", "Checkbox control options saved data values separator options (New line)", "wp-any-form")
    );
    $cbx_font_colour_use_control_defined_options = array(
        "id_str" => "cbx_font_colour_use_control_defined",
        "class_str" => "",
        "the_label" => CONTROL_OPTIONS_STYLE_CBX_USE_DEFINED_FONT_COLOUR,
        "the_value" => "yes",
        "checked_str" => $the_font_colour_use_control_defined
    );
    $cbx_use_flexi_width_options = array(
        "id_str" => "cbx_use_flexi_width",
        "class_str" => "",
        "the_label" => _x("Space checkboxes automatically", "Checkbox control options spacing checkbox label text", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => $use_flexi_width
    );
    $cbx_initial_value_options = array(
        "id_str" => "cbx_initial_value",
        "the_label" => _x("Initial checked value", "Checkbox control options initial value checkbox label text", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => ""
    );
    $cbx_must_be_checked_options = array(
        "id_str" => "cbx_must_be_checked",
        "the_label" => _x("Must be checked", "Checkbox control options must be checked checkbox label text", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => ""
    );
    $html_str .= "
    <div class='div-control-options-container' >
        <div id='div-control-options-form-msg' class='lblmsg' ></div>
        <div id='div-control-options-tabs' >
            <ul>
                <li><a href='#tabs-general' >" . CONTROL_OPTIONS_TABS_HEADING_GENERAL . "</a></li>
                <li><a href='#tabs-style' >" . CONTROL_OPTIONS_TABS_HEADING_STYLE . "</a></li>
                <li><a href='#tabs-item-set' >" . _x("Checkbox set", "Control options tabs heading (Checkbox set)", "wp-any-form") . "</a></li>
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
                        " . _x("Saved data values separator:", "Checkbox control options saved data values separator label", "wp-any-form") . "
                    </div>
                    <div class='div-any-form-cell' >
                        " . biz_logic_wp_custom_get_ddl_html("", "ddl_saved_data_values_separator", "", "", $ddl_saved_data_values_separator_options, $saved_data_values_separator) . "
                        &nbsp;
                        " . ASTERISK_HTML_STR . "
                    </div>  
                    <div class='div-any-form-cell' >
                        <a id='acmd-help_cbxdatavaluesseparator' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
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
                    <div class='div-any-form-cell' >
                        <a id='acmd-help_cbxrequired' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
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
                        " . CONTROL_OPTIONS_STYLE_LABEL_NUMBER_PER_ROW . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_cbxs_per_row' class='txt_control_options numbers_only' placeholder='" . CONTROL_OPTIONS_STYLE_PLACEHOLDER_NUMBER_PER_ROW . "' value='" . $cbxs_per_row . "' />
                    </div>
                    <div class='div-any-form-cell' >
                        <a id='acmd-help_cbxsperrow' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w165' >
                        " . CONTROL_OPTIONS_STYLE_LABEL_SPACING . "
                    </div>
                    <div class='div-any-form-cell' >
                        " . biz_logic_wp_custom_get_cbx_html($cbx_use_flexi_width_options) . "
                    </div>
                    <div class='div-any-form-cell' >
                        <a id='acmd-help_cbxuseflexiwidth' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
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
            <div id='tabs-item-set' >
                <div id='div-cbx-options-item-set-form' class='div-any-form-row' >
                	<div id='div-cbx-options-item-set-form-msg' class='lblmsg' ></div>
                    <div class='div-any-form-cell' >
                        <div class='div-any-form-row' >
				            <div id='div-cbx-item-set-form-heading' class='div-any-form-cell lbl class-bold' >
				                " . ADD_CBX_HEADING_TEXT . "
				            </div>
				        </div>
				        <div class='div-any-form-row' >
				            <div class='div-any-form-cell' >
				                <input id='txt_item_description' class='txt_control_options' placeholder='" . _x("Enter label", "Checkbox add / edit item form placeholder (Enter label)", "wp-any-form") . "' value='' />
				                &nbsp;
				                " . ASTERISK_HTML_STR . "                    
				            </div>
				            <div class='div-any-form-cell' >
				                <input id='txt_item_value' class='txt_control_options' placeholder='" . _x("Enter value", "Checkbox add / edit item form placeholder (Enter value)", "wp-any-form") . "' value='' />
				                &nbsp;
				                " . ASTERISK_HTML_STR . "
				            </div>
				        </div>
                        <div class='div-any-form-row' >
                            <div class='div-any-form-cell' >
                                " . biz_logic_wp_custom_get_cbx_html($cbx_initial_value_options) . "       
                            </div>
                        </div>
                        <div class='div-any-form-row' >
                            <div class='div-any-form-cell' >
                                " . biz_logic_wp_custom_get_cbx_html($cbx_must_be_checked_options) . "       
                                &nbsp;
                                <a id='acmd-help_cbxmustbechecked' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
                            </div>
                        </div>
                        <div id='div-any-form-row_must_be_checked_error_msg' class='div-any-form-row' >
                            <div class='div-any-form-cell' >
                                <input id='txt_must_be_checked_error_msg' class='txt_control_options' placeholder='" . _x("Enter validation error message", "Checkbox add / edit item form placeholder (Enter validation error message)", "wp-any-form") . "' value='' />
                                &nbsp;
                                " . ASTERISK_HTML_STR . "
                                &nbsp;
                                <a id='acmd-help_cbxmustbecheckederrormsg' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
                            </div>
                        </div>
				        <div class='div-any-form-row' >
				            <div class='div-any-form-cell' >
				                <input id='btn_submit_item_form' class='button button-primary' type='submit' value='" . ADD_CBX_BTN_TEXT . "' />
				                <input id='btn_cancel_edit_item_form' class='button button-primary' type='submit' value='" . _x("Cancel", "Checkbox add / edit item form button text (cancel)", "wp-any-form") . "' />
				            </div>
				        </div>
				        <div class='div-any-form-row' >
				            <div class='div-any-form-cell lbl w135 class-bold' >
				                " . _x("Items:", "Checkbox add / edit item form heading (Items)", "wp-any-form") . "
				            </div>
				        </div>
				        <div class='div-any-form-row' >
				            <div id='div-cbx-items-container' class='div-any-form-cell' >
				            	" . biz_logic_wp_custom_get_cbx_items_admin_html($items_arr) . "
				            </div>
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
        "items_arr" => $items_arr
    );
    return $return_arr;
}

function biz_logic_wp_custom_get_cbx_items_admin_html($items_arr) {
    $html_str = "";
    $has_items = "no";
    if($items_arr != "none") {
        if(count($items_arr) > 0) {
            $has_items = "yes";
        }
    }    
    if($has_items == "yes") {
        $html_str .= "
	<ul id='ul-admin-items-arr' >";
        $the_items_arr_count = count($items_arr);
        for ($i=0; $i < $the_items_arr_count; $i++) { 
            $item_arr = $items_arr[$i];
            $html_str .= "
		<li id='ul-admin-cbx-set-item_" . $i . "' >
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
    return $html_str;
}

function biz_logic_wp_custom_get_form_cbx_html($the_control_fields_arr) {
    $html_str = "";
    $css_class_str = "cbx_control";
    $the_field_id = $the_control_fields_arr["the_field_id"];
    $is_required = $the_control_fields_arr["is_required"];
    $cbxs_per_row = $the_control_fields_arr["cbxs_per_row"];
    $css_class_str .= " " . $the_field_id;
    $the_custom_css_class = $the_control_fields_arr["the_custom_css_class"];
    $items_arr = $the_control_fields_arr["items_arr"];
    if($items_arr != "none") {
        if(count($items_arr) > 0) {
            $html_str .= "<div id='div-cbx-set-container_" . $the_field_id . "' class='div-cbx-set-container";
            if($the_custom_css_class != "") {
                $html_str .= " " . $the_custom_css_class;
            }
            $html_str .= "' >";
            $cbx_cell_counteri = 0;
            $cbx_total_counteri = 0;
            foreach ($items_arr as $item_arr) {
                $cbx_cell_counteri += 1;
                $cbx_total_counteri += 1;
                $the_label = $item_arr["item_description"];
                $the_value = $item_arr["item_value"];
                $initial_value = $item_arr["initial_value"];
                $must_be_checked = $item_arr["must_be_checked"];
                if($initial_value == "yes") {
                    $checked_str = $the_value;
                }
                if($must_be_checked == "yes") {
                    $css_class_str .= " must_be_checked";
                }
                $id_str = "cbx" . $the_field_id . "_" . $cbx_total_counteri;
                $cbx_options = array(
                    "id_str" => $id_str,
                    "name_str" => $the_field_id,
                    "class_str" => $css_class_str,
                    "the_label" => $the_label,
                    "the_value" => $the_value,
                    "checked_str" => $checked_str
                );
                if($cbxs_per_row != "" && $cbx_cell_counteri == 1) {
                    $html_str .= "<div class='div-cbx-set-container-row' >";    
                }
                $the_cell_id_str = "div-cbx-set-container-cell_" . $the_field_id . "_" . $cbx_total_counteri;
                $html_str .= "<div id='" . $the_cell_id_str . "' class='div-cbx-set-container-cell' >" . biz_logic_wp_custom_get_cbx_html($cbx_options) . "</div>";
                if($cbx_total_counteri == count($items_arr)) {
                    if($is_required == "yes") {
                        $html_str .= "<div class='div-cbx-required-span-container' >" . ASTERISK_HTML_STR_USER . "</div>";
                    }
                }
                if($cbxs_per_row != "") {
                    if($cbx_cell_counteri == $cbxs_per_row || $cbx_total_counteri == count($items_arr)) {
                        $html_str .= "</div>";       
                        $cbx_cell_counteri = 0;
                    }
                }
            }     
            $html_str .= "</div>";
        }
    }
    return $html_str;
}

?>