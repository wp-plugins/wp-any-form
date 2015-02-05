<?php

function biz_logic_wp_custom_get_recaptcha_control_options_arr($the_action_cmd, $the_control_fields_arr) {
    $html_str = "";
    $the_dialog_height = "750";
    $the_field_id = "";
    $validating_recaptcha_msg = "";
    $recaptcha_validation_failed_msg = "";
    $the_theme = "";
    switch ($the_action_cmd) {
        case "add":
            $the_field_id = biz_logic_wp_custom_get_unique_id();  
            $validating_recaptcha_msg = _x("Validating ReCaptcha, please wait...", "ReCaptcha control default message (Validating)", "wp-any-form");
            $recaptcha_validation_failed_msg = _x("Invalid ReCaptcha, please try again.", "ReCaptcha control default message (Validation failed)", "wp-any-form");  
            break;
        case "edit":
            $the_field_id = $the_control_fields_arr["the_field_id"];
            $validating_recaptcha_msg = $the_control_fields_arr["validating_recaptcha_msg"];
            $recaptcha_validation_failed_msg = $the_control_fields_arr["recaptcha_validation_failed_msg"];
            $the_theme = $the_control_fields_arr["the_theme"];    
            break;    
    }
    $ddl_theme_options_arr = array(
        "light" => _x("Light", "ReCaptcha control theme option (Light)", "wp-any-form"),
        "dark" => _x("Dark", "ReCaptcha control theme option (Dark)", "wp-any-form")
    );
    $html_str .= "
    <div class='div-control-options-container' >
        <div id='div-control-options-form-msg' class='lblmsg' ></div>
        <div id='div-control-options-tabs' >
            <ul>
                <li><a href='#tabs-general' >" . CONTROL_OPTIONS_TABS_HEADING_GENERAL . "</a></li>
            </ul>    
            <div id='tabs-general' >
                " . biz_logic_wp_custom_get_div_clear_html("15") . "
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl' >
                        " . _x("Validating ReCaptcha message:", "Control options general label (Validating ReCaptcha message)", "wp-any-form") . " 
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell' >
                        <textarea id='txta_validating_recaptcha_msg' class='txta_form_messages' >" . $validating_recaptcha_msg . "</textarea>
                        &nbsp;
                        " . ASTERISK_HTML_STR . "
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl' >
                        " . _x("ReCaptcha validation failed message:", "Control options general label (ReCaptcha validation failed message)", "wp-any-form") . " 
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell' >
                        <textarea id='txta_recaptcha_validation_failed_msg' class='txta_form_messages' >" . $recaptcha_validation_failed_msg . "</textarea>
                        &nbsp;
                        " . ASTERISK_HTML_STR . "
                    </div>
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell lbl w135' >
                        " . _x("Theme:", "Control options general label (ReCaptcha Theme)", "wp-any-form") . "
                    </div>
                    <div class='div-any-form-cell' >
                        " . biz_logic_wp_custom_get_ddl_html("", "ddl_theme", "", "", $ddl_theme_options_arr, $the_theme) . "    
                    </div>
                </div>
                " . biz_logic_wp_custom_get_div_clear_html("15") . "
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell fs13' >
                        " . sprintf(_x("Please ensure the correct ReCaptcha Site and Secret keys are entered in plugin %s", "ReCaptcha control options informational message", "wp-any-form"), WPAF_PLUGIN_CONFIGURATION_LINK) . "
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

function biz_logic_wp_custom_get_recaptcha_html($the_control_fields_arr) {
	$html_str = "";
    $the_field_id = $the_control_fields_arr["the_field_id"];
    $html_str .= "
    <div id='" . $the_field_id . "' class='div-recaptcha-container' ></div>";
	return $html_str;
}

function biz_logic_wp_custom_recaptcha_check_answer($the_response) {
    require_once("recaptchalib.php");
    $recaptcha_secret_key = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_recaptcha_secret_key", ""));
    // The response from reCAPTCHA
    $resp = null;
    // The error code from reCAPTCHA, if any
    $error = null;
    $reCaptcha = new ReCaptcha($recaptcha_secret_key);
    $resp = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $the_response
    );
    if ($resp != null && $resp->success) {
        return true;    
    } else {
        return false;
    }
}

?>