<?php

function biz_logic_wp_custom_get_txta_control_options_arr($the_action_cmd, $the_control_fields_arr) {
    $html_str = "";
    $the_dialog_height = "700";
    $the_field_name = "";
    $the_field_id = "";
    $the_value = "";
    $the_font_size = "";
    $the_font_weight = "";
    $the_font_colour = "";
    $the_font_colour_use_control_defined = "";
    $the_width = "";
    $the_height = "";
    $the_custom_css_class = "";
    $the_placeholder = "";
    $the_max_length = "";
    $is_required = "";
    switch ($the_action_cmd) {
        case "add":
            $the_field_id = biz_logic_wp_custom_get_unique_id();    
            break;
        case "edit":
            $the_field_name = $the_control_fields_arr["the_field_name"];    
            $the_field_id = $the_control_fields_arr["the_field_id"];
            $the_value = $the_control_fields_arr["the_value"];    
            $the_font_size = $the_control_fields_arr["the_font_size"];    
            $the_font_weight = $the_control_fields_arr["the_font_weight"]; 
            $the_font_colour = $the_control_fields_arr["the_font_colour"];   
            $the_font_colour_use_control_defined = $the_control_fields_arr["the_font_colour_use_control_defined"];    
            $the_width = $the_control_fields_arr["the_width"];    
            $the_height = $the_control_fields_arr["the_height"];    
            $the_custom_css_class = $the_control_fields_arr["the_custom_css_class"];
            $the_placeholder = $the_control_fields_arr["the_placeholder"];    
            $the_max_length = $the_control_fields_arr["the_max_length"];    
            $is_required = $the_control_fields_arr["is_required"];    
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
    $html_str .= "
    <div class='div-control-options-container' >
        <div id='div-control-options-form-msg' class='lblmsg' ></div>
        <div id='div-control-options-tabs' >
            <ul>
                <li><a href='#tabs-general' >" . CONTROL_OPTIONS_TABS_HEADING_GENERAL . "</a></li>
                <li><a href='#tabs-style' >" . CONTROL_OPTIONS_TABS_HEADING_STYLE . "</a></li>
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
                        " . _x("Value:", "Control options text area general label (Value)", "wp-any-form") . "
                    </div>
                    <div class='div-any-form-cell' >
                        <textarea id='txta_value' class='txta_control_options' placeholder='" . _x("Enter value", "Control options text area general placeholder (Enter value)", "wp-any-form") . "' >" . $the_value . "</textarea>
                    </div>  
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w135' >
                        " . _x("Placeholder:", "Control options text area general label (Placeholder)", "wp-any-form") . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_placeholder' class='txt_control_options' placeholder='" . _x("Enter placeholder", "Control options text area general placeholder (Enter placeholder)", "wp-any-form") . "' value='" . $the_placeholder . "' />
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w135' >
                        " . _x("Max length:", "Control options text area general label (Max length)", "wp-any-form") . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_max_length' class='txt_control_options numbers_only' placeholder='" . _x("Enter max length", "Control options text area general placeholder (Enter max length)", "wp-any-form") . "' value='" . $the_max_length . "' />
                        &nbsp;
                        (of characters entered)
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
                        " . CONTROL_OPTIONS_STYLE_LABEL_HEIGHT . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_height' class='txt_control_options numbers_only' placeholder='" . CONTROL_OPTIONS_STYLE_PLACEHOLDER_HEIGHT . "' value='" . $the_height . "' />
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
        </div>
        " . biz_logic_wp_custom_get_div_clear_html("15") . "
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                " . REQUIRED_HTML_STR . "
            </div>
        </div>
        <input id='hval-field-id' type='hidden' value='" . $the_field_id . "' />
    </div>";
    $return_arr = array(
        "html_str" => $html_str,
        "the_dialog_height" => $the_dialog_height
    );
    return $return_arr;
}

function biz_logic_wp_custom_get_txta_html($the_control_fields_arr) {
	$html_str = "";
	$style_str = "";
	$css_class_str = "txta_control";
	$the_field_id = $the_control_fields_arr["the_field_id"];
    $the_value = $the_control_fields_arr["the_value"];    
    $the_width = $the_control_fields_arr["the_width"];    
    $the_height = $the_control_fields_arr["the_height"];    
    $the_placeholder = $the_control_fields_arr["the_placeholder"];    
    $the_max_length = $the_control_fields_arr["the_max_length"];    
    $is_required = $the_control_fields_arr["is_required"];    
    $the_custom_css_class = $the_control_fields_arr["the_custom_css_class"];
    $html_str .= "<textarea id='" . $the_field_id . "'";
    if($the_placeholder != "") {
    	$html_str .= " placeholder='" . $the_placeholder . "'";
    }
    if($the_max_length != "") {
    	$html_str .= " maxlength='" . $the_max_length . "'";
    }
    if($the_height != "") {
    	$style_str .= " height: " . $the_height . "px;";
    }
    if($the_width != "") {
    	$style_str .= " width: " . $the_width . "px;";
    }
    if($style_str != "") {
    	$html_str .= " style='" . $style_str . "'";	
    }
    if($is_required == "yes") {
    	$css_class_str .= " is_required";
    }
    if($the_custom_css_class != "") {
        $css_class_str .= " " . $the_custom_css_class;
    }
    if($css_class_str != "") {
    	$html_str .= " class='" . $css_class_str . "'";		
    }
    $html_str .= " >" . $the_value . "</textarea>";
    if($is_required == "yes") {
    	$html_str .= " " . ASTERISK_HTML_STR_USER;
    }
	return $html_str;
}

?>