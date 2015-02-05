<?php

function biz_logic_wp_custom_get_btnsubmit_control_options_arr($the_action_cmd, $the_control_fields_arr) {
    $html_str = "";
    $the_dialog_height = "600";
    $the_btn_id = "";
    $the_text = "";
    $the_font_size = "";
    $the_font_weight = "";
    $the_font_colour = "";
    $the_font_colour_use_control_defined = "";
    $the_control_align = "";
    $the_custom_css_class = "";
    switch ($the_action_cmd) {
        case "add":
            $the_btn_id = biz_logic_wp_custom_get_unique_id();    
            break;
        case "edit":
            $the_btn_id = $the_control_fields_arr["the_btn_id"];    
            $the_text = $the_control_fields_arr["the_text"];    
            $the_font_size = $the_control_fields_arr["the_font_size"];    
            $the_font_weight = $the_control_fields_arr["the_font_weight"];    
            $the_font_colour = $the_control_fields_arr["the_font_colour"];    
            $the_font_colour_use_control_defined = $the_control_fields_arr["the_font_colour_use_control_defined"];    
            $the_control_align = $the_control_fields_arr["the_control_align"];
            $the_custom_css_class = $the_control_fields_arr["the_custom_css_class"];
            break;    
    }
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
                    <div class='div-any-form-cell lbl w105' >
                        " . _x("Text:", "Submit button options button text label", "wp-any-form") . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_btnsubmit_text' class='txt_control_options' placeholder='" . _x("Enter text", "Submit button options button text placeholder", "wp-any-form") . "' value='" . $the_text . "' />
                        &nbsp;
                        " . ASTERISK_HTML_STR . "
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
                        <input id='txt_btnsubmit_font_size' class='txt_control_options txt_default_font_size numbers_only' value='" . $the_font_size . "' />
                        &nbsp;
                        px
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w165' >
                        " . CONTROL_OPTIONS_STYLE_LABEL_FONT_WEIGHT . "
                    </div>
                    <div class='div-any-form-cell' >
                        " . biz_logic_wp_custom_get_font_weight_ddl_html_str("", "ddl_form_btnsubmit_font_weight", $the_font_weight) . "
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w165' >
                        " . CONTROL_OPTIONS_STYLE_LABEL_FONT_COLOUR . "
                    </div>
                    <div class='div-any-form-cell' >
                        <input id='txt_btnsubmit_font_colour' class='txt_control_options txt_font_colour' value='" . $the_font_colour . "' />
                        &nbsp;
                        " . biz_logic_wp_custom_get_cbx_html($cbx_font_colour_use_control_defined_options) . "
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w165' >
                        " . CONTROL_OPTIONS_STYLE_LABEL_ALIGN . "
                    </div>
                    <div class='div-any-form-cell' >
                        " . biz_logic_wp_custom_get_form_control_align_ddl_html_str("", "ddl_form_control_align", $the_control_align) . "
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
        <input id='hval-btn-id' type='hidden' value='" . $the_btn_id . "' />
    </div>";
    $return_arr = array(
        "html_str" => $html_str,
        "the_dialog_height" => $the_dialog_height
    );
    return $return_arr;
}

function biz_logic_wp_custom_get_btnsubmit_html($the_control_fields_arr) {
	$html_str = "";
    $the_btn_id = $the_control_fields_arr["the_btn_id"];    
	$the_text = $the_control_fields_arr["the_text"];    
    $the_custom_css_class = $the_control_fields_arr["the_custom_css_class"];
    $html_str .= "<input id='" . $the_btn_id . "' class='div-wp-any-form-btnsubmit";
    if($the_custom_css_class != "") {
        $html_str .= " " . $the_custom_css_class;
    }
    $html_str .= "' type='submit' value='" . $the_text . "' />";
	return $html_str;
}

?>