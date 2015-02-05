<?php

function biz_logic_wp_custom_get_emptycell_control_options_arr($the_action_cmd, $the_control_fields_arr) {
    $html_str = "";
    $the_dialog_height = "235";
    $html_str .= "
    <div class='div-control-options-container' >
        <div id='div-control-options-form-msg' class='lblmsg' ></div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                " . _x("En empty cell can be used for layout purposes i.e. spacing other controls.", "Empty cell control informational message", "wp-any-form") . "
            </div>  
        </div>
    </div>";
    $return_arr = array(
        "html_str" => $html_str,
        "the_dialog_height" => $the_dialog_height
    );
    return $return_arr;
}

function biz_logic_wp_custom_get_emptycell_html_admin($the_control_fields_arr) {
	$html_str = "";
	$html_str .= "<div class='div-empty-cell-control' >" . _x("Empty Cell", "Empty cell control form builder label", "wp-any-form") . "</div>";
	return $html_str;
}

function biz_logic_wp_custom_get_emptycell_html_public($the_control_fields_arr) {
    $html_str = "";
    $html_str .= "<div class='div-empty-cell-control' >&nbsp;</div>";
    return $html_str;
}

?>