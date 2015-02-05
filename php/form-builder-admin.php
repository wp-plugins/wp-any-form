<?php

function biz_logic_wp_custom_build_form_from_saved_data_admin($hval_form_builder_arr) {
	$html_str = "
        <div id='div-wp-any-form-builder-form' class='div-wp-any-form-builder-form' >
            <div id='div-wp-any-form-builder-form-msg' class='div-wp-any-form-builder-form-msg' ></div>
            <div id='div-wp-any-form-builder-form-rows-container' >";
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
				$html_str .= biz_logic_wp_custom_get_form_builder_img_cmd_html("addcell", $the_current_row_no_val) . "
					<input id='hval-form-cell-count-row_" . $the_current_row_no_val . "' type='hidden' value='" . $the_current_cell_no_val . "' /> 
				</div>";	
			}
			$the_current_row_no_val = $the_control_fields_arr["the_row_no_val"];
			$html_str .= "<div id='div-wp-any-form-builder-form-row_" . $the_current_row_no_val . "' class='div-wp-any-form-builder-form-row' >";
		}
		$the_current_cell_no_val = $the_control_fields_arr["the_cell_no_val"];
		$the_control_html_str = biz_logic_wp_custom_get_control_html_admin($the_control_fields_arr);
		$html_str .= "<div id='div-wp-any-form-builder-form-cell_" . $the_current_row_no_val . "_" . $the_current_cell_no_val . "' class='div-wp-any-form-builder-form-cell' >" . $the_control_html_str . "</div>";				
	}
	$html_str .= biz_logic_wp_custom_get_form_builder_img_cmd_html("addcell", $the_current_row_no_val) . "
					<input id='hval-form-cell-count-row_" . $the_current_row_no_val . "' type='hidden' value='" . $the_current_cell_no_val . "' />
				</div>
				" . biz_logic_wp_custom_get_div_clear_html(false) . "
				" . biz_logic_wp_custom_get_form_builder_img_cmd_html("addrow") . "
			</div>
        </div>
        " . biz_logic_wp_custom_get_div_clear_html(false) . "
        <input id='hval-form-row-count' type='hidden' value='" . $the_current_row_no_val . "' />";
    $recaptcha_language = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_recaptcha_language", "auto"));
    $js_str = "
    <script type='text/javascript' >
    	var the_saved_form_arr = " . json_encode($hval_form_builder_arr) . ";
    	if(the_recaptcha_language_val == '-1') {
            the_recaptcha_language_val = '" . $recaptcha_language . "';
        }
    </script>";
    $html_str .= $js_str . biz_logic_wp_custom_get_default_admin_content("form-builder-form-saved");
    return $html_str;
}

function biz_logic_wp_custom_get_form_builder_img_cmd_html($the_cmd, $the_row_no_val = "-1", $the_cell_no_val = "-1") {
	$html_str = "";
	switch ($the_cmd) {
		case "addrow":
			$html_str .= "
				<div class='div-form-builder-img-cmd-container-row' >
					<a id='a-form-builder-img-cmd_addrow' class='a-form-builder-img-cmd' href='javascript:void(0);' >
						<img src='" . plugins_url('/img/add.png', dirname(__FILE__ )) . "' title='" . _x("Add new row", "Form builder image command title (Add new row)", "wp-any-form") . "' />
					</a>
				</div>";
			break;
		case "addcell":
			$html_str .= "
				<div class='div-form-builder-img-cmd-container-cell' >
                    <a id='a-form-builder-img-cmd_addcell_" . $the_row_no_val . "' class='a-form-builder-img-cmd' href='javascript:void(0);' >
                    	<img src='" . plugins_url('/img/add.png', dirname(__FILE__ )) . "' title='" . FORM_BUILDER_IMG_CMD_TITLE_ADDCELL . "' />
                    </a>
                </div>";
			break;
		case "delcell":
			$html_str .= "
				<div class='div-form-builder-img-cmd-container-row del' >
					<a id='a-form-builder-img-cmd_delcell_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='a-form-builder-img-cmd' href='javascript:void(0);' >
						<img src='" . plugins_url('/img/delete.png', dirname(__FILE__ )) . "' title='" . FORM_BUILDER_IMG_CMD_TITLE_DELCELL . "' />
					</a>
				</div>";
			break;
		case "options":
			$html_str .= "
				<div class='div-form-builder-img-cmd-container-row options' >
					<a id='a-form-builder-img-cmd_options_" . $the_row_no_val . "_" . $the_cell_no_val . "' class='a-form-builder-img-cmd' href='javascript:void(0);' >
						<img src='" . plugins_url('/img/cog.png', dirname(__FILE__ )) . "' title='" . _x("Options", "Form builder image command title (Options)", "wp-any-form") . "' />
					</a>
				</div>";
			break;	
	}
	return $html_str;
}

?>