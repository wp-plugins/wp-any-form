<?php

function biz_logic_wp_custom_get_rbtn_html($rbtn_options) {
	$html_str = "";
	$name_str = "";
	if (array_key_exists("name_str", $rbtn_options)) {
		$name_str = $rbtn_options["name_str"];	
	}
	$id_str = "";
	if (array_key_exists("id_str", $rbtn_options)) {
		$id_str = $rbtn_options["id_str"];	
	}
	$class_str = "";
	if (array_key_exists("class_str", $rbtn_options)) {
		$class_str = $rbtn_options["class_str"];	
	}
	$style_str = "";
	if (array_key_exists("style_str", $rbtn_options)) {
		$style_str = $rbtn_options["style_str"];	
	}
	$the_value = "";
	if (array_key_exists("the_value", $rbtn_options)) {
		$the_value = $rbtn_options["the_value"];	
	}
	$the_label = "";
	if (array_key_exists("the_label", $rbtn_options)) {
		$the_label = $rbtn_options["the_label"];	
	}
	$checked_str = "";
	if (array_key_exists("checked_str", $rbtn_options)) {
		$checked_str = $rbtn_options["checked_str"];	
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
	$html_str .= "type='radio' value='" . $the_value . "' ";
	if ($the_value == $checked_str) {
		if($the_value != "" || $checked_str != "") {
			$html_str .= "checked=checked ";	
		}
	}
	$html_str .= "/> " . $the_label;
	return $html_str;
}

function biz_logic_wp_custom_get_rbtn_control_options_arr($the_action_cmd, $the_control_fields_arr) {
    $html_str = "";
    $the_dialog_height = "700";
    $the_field_name = "";
    $the_field_id = "";
    $the_font_size = "";
    $the_font_weight = "";
    $the_font_colour = "";
    $the_font_colour_use_control_defined = "";
    $use_flexi_width = "";
    $rbtns_per_row = "";
    $the_custom_css_class = "";
    $is_required = "";
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
            $rbtns_per_row = $the_control_fields_arr["rbtns_per_row"];    
            $the_custom_css_class = $the_control_fields_arr["the_custom_css_class"];
            $is_required = $the_control_fields_arr["is_required"]; 
            $items_arr = $the_control_fields_arr["items_arr"];   
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
    $cbx_use_flexi_width_options = array(
        "id_str" => "cbx_use_flexi_width",
        "class_str" => "",
        "the_label" => _x("Space radio buttons automatically", "Radio button control options spacing checkbox label text", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => $use_flexi_width
    );
    $rbtn_initial_value_yes_options = array(
        "id_str" => "rbtn_initial_value_yes",
        "name_str" => "rbtn_initial_value",
        "the_label" => _x("Checked", "Radio button add / edit item form initial value radio button label text (Checked)", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => ""
    );
    $rbtn_initial_value_no_options = array(
        "id_str" => "rbtn_initial_value_no",
        "name_str" => "rbtn_initial_value",
        "the_label" => _x("Not checked", "Radio button add / edit item form initial value radio button label text (Not checked)", "wp-any-form"),
        "the_value" => "no",
        "checked_str" => ""
    );
    $html_str .= "
    <div class='div-control-options-container' >
        <div id='div-control-options-form-msg' class='lblmsg' ></div>
        <div id='div-control-options-tabs' >
            <ul>
                <li><a href='#tabs-general' >" . CONTROL_OPTIONS_TABS_HEADING_GENERAL . "</a></li>
                <li><a href='#tabs-style' >" . CONTROL_OPTIONS_TABS_HEADING_STYLE . "</a></li>
                <li><a href='#tabs-item-set' >" . _x("Radio button set", "Control options tabs heading (Radio button set)", "wp-any-form") . "</a></li>
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
                        " . CONTROL_OPTIONS_STYLE_LABEL_NUMBER_PER_ROW . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_rbtns_per_row' class='txt_control_options numbers_only' placeholder='" . CONTROL_OPTIONS_STYLE_PLACEHOLDER_NUMBER_PER_ROW . "' value='" . $rbtns_per_row . "' />
                    </div>
                    <div class='div-any-form-cell' >
                        <a id='acmd-help_rbtnsperrow' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
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
                        <a id='acmd-help_rbtnuseflexiwidth' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
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
                <div id='div-rbtn-options-item-set-form' class='div-any-form-row' >
                	<div id='div-rbtn-options-item-set-form-msg' class='lblmsg' ></div>
                    <div class='div-any-form-cell' >
                        <div class='div-any-form-row' >
				            <div id='div-rbtn-item-set-form-heading' class='div-any-form-cell lbl class-bold' >
				                " . ADD_RBTN_HEADING_TEXT . "
				            </div>
				        </div>
				        <div class='div-any-form-row' >
				            <div class='div-any-form-cell' >
				                <input id='txt_item_description' class='txt_control_options' placeholder='" . _x("Enter label", "Radio button add / edit item form placeholder (Enter label)", "wp-any-form") . "' value='' />
				                &nbsp;
				                " . ASTERISK_HTML_STR . "                    
				            </div>
				            <div class='div-any-form-cell' >
				                <input id='txt_item_value' class='txt_control_options' placeholder='" . _x("Enter value", "Radio button add / edit item form placeholder (Enter value)", "wp-any-form") . "' value='' />
				                &nbsp;
				                " . ASTERISK_HTML_STR . "
				            </div>
				        </div>
                        <div class='div-any-form-row' >
                        	<div class='div-any-form-cell lbl w135' >
                        		" . _x("Initial value:", "Radio button add / edit item form label (Initial value)", "wp-any-form") . "
                    		</div>
                            <div class='div-any-form-cell' >
                            	<div class='div-rbtn-set-container' >
                            		<div class='div-rbtn-set-container-row' >
                            			<div class='div-rbtn-set-container-cell' >
                            				" . biz_logic_wp_custom_get_rbtn_html($rbtn_initial_value_yes_options) . "       
                            			</div>
                            			<div class='div-rbtn-set-container-cell' >
											" . biz_logic_wp_custom_get_rbtn_html($rbtn_initial_value_no_options) . "       
                            			</div>
                            		</div>
                            	</div>
                            </div>
                        </div>
				        <div class='div-any-form-row' >
				            <div class='div-any-form-cell' >
				                <input id='btn_submit_item_form' class='button button-primary' type='submit' value='" . ADD_RBTN_BTN_TEXT . "' />
				                <input id='btn_cancel_edit_item_form' class='button button-primary' type='submit' value='" . _x("Cancel", "Radio button add / edit item form button text (cancel)", "wp-any-form") . "' />
				            </div>
				        </div>
				        <div class='div-any-form-row' >
				            <div class='div-any-form-cell lbl w135 class-bold' >
				                " . _x("Items:", "Radio button add / edit item form heading (Items)", "wp-any-form") . "
				            </div>
				        </div>
				        <div class='div-any-form-row' >
				            <div id='div-rbtn-items-container' class='div-any-form-cell' >
				            	" . biz_logic_wp_custom_get_rbtn_items_admin_html($items_arr) . "
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

function biz_logic_wp_custom_get_rbtn_items_admin_html($items_arr) {
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
		<li id='ul-admin-rbtn-set-item_" . $i . "' >
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

function biz_logic_wp_custom_get_form_rbtn_html($the_control_fields_arr) {
    $html_str = "";
    $css_class_str = "rbtn_control";
    $the_field_id = $the_control_fields_arr["the_field_id"];
    $is_required = $the_control_fields_arr["is_required"];
    $rbtns_per_row = $the_control_fields_arr["rbtns_per_row"];
    $css_class_str .= " " . $the_field_id;
    $the_custom_css_class = $the_control_fields_arr["the_custom_css_class"];
    $items_arr = $the_control_fields_arr["items_arr"];
    if($items_arr != "none") {
        if(count($items_arr) > 0) {
            $html_str .= "<div id='div-rbtn-set-container_" . $the_field_id . "' class='div-rbtn-set-container";
            if($the_custom_css_class != "") {
                $html_str .= " " . $the_custom_css_class;
            }
            $html_str .= "' >";
            $rbtn_cell_counteri = 0;
            $rbtn_total_counteri = 0;
            foreach ($items_arr as $item_arr) {
                $rbtn_cell_counteri += 1;
                $rbtn_total_counteri += 1;
                $the_label = $item_arr["item_description"];
                $the_value = $item_arr["item_value"];
                $initial_value = $item_arr["initial_value"];
                if($initial_value == "yes") {
                    $checked_str = $the_value;
                }
                $id_str = "rbtn" . $the_field_id . "_" . $rbtn_total_counteri;
                $rbtn_options = array(
                    "id_str" => $id_str,
                    "name_str" => $the_field_id,
                    "class_str" => $css_class_str,
                    "the_label" => $the_label,
                    "the_value" => $the_value,
                    "checked_str" => $checked_str
                );
                if($rbtns_per_row != "" && $rbtn_cell_counteri == 1) {
                    $html_str .= "<div class='div-rbtn-set-container-row' >";    
                }
                $the_cell_id_str = "div-rbtn-set-container-cell_" . $the_field_id . "_" . $rbtn_total_counteri;
                $html_str .= "<div id='" . $the_cell_id_str . "' class='div-rbtn-set-container-cell' >" . biz_logic_wp_custom_get_rbtn_html($rbtn_options) . "</div>";
                if($rbtn_total_counteri == count($items_arr)) {
                    if($is_required == "yes") {
                        $html_str .= "<div class='div-rbtn-required-span-container' >" . ASTERISK_HTML_STR_USER . "</div>";
                    }
                }
                if($rbtns_per_row != "") {
                    if($rbtn_cell_counteri == $rbtns_per_row || $rbtn_total_counteri == count($items_arr)) {
                        $html_str .= "</div>";       
                        $rbtn_cell_counteri = 0;
                    }
                }
            }     
            $html_str .= "</div>";
        }
    }
    return $html_str;
}

?>