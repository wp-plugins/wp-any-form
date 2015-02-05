<?php

function biz_logic_wp_custom_wp_any_form_get_data_grids_options_custom_field_keys_html($the_form_post_id) {
	$html_str = "";
	$form_data_schema_post_id = biz_logic_wp_custom_submit_get_data_schema_post_id($the_form_post_id);
    if($form_data_schema_post_id != "-1") {
        $form_data_schema = biz_logic_wp_custom_get_post_meta($form_data_schema_post_id, "form_data_schema", "");
        if($form_data_schema != "") {
        	$html_str .= "
            <h4>" . _x("Select fields", "Data Grids options heading", "wp-any-form") . "</h4>
        	<div class='div-wpaf-table-row' >
                <div class='div-wpaf-table-cell' >
                	<input id='cbx_custom_field_keys_select_all' value='All' checked='checked' type='checkbox'> " . _x("Select/Deselect All", "Data Grids select fields option checkbox label", "wp-any-form") . "
                </div>
                <div class='div-wpaf-table-cell' >
                    <input class='cbx_custom_field_keys' value='form_data_custom_field_the_date_time' checked='checked' type='checkbox'> " . DATA_GRIDS_CSV_DATE_TIME_COLUMN_NAME . "
                </div>";
        	foreach ($form_data_schema as $the_custom_field_key) {
                $cbx_custom_field_key_options = array(
			        "class_str" => "cbx_custom_field_keys",
			        "the_label" => $the_custom_field_key,
			        "the_value" => $the_custom_field_key,
			        "checked_str" => $the_custom_field_key
			    );
			    $html_str .= "<div class='div-wpaf-table-cell' >" . biz_logic_wp_custom_get_cbx_html($cbx_custom_field_key_options) . "</div>";
            }
            $html_str .= "
            </div>";
        }
    }
	return $html_str;
}

function biz_logic_wp_custom_get_data_grid_html($the_form_post_id, $the_selected_custom_field_keys_arr) {
    $html_str = "";
    $form_data_schema_post_id = biz_logic_wp_custom_submit_get_data_schema_post_id($the_form_post_id);
    if($form_data_schema_post_id != "-1") {
        $form_data_schema = biz_logic_wp_custom_get_post_meta($form_data_schema_post_id, "form_data_schema", "");
        if($form_data_schema != "") {
            $form_data_grid_args = array(
                'post_type' => 'wp_any_form_data',
                'numberposts' => -1,
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => "form_data_custom_field_form_post_id",
                        'value' => $the_form_post_id,
                        'compare' => '='
                    )
                )
            );
            $form_data_grid_arr = get_posts($form_data_grid_args);
            $form_data_grid_total_count = count($form_data_grid_arr);
            if ($form_data_grid_total_count > 0) {
                $html_str .= "
                <div class='div-table-data-filter' >
                    <input id='tbl_admin_data_grid_filter' type='text' value='' placeholder='" . _x("Filter data", "Data Grids filter data placeholder", "wp-any-form") . "' />
                </div>
                " . biz_logic_wp_custom_get_div_clear_html("5") . "
                <table id='tbl-admin-data-grid' class='footable' data-page-size='50' data-filter=#tbl_admin_data_grid_filter >
                    <thead>
                        <tr>";
                $the_th_counteri = 0;
                if(in_array("form_data_custom_field_the_date_time", $the_selected_custom_field_keys_arr)) {
                    $the_th_counteri += 1;
                    $html_str .= "
                            <th>" . DATA_GRIDS_CSV_DATE_TIME_COLUMN_NAME . "</th>";                    
                }
                foreach ($form_data_schema as $the_custom_field_key) {
                    if(in_array($the_custom_field_key, $the_selected_custom_field_keys_arr)) {
                        $the_th_counteri += 1;
                        $html_str .= "
                            <th";
                        switch ($the_th_counteri) {
                            case 1:
                                break;
                            case ($the_th_counteri > 1 && $the_th_counteri <= 4):
                                $html_str .= " data-hide='phone'";
                                break;
                            case ($the_th_counteri > 4):
                                $html_str .= " data-hide='phone,tablet'";
                                break;
                        }
                        if($the_th_counteri > 1) {
                            
                        }
                        $html_str .= " >" . $the_custom_field_key . "</th>";
                	}
                }
                $html_str .= "
                        </tr>
                    </thead>
                    <tbody>";
                foreach($form_data_grid_arr as $form_data_post_item) {
                    $html_str .= "<tr>";
                    setup_postdata($form_data_post_item);
                    $form_data_post_id = $form_data_post_item->ID;
                    if(in_array("form_data_custom_field_the_date_time", $the_selected_custom_field_keys_arr)) {
                        $form_data_custom_field_the_date_time = biz_logic_wp_custom_get_post_meta($form_data_post_id, "form_data_custom_field_the_date_time", "");
                        $html_str .= "<td>" . $form_data_custom_field_the_date_time . "</td>";
                    }
                    foreach ($form_data_schema as $the_custom_field_key) {
                    	if(in_array($the_custom_field_key, $the_selected_custom_field_keys_arr)) {
                            $html_str .= "<td>" . biz_logic_wp_custom_get_saved_form_field_data_to_html($form_data_post_id, $the_custom_field_key, "grid") . "</td>";       	
                    	}
                    }
                    $html_str .= "</tr>";
                }
                $html_str .= "
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan='" . $the_th_counteri . "' >
                            <div id='div-admin-data-table-paging' class='pagination pagination-centered hide-if-no-paging'></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>";
            } else {
                $html_str .= _x("No form data found.", "Data Grids informational message", "wp-any-form");
            }
            wp_reset_query();
        }
    }
    return $html_str;
}

?>