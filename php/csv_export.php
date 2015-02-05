<?php
	
function biz_logic_wp_custom_export_data_csv_html($the_form_post_id, $the_selected_custom_field_keys_arr) {
	$return_arr = array();
	$the_uniqid = biz_logic_wp_custom_get_unique_id();
	$the_csv_file_name_str = _x("form_data", "CSV Export file name prefix", "wp-any-form") . "_" . $the_form_post_id . "_" . $the_uniqid . ".csv";
	$file_create_arr = wp_upload_bits($the_csv_file_name_str, null, "");
	if(!$file_create_arr["error"]) {
		$the_csv_file_str = $file_create_arr["file"];
		$the_csv_file_url = $file_create_arr["url"];
		$the_csv_file = fopen($the_csv_file_str, "w");	
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
	            	$the_csv_headings_arr = array();
	            	if(in_array("form_data_custom_field_the_date_time", $the_selected_custom_field_keys_arr)) {
	                    $the_csv_headings_arr[] = DATA_GRIDS_CSV_DATE_TIME_COLUMN_NAME;
	                }
	                foreach ($form_data_schema as $the_custom_field_key) {
	                	if(in_array($the_custom_field_key, $the_selected_custom_field_keys_arr)) {
	                		$the_csv_headings_arr[] = $the_custom_field_key;
	                	}
	                }
	            	if(count($the_csv_headings_arr) > 0) {
						fputcsv($the_csv_file, $the_csv_headings_arr);	
					}
	                foreach($form_data_grid_arr as $form_data_post_item) {
	                	$the_csv_row_arr = array();
	                    setup_postdata($form_data_post_item);
	                    $form_data_post_id = $form_data_post_item->ID;
	                    if(in_array("form_data_custom_field_the_date_time", $the_selected_custom_field_keys_arr)) {
	                        $form_data_custom_field_the_date_time = biz_logic_wp_custom_get_post_meta($form_data_post_id, "form_data_custom_field_the_date_time", "");
	                        $the_csv_row_arr[] = $form_data_custom_field_the_date_time;
	                    }
	                    foreach ($form_data_schema as $the_custom_field_key) {
	                    	if(in_array($the_custom_field_key, $the_selected_custom_field_keys_arr)) {
	                    		$the_custom_field_value = biz_logic_wp_custom_get_post_meta($form_data_post_id, $the_custom_field_key, "");
	                    		$the_csv_row_arr[] = $the_custom_field_value;
	                    	}
	                    }
	                    if(count($the_csv_row_arr) > 0) {
							fputcsv($the_csv_file, $the_csv_row_arr);	
						}
	                }
	            } else {
	            	$return_arr["return_msg"] = _x("No form data found.", "CSV Export informational message", "wp-any-form");
	            }
	            wp_reset_query();
	        }
	    }
	    fclose($the_csv_file);
		$return_arr["the_csv_file_url"] = $the_csv_file_url;
		$return_arr["return_msg"] = "ok";
	} else {
		$return_arr["return_msg"] = _x("Error creating CSV file.", "CSV Export error message", "wp-any-form");
	}
	return $return_arr;
}

?>