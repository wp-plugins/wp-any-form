<?php

function biz_logic_wp_custom_get_font_weight_ddl_html_str($ddl_name_str, $ddl_id_str, $selected_val) {
	$ddl_font_weight_options_arr = array(
		"" => _x("None", "Form style drop down list option (Font weight, none)", "wp-any-form"),
        "normal" => _x("Normal", "Form style drop down list option (Font weight, normal)", "wp-any-form"),
		"bold" => _x("Bold", "Form style drop down list option (Font weight, bold)", "wp-any-form"),
		"bolder" => _x("Bolder", "Form style drop down list option (Font weight, bolder)", "wp-any-form"),
		"lighter" => _x("Lighter", "Form style drop down list option (Font weight, lighter)", "wp-any-form")
    );
	return biz_logic_wp_custom_get_ddl_html($ddl_name_str, $ddl_id_str, "", "", $ddl_font_weight_options_arr, $selected_val);
}

function biz_logic_wp_custom_get_form_layout_ddl_html_str($ddl_name_str, $ddl_id_str, $selected_val) {
    $ddl_form_layout_options_arr = array(
        "auto" => _x("Auto", "Form layout option (Auto)", "wp-any-form"),
        "grid" => _x("Grid", "Form layout option (Grid)", "wp-any-form"),
        "flexi" => _x("Flexi Grid", "Form layout option (Flexi Grid)", "wp-any-form")
    );
    return biz_logic_wp_custom_get_ddl_html($ddl_name_str, $ddl_id_str, "", "", $ddl_form_layout_options_arr, $selected_val);
}

function biz_logic_wp_custom_get_form_cell_vertical_align_ddl_html_str($ddl_name_str, $ddl_id_str, $selected_val) {
    $ddl_form_cell_vertical_align_options_arr = array(
        "top" => _x("Top", "Cell vertical align option (Top)", "wp-any-form"),
        "middle" => _x("Middle", "Cell vertical align option (Middle)", "wp-any-form"),
        "bottom" => _x("Bottom", "Cell vertical align option (Bottom)", "wp-any-form")
    );
    return biz_logic_wp_custom_get_ddl_html($ddl_name_str, $ddl_id_str, "", "", $ddl_form_cell_vertical_align_options_arr, $selected_val);
}

function biz_logic_wp_custom_get_form_control_align_ddl_html_str($ddl_name_str, $ddl_id_str, $selected_val) {
    $ddl_form_control_align_options_arr = array(
        "none" => _x("None", "Control options style drop down list option (Align, none)", "wp-any-form"),
        "left" => _x("Left", "Control options style drop down list option (Align, left)", "wp-any-form"),
        "right" => _x("Right", "Control options style drop down list option (Align, right)", "wp-any-form")
    );
    return biz_logic_wp_custom_get_ddl_html($ddl_name_str, $ddl_id_str, "", "", $ddl_form_control_align_options_arr, $selected_val);
}

?>