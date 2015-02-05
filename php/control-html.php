<?php

function biz_logic_wp_custom_get_control_html_admin($the_control_fields_arr) {
	$html_str = "";
	$the_row_no_val = $the_control_fields_arr["the_row_no_val"];
	$the_cell_no_val = $the_control_fields_arr["the_cell_no_val"];
	switch($the_control_fields_arr["the_control_type"]) {
		case "lbl":
			$html_str .= "<div id='div-control-container_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_lbl_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . biz_logic_wp_custom_get_form_builder_img_cmd_html("options", $the_row_no_val, $the_cell_no_val) . "</div>" . biz_logic_wp_custom_get_form_builder_img_cmd_html("delcell", $the_row_no_val, $the_cell_no_val);
			break;
		case "txt":
			$html_str .= "<div id='div-control-container_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_txt_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . biz_logic_wp_custom_get_form_builder_img_cmd_html("options", $the_row_no_val, $the_cell_no_val) . "</div>" . biz_logic_wp_custom_get_form_builder_img_cmd_html("delcell", $the_row_no_val, $the_cell_no_val);
			break;
		case "txta":
			$html_str .= "<div id='div-control-container_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_txta_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . biz_logic_wp_custom_get_form_builder_img_cmd_html("options", $the_row_no_val, $the_cell_no_val) . "</div>" . biz_logic_wp_custom_get_form_builder_img_cmd_html("delcell", $the_row_no_val, $the_cell_no_val);
			break;	
		case "ddl":
			$html_str .= "<div id='div-control-container_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_form_ddl_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . biz_logic_wp_custom_get_form_builder_img_cmd_html("options", $the_row_no_val, $the_cell_no_val) . "</div>" . biz_logic_wp_custom_get_form_builder_img_cmd_html("delcell", $the_row_no_val, $the_cell_no_val);
			break;	
		case "cbx":
			$html_str .= "<div id='div-control-container_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_form_cbx_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . biz_logic_wp_custom_get_form_builder_img_cmd_html("options", $the_row_no_val, $the_cell_no_val) . "</div>" . biz_logic_wp_custom_get_form_builder_img_cmd_html("delcell", $the_row_no_val, $the_cell_no_val);
			break;	
		case "rbtn":
			$html_str .= "<div id='div-control-container_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_form_rbtn_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . biz_logic_wp_custom_get_form_builder_img_cmd_html("options", $the_row_no_val, $the_cell_no_val) . "</div>" . biz_logic_wp_custom_get_form_builder_img_cmd_html("delcell", $the_row_no_val, $the_cell_no_val);
			break;	
		case "recaptcha":
			$html_str .= "<div id='div-control-container_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_recaptcha_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . biz_logic_wp_custom_get_form_builder_img_cmd_html("options", $the_row_no_val, $the_cell_no_val) . "</div>" . biz_logic_wp_custom_get_form_builder_img_cmd_html("delcell", $the_row_no_val, $the_cell_no_val);
			break;
		case "btnsubmit":
			$html_str .= "<div id='div-control-container_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_btnsubmit_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . biz_logic_wp_custom_get_form_builder_img_cmd_html("options", $the_row_no_val, $the_cell_no_val) . "</div>" . biz_logic_wp_custom_get_form_builder_img_cmd_html("delcell", $the_row_no_val, $the_cell_no_val);
			break;	
		case "btnreset":
			$html_str .= "<div id='div-control-container_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_btnreset_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . biz_logic_wp_custom_get_form_builder_img_cmd_html("options", $the_row_no_val, $the_cell_no_val) . "</div>" . biz_logic_wp_custom_get_form_builder_img_cmd_html("delcell", $the_row_no_val, $the_cell_no_val);
			break;	
		case "emptycell":
			$html_str .= "<div id='div-control-container_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_emptycell_html_admin($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . biz_logic_wp_custom_get_form_builder_img_cmd_html("options", $the_row_no_val, $the_cell_no_val) . "</div>" . biz_logic_wp_custom_get_form_builder_img_cmd_html("delcell", $the_row_no_val, $the_cell_no_val);
			break;		
	}
	return $html_str;
}

function biz_logic_wp_custom_get_control_html_public($the_control_fields_arr) {
	$html_str = "";
	$the_row_no_val = $the_control_fields_arr["the_row_no_val"];
	$the_cell_no_val = $the_control_fields_arr["the_cell_no_val"];
	switch($the_control_fields_arr["the_control_type"]) {
		case "lbl":
			$html_str .= "<div class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_lbl_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . "</div>";
			break;
		case "txt":
			$html_str .= biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_txt_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end");
			break;
		case "txta":
			$html_str .= biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_txta_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end");
			break;
		case "ddl":
			$html_str .= biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_form_ddl_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end");
			break;
		case "cbx":
			$html_str .= "<div class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_form_cbx_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . "</div>";
			break;
		case "rbtn":
			$html_str .= "<div class='div-control-container' >" . biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_form_rbtn_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end") . "</div>";
			break;
		case "recaptcha":
			$html_str .= biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_recaptcha_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end");
			break;
		case "btnsubmit":
			$html_str .= biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_btnsubmit_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end");
			break;	
		case "btnreset":
			$html_str .= biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_btnreset_html($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end");
			break;	
		case "emptycell":
			$html_str .= biz_logic_wp_custom_get_vertical_align_html("start") . biz_logic_wp_custom_get_emptycell_html_public($the_control_fields_arr) . biz_logic_wp_custom_get_vertical_align_html("end");
			break;	
	}
	return $html_str;
}

function biz_logic_wp_custom_get_vertical_align_html($start_or_end) {
	$html_str = "";
	switch ($start_or_end) {
		case "start":
			$html_str .= "<div class='div-vertical-spacing-tbl' ><div class='div-vertical-spacing-cell' ><div>";
			break;
		case "end":
			$html_str .= "</div></div></div>";
			break;
	}
	return $html_str;
}

?>