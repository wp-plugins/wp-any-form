<?php

function biz_logic_wp_custom_submit_form_sync_data_schema($the_form_post_id, $field_name_arr) {
	$form_data_schema_post_id = biz_logic_wp_custom_submit_get_data_schema_post_id($the_form_post_id);
    if ($form_data_schema_post_id != "-1") {
	    $form_data_schema = biz_logic_wp_custom_get_post_meta($form_data_schema_post_id, "form_data_schema", "");
	    if($form_data_schema != "") {
            $the_new_form_data_schema = $form_data_schema;
            foreach ($field_name_arr as $field_name) {
                if(!in_array($field_name, $form_data_schema)) {
                    $the_new_form_data_schema[] = $field_name;
                }
            }
	    	update_post_meta($form_data_schema_post_id, "form_data_schema", $the_new_form_data_schema);
	    } else {
	    	add_post_meta($form_data_schema_post_id, "form_data_schema", $field_name_arr);	
	    }
    } else {
    	$new_form_data_schema_post_title = get_the_title($the_form_post_id);
    	$the_post_author_id = biz_logic_wp_custom_get_admin_author_id();
		$new_form_data_schema_post_args = array(
	        'post_author'    => $the_post_author_id,
	        'post_type'      => "wpaf_data_schema",
	        'post_status'    => "publish",
	        'post_title'     => $new_form_data_schema_post_title
	    ); 
	    $new_form_data_schema_post_id = wp_insert_post($new_form_data_schema_post_args, false);
	    if($new_form_data_schema_post_id) {
	    	add_post_meta($new_form_data_schema_post_id, "form_data_schema_custom_field_form_post_id", $the_form_post_id); 
	    	add_post_meta($new_form_data_schema_post_id, "form_data_schema", $field_name_arr);
	    }
    }
    wp_reset_query();
}

function biz_logic_wp_custom_submit_get_data_schema_post_id($the_form_post_id) {
	$form_data_schema_post_id = "-1";
	$form_data_schema_args = array(
        'post_type' => 'wpaf_data_schema',
        'numberposts' => 1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => "form_data_schema_custom_field_form_post_id",
                'value' => $the_form_post_id,
                'compare' => '='
            )
        )
    );
    $form_data_schema_arr = get_posts($form_data_schema_args);
    $form_data_schema_total_count = count($form_data_schema_arr);
    if ($form_data_schema_total_count > 0) {
        foreach($form_data_schema_arr as $form_data_schema_post) {
            setup_postdata($form_data_schema_post);
            $form_data_schema_post_id = $form_data_schema_post->ID;
        }
    }
    wp_reset_query();
    return $form_data_schema_post_id;
}

?>