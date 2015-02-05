<?php
/* 
Admin
*/

class WPAnyFormAdmin {
    
function __construct() {
    add_action('admin_print_styles', array($this, 'register_admin_styles'));
	add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
    add_action('admin_menu', array($this, 'admin_menu'));
    add_action('add_meta_boxes', array($this, 'add_custom_meta_boxes'));
    add_action('save_post', array($this, 'wp_any_form_save_custom_fields_meta'), 1, 2);
    add_action('save_post', array($this, 'wp_any_form_email_templates_options_save_custom_fields_meta'), 1, 2);
    add_action('wp_ajax_' . BIZLOGIC_UNIQUE_PLUGIN_NAME . '_admin-ajax-submit', array($this, 'admin_ajax_submit'));
    add_filter('post_row_actions', array($this, 'custom_admin_row_actions'), 10, 2);
    add_action('admin_action_wp_any_form_duplicate', array($this, 'wp_any_form_duplicate'));
    add_filter('bulk_actions-edit-wp_any_form_data', array($this, 'form_data_custom_bulk_actions'));
    add_filter('screen_layout_columns', array($this, 'custom_screen_layout_columns'));
    add_filter('get_user_option_screen_layout_wp_any_form', array($this, 'custom_screen_layout_wp_any_form'));
    add_filter('get_user_option_screen_layout_wp_any_form_data', array($this, 'custom_screen_layout_wp_any_form_data'));
    add_action('admin_head-post.php', array($this, 'custom_hide_publishing_actions'));
    add_action('admin_head-post-new.php', array($this, 'custom_hide_publishing_actions'));    
    add_action('restrict_manage_posts', array($this, 'get_form_posts_drop_down'));
    add_filter('parse_query', array($this, 'form_data_posts_filter'));
    add_action('init', array($this, 'mce_custom_controls_init'));
    add_filter('post_updated_messages', array($this, 'custom_post_type_messages'));
}

function custom_post_type_messages($messages) {
    global $post, $post_ID;
    $post_type = get_post_type( $post_ID );
    $post_type_object = get_post_type_object( $post_type );
    switch($post_type) {
        case "wp_any_form": 
            $messages[$post_type] = array(
                0 => '', // Unused. Messages start at index 1.
                1 => __("Form updated.", "wp-any-form"),
                2 => 'Custom field updated.',
                3 => 'Custom field deleted.',
                4 => __("Form updated.", "wp-any-form"),
                5 => isset( $_GET['revision'] ) ? sprintf( __( 'Form restored to revision from %s', 'wp-any-form' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
                6 => __( 'Form published.', 'wp-any-form' ),
                7 => __( 'Form saved.', 'wp-any-form' ),
                8 => __( 'Form submitted.', 'wp-any-form' ),
                9 => sprintf(
                        __( 'Form scheduled for: <strong>%1$s</strong>.', 'wp-any-form' ),
                        date_i18n( _x("M j, Y @ G:i", "Scheduled date for Form", "wp-any-form"), strtotime( $post->post_date ) )
                     ),
                10 => __( 'Form draft updated.', 'wp-any-form' ),
            );
            if ($post_type_object->publicly_queryable) {
                $view_link = "";
                $messages[ $post_type ][1] .= $view_link;
                $messages[ $post_type ][6] .= $view_link;
                $messages[ $post_type ][9] .= $view_link;
                $preview_link = "";
                $messages[ $post_type ][8]  .= $preview_link;
                $messages[ $post_type ][10] .= $preview_link;
            }
            break;
        case "wpaf_email_templates":
            $messages[$post_type] = array(
                0 => '', // Unused. Messages start at index 1.
                1 => __("Email template updated.", "wp-any-form"),
                2 => 'Custom field updated.',
                3 => 'Custom field deleted.',
                4 => __("Email template updated.", "wp-any-form"),
                5 => isset( $_GET['revision'] ) ? sprintf( __( 'Email template restored to revision from %s', 'wp-any-form' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
                6 => __( 'Email template published.', 'wp-any-form' ),
                7 => __( 'Email template saved.', 'wp-any-form' ),
                8 => __( 'Email template submitted.', 'wp-any-form' ),
                9 => sprintf(
                        __( 'Email template scheduled for: <strong>%1$s</strong>.', 'wp-any-form' ),
                        date_i18n( _x("M j, Y @ G:i", "Scheduled date for Email template", "wp-any-form"), strtotime( $post->post_date ) )
                     ),
                10 => __( 'Email template draft updated.', 'wp-any-form' ),
            );
            if ($post_type_object->publicly_queryable) {
                $view_link = "";
                $messages[ $post_type ][1] .= $view_link;
                $messages[ $post_type ][6] .= $view_link;
                $messages[ $post_type ][9] .= $view_link;
                $preview_link = "";
                $messages[ $post_type ][8]  .= $preview_link;
                $messages[ $post_type ][10] .= $preview_link;
            }
            break;
        case "wpaf_data_schema": case "wp_any_form_data": default:
            return $messages;
    }
    return $messages;
}

function mce_custom_controls_init() {
    //Abort early if the user will never see TinyMCE
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') {
        return;
    }
    $the_current_post_type = biz_logic_wp_custom_get_current_post_type_admin();
    if(post_type_supports($the_current_post_type, "editor")) {
        switch($the_current_post_type) {
            case "wpaf_email_templates":
                //Add a callback to regiser our tinymce plugin   
                add_filter("mce_external_plugins", array($this, "email_templates_mce_custom_tinymce_plugin")); 
                // Add a callback to add our button to the TinyMCE toolbar
                add_filter('mce_buttons', array($this, 'email_templates_mce_custom_add_tinymce_button'));        
                break;
            default:
                add_action('admin_head', 'biz_logic_wp_custom_get_form_posts_drop_down_tiny_mce_js');
                //Add a callback to regiser our tinymce plugin   
                add_filter("mce_external_plugins", array($this, "wpaf_mce_custom_tinymce_plugin")); 
                // Add a callback to add our button to the TinyMCE toolbar
                add_filter('mce_buttons', array($this, 'wpaf_mce_custom_add_tinymce_forms_ddl'));               
                break;
        }
    }    
}

//This callback registers our plug-in
function email_templates_mce_custom_tinymce_plugin($plugin_array) {
    $plugin_array['email_templates_mce_custom_add_tinymce_button'] = plugins_url('/js/email-template-editor-custom-controls.js', dirname(__FILE__ ));
    return $plugin_array;
}

//This callback adds our button to the toolbar
function email_templates_mce_custom_add_tinymce_button($buttons) {
    //Add the button ID to the $button array
    $buttons[] = "email_templates_mce_custom_add_tinymce_button";
    return $buttons;
}

//This callback registers our plug-in
function wpaf_mce_custom_tinymce_plugin($plugin_array) {
    $plugin_array['wpaf_mce_custom_add_tinymce_forms_ddl'] = plugins_url('/js/wpaf-editor-custom-controls.js', dirname(__FILE__ ));
    return $plugin_array;
}

//This callback adds our button to the toolbar
function wpaf_mce_custom_add_tinymce_forms_ddl($buttons) {
    //Add the button ID to the $button array
    $buttons[] = "wpaf_mce_custom_add_tinymce_forms_ddl";
    return $buttons;
}

function form_data_custom_bulk_actions($actions) {
    unset($actions['edit']);
    return $actions;
}

function custom_admin_row_actions($actions, $post) {
    //check for your post type
    switch($post->post_type) {
        case "wp_any_form":
            /*do you stuff here
            you can unset to remove actions
            and to add actions ex:
            $actions['in_google'] = '<a href="http://www.google.com/?q='.get_permalink($post->ID).'">check if indexed</a>';
            */
            unset($actions['inline hide-if-no-js']);
            unset($actions['view']);
            $actions['duplicate'] = "<a href='" . admin_url("admin.php?action=wp_any_form_duplicate&post=" . $post->ID) . "' title='Duplicate this item' rel='permalink' >Duplicate</a>";
            break;
        case "wp_any_form_data":
            unset($actions['inline hide-if-no-js']);
            unset($actions['view']);
            unset($actions['edit']);
            break;
        case "wpaf_email_templates":
            unset($actions['inline hide-if-no-js']);
            unset($actions['view']);
            $actions['duplicate'] = "<a href='" . admin_url("admin.php?action=wp_any_form_duplicate&post=" . $post->ID) . "' title='Duplicate this item' rel='permalink' >Duplicate</a>";
            break;
    }
    return $actions;
}

/*
 * Function creates post duplicate as a draft and redirects then to the edit post screen
 */
function wp_any_form_duplicate(){
    global $wpdb;
    if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'wp_any_form_duplicate' == $_REQUEST['action'] ) ) ) {
        wp_die(_x("No form to duplicate has been specified.", "Duplicate form error message", "wp-any-form"));
    }
    /*
     * get the original post id
     */
    $post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
    /*
     * and all the original post data then
     */
    $post = get_post( $post_id );
    $new_post_author = $post->post_author;
    /*
     * if post data exists, create the post duplicate
     */
    if (isset( $post ) && $post != null) {
        /*
         * new post data array
         */
        $the_new_unique_form_post_title = $post->post_title;
        if (strrpos($the_new_unique_form_post_title, " copy") === false) { 
            $the_new_unique_form_post_title .= " copy";
        }
        $the_new_unique_form_post_title = biz_logic_wp_custom_get_unique_post_title($the_new_unique_form_post_title, $post->post_type);
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $the_new_unique_form_post_title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );
        /*
         * insert the post by wp_insert_post() function
         */
        $new_post_id = wp_insert_post( $args );
        /*
         * get all current post terms and set them to the new post draft
         */
        $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }
        /*
         * duplicate all post meta
         */
        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
        if (count($post_meta_infos)!=0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($post_meta_infos as $meta_info) {
                $meta_key = $meta_info->meta_key;
                $temp_meta_value = $meta_info->meta_value;
                if($post->post_type == 'wp_any_form' && $meta_key == 'hval_form_builder_arr') {
                    $hval_form_builder_arr = $temp_meta_value;
                    $have_saved_arr_not_empty = false;
                    if($hval_form_builder_arr != "") {
                        $hval_form_builder_arr = json_decode($hval_form_builder_arr, true);
                        if (!biz_logic_wp_custom_check_if_array_empty($hval_form_builder_arr)) {
                            $have_saved_arr_not_empty = true;            
                        }
                    }
                    if($have_saved_arr_not_empty) {
                        for ($i=0; $i < count($hval_form_builder_arr); $i++) { 
                            $the_control_fields_arr = $hval_form_builder_arr[$i];
                            $the_control_type = $the_control_fields_arr["the_control_type"];
                            switch($the_control_type) {
                                case "lbl": case "txt": case "txta": case "cbx": case "rbtn": case "ddl": case "recaptcha":
                                    $the_control_fields_arr["the_field_id"] = biz_logic_wp_custom_get_unique_id() . $i;
                                    $hval_form_builder_arr[$i] = $the_control_fields_arr;
                                    break;
                                case "btnsubmit": case "btnreset":
                                    $the_control_fields_arr["the_btn_id"] = biz_logic_wp_custom_get_unique_id() . $i;
                                    $hval_form_builder_arr[$i] = $the_control_fields_arr;
                                    break;
                            }
                        }
                        $temp_meta_value = json_encode($hval_form_builder_arr);
                    }
                }
                $meta_value = addslashes($temp_meta_value);
                $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query.= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }
        /*
         * finally, redirect to the edit post screen for the new draft
         */
        wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
        exit;
    } else {
        wp_die(_x("Post creation failed, could not find original post.", "Duplicate form error message", "wp-any-form"));
    }
}

function custom_screen_layout_columns($columns) {
    $columns['wp_any_form'] = 1;
    $columns['wp_any_form_data'] = 1;
    return $columns;
}

function custom_screen_layout_wp_any_form() {
    return 1;
}

function custom_screen_layout_wp_any_form_data() {
    return 1;
}

function custom_hide_publishing_actions(){
    global $post;
    switch($post->post_type) {
        case "wp_any_form":
            echo "
            <style type='text/css' >
                .misc-pub-visibility, .misc-pub-curtime, #edit-slug-box {
                    display:none;
                }
            </style>";
            break;
        case "wp_any_form_data":
            echo "
            <style type='text/css' >
                #minor-publishing, #edit-slug-box, #submitdiv {
                    display:none;
                }
            </style>";
            break;
        case "wpaf_email_templates":
            echo "
            <style type='text/css' >
                #minor-publishing-actions, .misc-pub-visibility, .misc-pub-curtime, #edit-slug-box {
                    display:none;
                }
            </style>";
            break;
    }
}

function get_form_posts_drop_down() {
    $the_selected_val = $_GET['the_form_post_id'];
    if ($_GET['post_type'] == 'wp_any_form_data' ) {
        $first_option_arr = array("-1" => __("Show data from all forms", "wp-any-form"));
        echo biz_logic_wp_custom_get_form_posts_drop_down_html(true, "the_form_post_id", "", "", $first_option_arr, $the_selected_val);        
    }
}

function form_data_posts_filter($query) {
    global $pagenow;
    $the_custom_field_name = "form_data_custom_field_form_post_id";
    if ( is_admin() && $pagenow=='edit.php' && isset($_GET['the_form_post_id']) && $_GET['the_form_post_id'] != '-1') {
        $query->query_vars['meta_key'] = $the_custom_field_name;
        $query->query_vars['meta_value'] = $_GET['the_form_post_id'];
    }
}

function register_admin_styles() {
    wp_enqueue_style('thickbox');
    $the_ui_theme_file_str = biz_logic_wp_custom_get_the_ui_theme_file_str();
    $exclude_ui_theme_admin = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_exclude_ui_theme_admin", "no"));
    if($exclude_ui_theme_admin == "no") {
        wp_register_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_jquery_ui_css', plugins_url("/css/jquery-ui-themes/" . $the_ui_theme_file_str, dirname(__FILE__)));
    }
    wp_enqueue_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_jquery_ui_css');
    wp_register_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_spectrum_css', plugins_url('/css/spectrum.css', dirname(__FILE__)));
    wp_enqueue_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_spectrum_css');
    wp_register_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_core_css', plugins_url('/css/footable.core.css', dirname(__FILE__)));
    wp_enqueue_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_core_css');
    wp_register_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_standalone_css', plugins_url('/css/footable.standalone.css', dirname(__FILE__)));
    wp_enqueue_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_standalone_css');
    wp_register_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_admin_css', plugins_url('/css/admin-style.css', dirname(__FILE__)));
    wp_enqueue_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_admin_css');
    wp_register_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_admin_mobile_css', plugins_url('/css/admin-style-mobile.css', dirname(__FILE__)), array(), false, "only screen and (max-device-width: 1023px), screen and (max-width: 1023px)");
    wp_enqueue_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_admin_mobile_css');
    wp_register_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_admin_600_css', plugins_url('/css/admin-style-600.css', dirname(__FILE__)), array(), false, "only screen and (max-device-width: 600px), screen and (max-width: 600px)");
    wp_enqueue_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_admin_600_css');
}

function register_admin_scripts() {
    wp_enqueue_script('jquery');			
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('jquery-ui-droppable');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('thickbox');  
    wp_enqueue_script('media-upload');
    wp_register_script('biz_logic_wp_custom_js_lib', plugins_url('/js/lib.js', dirname(__FILE__ )));
    wp_enqueue_script('biz_logic_wp_custom_js_lib');
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_spectrum_js', plugins_url('/js/spectrum.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_spectrum_js');   
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_js', plugins_url('/js/footable.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_js');   
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_sort_js', plugins_url('/js/footable.sort.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_sort_js');
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_paginate_js', plugins_url('/js/footable.paginate.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_paginate_js');
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_filter_js', plugins_url('/js/footable.filter.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_footable_filter_js');
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_jquery_placeholder_js', plugins_url('/js/jquery.placeholder.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_jquery_placeholder_js');
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_lib_js', plugins_url('/js/form-lib.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_lib_js');   
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_ddl_lib_js', plugins_url('/js/ddl.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_ddl_lib_js');   
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_cbx_lib_js', plugins_url('/js/cbx.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_cbx_lib_js');   
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_rbtn_lib_js', plugins_url('/js/rbtn.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_rbtn_lib_js');   
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_builder_js', plugins_url('/js/form-builder.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_builder_js');   
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_admin_js', plugins_url('/js/admin-init.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_admin_js');	
    $the_date_format_str = biz_logic_wp_custom_get_date_format_site_option();
    $recaptcha_site_key = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_recaptcha_site_key", ""));
    $server_error_msg_admin = _x("Server error, please contact plugin author or system administrator.", "Server error message for plugin admin", "wp-any-form");
    $required_fields_error_msg_admin = sprintf(_x('All fields marked with %s required.', "Required fields error message for plugin admin", "wp-any-form"), "<span class='span-required-field' >*</span>");
    $data_grid_csv_select_form_first_error_msg = _x("Select form first.", "Data grids and CSV select form error message", "wp-any-form");
    $data_grid_csv_field_required_error_msg = _x("At least one field required.", "Data grids and CSV select field(s) error message", "wp-any-form");
    $data_grid_csv_download_file_link_text = _x("Download CSV file", "Data CSV download file link text", "wp-any-form");
    $data_grid_csv_download_file_msg = _x("Export complete, download CSV file by clicking on link.", "Data CSV download file message", "wp-any-form");
    $config_contacting_server_msg = _x("Contacting server, please wait...", "Plugin configuration contacting server message", "wp-any-form");
    $config_saved_msg = _x("Configuration saved.", "Plugin configuration saved message", "wp-any-form");
    $config_custom_theme_value_error_msg = _x("Custom theme value required.", "Plugin configuration custom theme value error message", "wp-any-form");
    $config_custom_theme_file_check_ok_msg = _x("Custom theme file check ok.", "Plugin configuration custom theme file check ok message", "wp-any-form");
    $config_custom_theme_file_check_error_msg = _x("Custom theme file not found, please check file name and path and re-enter.", "Plugin configuration custom theme file check error message", "wp-any-form");
    $cbx_item_label_value_required_error_msg = _x("Item label and value required.", "Checkbox add / edit item form label and value required error message", "wp-any-form");
    $cbx_item_validation_error_msg_required_msg = _x("Validation error message required.", "Checkbox add / edit item form validation error message required message", "wp-any-form");
    $cbx_item_form_heading_update = _x("Update checkbox:", "Checkbox add / edit item form heading (update)", "wp-any-form");
    $cbx_item_form_btn_text_update = _x("Update", "Checkbox add / edit item form button text (update)", "wp-any-form");
    $ddl_item_set_form_btn_text_update = _x("Update item set", "Drop down list add / edit item set form button text (update)", "wp-any-form");
    $ddl_item_set_form_name_required_msg = _x("Item set name required.", "Drop down list add / edit item set form name required message", "wp-any-form");
    $ddl_item_set_form_updated_msg = _x("Item set updated.", "Drop down list add / edit item set form updated message", "wp-any-form");
    $ddl_item_set_confirm_delete_msg = _x("Confirm item set delete? Item set items that belong to item set will also be deleted.", "Drop down list item set delete confirmation message", "wp-any-form");
    $ddl_item_set_confirm_delete_action_text = _x("Confirm and delete", "Drop down list item set delete confirmation action text", "wp-any-form");
    $ddl_item_set_confirm_cancel_delete_action_text = _x("Cancel", "Drop down list item set delete cancel action text", "wp-any-form");
    $ddl_item_set_initial_only_once_error_msg = _x("Initial item set can only bet set once for this drop down list control.", "Drop down list initial item set error message", "wp-any-form");
    $ddl_item_desc_value_required_error_msg = _x("Item description and value required.", "Drop down list add / edit item form description and value required error message", "wp-any-form");
    $ddl_item_form_heading_update = _x("Update item:", "Drop down list add / edit item form heading (update)", "wp-any-form");
    $ddl_item_form_btn_text_update = _x("Update item", "Drop down list add / edit item form button text (update)", "wp-any-form");
    $email_templates_form_fields_dialog_title = _x("Insert form field shortcode", "Email Options insert form field dialog title", "wp-any-form");
    $email_templates_form_fields_dialog_no_form_selected_btn_txt = _x("Ok", "Email Options insert form field dialog no form selected button text", "wp-any-form");
    $email_templates_form_fields_dialog_no_form_selected_error_msg = _x("Select form first, in \"Email Options\" meta box.", "Email Options insert form field dialog no form selected error message", "wp-any-form");
    $email_templates_send_to_email_form_fields_no_form_selected_error_msg = _x("Select form first.", "Email Options send to email address form field no form selected error message", "wp-any-form");
    $email_templates_form_fields_dialog_btn_txt_insert = _x("Insert", "Email Options insert form field dialog button text (Insert)", "wp-any-form");
    $email_templates_form_fields_dialog_btn_txt_cancel = _x("Cancel", "Email Options insert form field dialog button text (Cancel)", "wp-any-form");
    $form_builder_error_msg_add_controls_first = _x("Add control(s) to form first.", "Form builder error message", "wp-any-form");
    $form_builder_error_msg_submit_btn_limit = _x("There can only be one submit button per form.", "Form builder error message", "wp-any-form");
    $form_builder_error_msg_reset_btn_limit = _x("There can only be one reset button per form.", "Form builder error message", "wp-any-form");
    $form_builder_error_msg_recaptcha_btn_limit = _x("There can only be one recaptcha control per form.", "Form builder error message", "wp-any-form");
    $form_builder_error_msg_can_only_move_empty_cell = _x("Can only move control to empty cell.", "Form builder error message", "wp-any-form");
    $form_builder_msg_control_replaced = _x("Please note the current control will be replaced.", "Form builder informational message", "wp-any-form");
    $form_builder_error_msg_field_name_unique = _x("Field Name must be unique.", "Form builder error message", "wp-any-form");
    $form_builder_control_options_dialog_btn_txt_add = _x("Add", "Form builder control options dialog button text (Add)", "wp-any-form");
    $form_builder_control_options_dialog_btn_txt_update = _x("Update", "Form builder control options dialog button text (Update)", "wp-any-form");
    $form_builder_control_options_dialog_btn_txt_cancel = _x("Cancel", "Form builder control options dialog button text (Cancel)", "wp-any-form");
    $form_builder_control_options_dialog_title = _x("Options", "Form builder control options dialog title", "wp-any-form");
    $form_builder_control_confirm_delete_text = _x("Please confirm delete action:", "Form builder control confirm delete text", "wp-any-form");
    $form_builder_control_confirm_delete_option_control_only = _x("Delete control only", "Form builder control confirm delete option", "wp-any-form");
    $form_builder_control_confirm_delete_option_control_and_cell = _x("Delete control and cell", "Form builder control confirm delete option", "wp-any-form");
    $form_builder_control_confirm_delete_option_dialog_title = _x("Confirmation", "Form builder control confirm delete dialog title", "wp-any-form");
    $form_builder_control_confirm_delete_option_dialog_btn_confirm = _x("Confirm and delete", "Form builder control confirm delete dialog button text (Confirm and delete)", "wp-any-form");
    $form_builder_control_confirm_delete_option_dialog_btn_cancel = _x("Cancel", "Form builder control confirm delete dialog button text (Cancel)", "wp-any-form");
    $form_builder_help_msg_close_text = _x("Close", "Form builder help dialog close button text", "wp-any-form");
    $rbtn_item_label_value_required_error_msg = _x("Item label and value required.", "Radio button add / edit item form label and value required error message", "wp-any-form");
    $rbtn_item_form_heading_update = _x("Update radio button:", "Radio button add / edit item form heading (update)", "wp-any-form");
    $rbtn_item_form_btn_text_update = _x("Update", "Radio button add / edit item form button text (update)", "wp-any-form");
    $rbtn_only_one_checked_error_msg = _x("Only one radio button in set can be set to be checked.", "Radio button options error message", "wp-any-form");
    $mce_forms_ddl_text = _x("Any Forms", "TinyMCE editor forms drop down list label text", "wp-any-form");
    wp_localize_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_admin_js', 'WPAnyFormAdminJSO', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'plugin_url' => plugins_url(),
        'stylesheet_url' => get_stylesheet_uri(),
        'recaptcha_site_key' => $recaptcha_site_key,
        'server_error_msg_admin' => $server_error_msg_admin,
        'required_fields_error_msg_admin' => $required_fields_error_msg_admin,
        'data_grid_csv_select_form_first_error_msg' => $data_grid_csv_select_form_first_error_msg,
        'data_grid_csv_field_required_error_msg' => $data_grid_csv_field_required_error_msg,
        'data_grid_csv_download_file_link_text' => $data_grid_csv_download_file_link_text,
        'data_grid_csv_download_file_msg' => $data_grid_csv_download_file_msg,
        'config_contacting_server_msg' => $config_contacting_server_msg,
        'config_saved_msg' => $config_saved_msg,
        'config_custom_theme_value_error_msg' => $config_custom_theme_value_error_msg,
        'config_custom_theme_file_check_ok_msg' => $config_custom_theme_file_check_ok_msg,
        'config_custom_theme_file_check_error_msg' => $config_custom_theme_file_check_error_msg,
        'cbx_item_label_value_required_error_msg' => $cbx_item_label_value_required_error_msg,
        'cbx_item_validation_error_msg_required_msg' => $cbx_item_validation_error_msg_required_msg,
        'cbx_item_form_heading_add' => ADD_CBX_HEADING_TEXT,
        'cbx_item_form_btn_text_add' => ADD_CBX_BTN_TEXT,
        'cbx_item_form_heading_update' => $cbx_item_form_heading_update,
        'cbx_item_form_btn_text_update' => $cbx_item_form_btn_text_update,
        'ddl_item_set_form_btn_text_add' => ADD_DDL_ITEM_SET_BTN_TEXT,
        'ddl_item_set_form_btn_text_update' => $ddl_item_set_form_btn_text_update,
        'ddl_item_set_form_name_required_msg' => $ddl_item_set_form_name_required_msg,
        'ddl_item_set_form_updated_msg' => $ddl_item_set_form_updated_msg,
        'ddl_item_set_confirm_delete_msg' => $ddl_item_set_confirm_delete_msg,
        'ddl_item_set_confirm_delete_action_text' => $ddl_item_set_confirm_delete_action_text,
        'ddl_item_set_confirm_cancel_delete_action_text' => $ddl_item_set_confirm_cancel_delete_action_text,
        'ddl_item_set_initial_only_once_error_msg' => $ddl_item_set_initial_only_once_error_msg,
        'ddl_item_desc_value_required_error_msg' => $ddl_item_desc_value_required_error_msg,
        'ddl_item_form_heading_add' => ADD_DDL_ITEM_HEADING_TEXT,
        'ddl_item_form_heading_update' => $ddl_item_form_heading_update,
        'ddl_item_form_btn_text_add' => ADD_DDL_ITEM_BTN_TEXT,
        'ddl_item_form_btn_text_update' => $ddl_item_form_btn_text_update,
        'email_templates_form_fields_dialog_title' => $email_templates_form_fields_dialog_title,
        'email_templates_form_fields_dialog_no_form_selected_btn_txt' => $email_templates_form_fields_dialog_no_form_selected_btn_txt,
        'email_templates_form_fields_dialog_no_form_selected_error_msg' => $email_templates_form_fields_dialog_no_form_selected_error_msg,
        'email_templates_send_to_email_form_fields_no_form_selected_error_msg' => $email_templates_send_to_email_form_fields_no_form_selected_error_msg,
        'email_templates_form_fields_dialog_btn_txt_insert' => $email_templates_form_fields_dialog_btn_txt_insert,
        'email_templates_form_fields_dialog_btn_txt_cancel' => $email_templates_form_fields_dialog_btn_txt_cancel,
        'email_templates_field_shortcode_insert_title' => EMAIL_TEMPLATE_FIELD_SHORTCODE_INSERT_TITLE,
        'form_builder_error_msg_add_controls_first' => $form_builder_error_msg_add_controls_first,
        'form_builder_error_msg_submit_btn_limit' => $form_builder_error_msg_submit_btn_limit,
        'form_builder_error_msg_reset_btn_limit' => $form_builder_error_msg_reset_btn_limit,
        'form_builder_error_msg_recaptcha_btn_limit' => $form_builder_error_msg_recaptcha_btn_limit,
        'form_builder_error_msg_can_only_move_empty_cell' => $form_builder_error_msg_can_only_move_empty_cell,
        'form_builder_msg_control_replaced' => $form_builder_msg_control_replaced,
        'form_builder_error_msg_field_name_unique' => $form_builder_error_msg_field_name_unique,
        'form_builder_control_options_dialog_btn_txt_add' => $form_builder_control_options_dialog_btn_txt_add,
        'form_builder_control_options_dialog_btn_txt_update' => $form_builder_control_options_dialog_btn_txt_update,
        'form_builder_control_options_dialog_btn_txt_cancel' => $form_builder_control_options_dialog_btn_txt_cancel,
        'form_builder_control_options_dialog_title' => $form_builder_control_options_dialog_title,
        'form_builder_control_confirm_delete_text' => $form_builder_control_confirm_delete_text,
        'form_builder_control_confirm_delete_option_control_only' => $form_builder_control_confirm_delete_option_control_only,
        'form_builder_control_confirm_delete_option_control_and_cell' => $form_builder_control_confirm_delete_option_control_and_cell,
        'form_builder_control_confirm_delete_option_dialog_title' => $form_builder_control_confirm_delete_option_dialog_title,
        'form_builder_control_confirm_delete_option_dialog_btn_confirm' => $form_builder_control_confirm_delete_option_dialog_btn_confirm,
        'form_builder_control_confirm_delete_option_dialog_btn_cancel' => $form_builder_control_confirm_delete_option_dialog_btn_cancel,
        'form_builder_img_cmd_title_addcell' => FORM_BUILDER_IMG_CMD_TITLE_ADDCELL,
        'form_builder_img_cmd_title_delcell' => FORM_BUILDER_IMG_CMD_TITLE_DELCELL,
        'form_builder_help_msg_close_text' => $form_builder_help_msg_close_text,
        'rbtn_item_label_value_required_error_msg' => $rbtn_item_label_value_required_error_msg,
        'rbtn_item_form_heading_update' => $rbtn_item_form_heading_update,
        'rbtn_item_form_btn_text_update' => $rbtn_item_form_btn_text_update,
        'rbtn_only_one_checked_error_msg' => $rbtn_only_one_checked_error_msg,
        'rbtn_item_form_heading_add' => ADD_RBTN_HEADING_TEXT,
        'rbtn_item_form_btn_text_add' => ADD_RBTN_BTN_TEXT,
        'mce_forms_ddl_text' => $mce_forms_ddl_text
    ));
}

//Admin Menu
function admin_menu() {
    if (current_user_can('edit_posts')) {
       add_submenu_page('edit.php?post_type=wp_any_form', _x("Data Grids CSV", "Data Grids CSV Page Title", "wp-any-form"), _x("Data Grids CSV", "Data Grids CSV Menu Text", "wp-any-form"), 'edit_posts', BIZLOGIC_UNIQUE_PLUGIN_NAME . '_grids_mh', array($this, 'data_grids'));
       add_submenu_page('edit.php?post_type=wp_any_form', _x("Configuration", "Configuration Page Title", "wp-any-form"), _x("Configuration", "Configuration Menu Text", "wp-any-form"), 'edit_posts', BIZLOGIC_UNIQUE_PLUGIN_NAME . '_config_mh', array($this, 'configuration'));
       add_submenu_page('edit.php?post_type=wp_any_form', "Go Pro", "Go Pro", 'edit_posts', BIZLOGIC_UNIQUE_PLUGIN_NAME . '_go_pro_mh', array($this, 'go_pro_content'));
    }
}

function configuration() {
    if (current_user_can('edit_posts')) {
        echo $this->get_configuration_html() . biz_logic_wp_custom_get_default_admin_content("config");
    }
}

function data_grids() {
    if (current_user_can('edit_posts')) {
        echo $this->get_data_grids_html() . biz_logic_wp_custom_get_default_admin_content("data-grids");
    }
}

function go_pro_content() {
    if (current_user_can('edit_posts')) {
        echo $this->get_go_pro_html() . biz_logic_wp_custom_get_default_admin_content("go-pro");
    }
}

function get_configuration_html() {
    $selected_ui_theme = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_selected_ui_theme", "
        smoothness"));
    $path_to_custom_ui_theme = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_path_to_custom_ui_theme", ""));
    $exclude_ui_theme_admin = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_exclude_ui_theme_admin", "no"));
    $cbx_exclude_ui_theme_admin_options = array(
        "id_str" => "cbx_exclude_ui_theme_admin",
        "the_label" => _x("WordPress admin", "UI Theme exclude option", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => $exclude_ui_theme_admin
    );
    $recaptcha_site_key = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_recaptcha_site_key", ""));
    $recaptcha_secret_key = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_recaptcha_secret_key", ""));
    $recaptcha_language = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_recaptcha_language", "auto"));
    $html_str = "
<div id='div-admin-configuration-tabs-container' class='wrap' >
    <h2>" . _x("Configuration", "Plugin configuration heading", "wp-any-form") . "</h2>
    " . biz_logic_wp_custom_get_div_clear_html("15") . "
    <div id='div-wp-any-form-config-msg' class='lblmsg' ></div>
    <div id='div-admin-config-tabs' >
        <ul>
            <li><a href='#tabs-ui-theme' >" . _x("UI Theme", "Plugin configuration UI Theme tab heading", "wp-any-form") . "</a></li>
            <li><a href='#tabs-recaptcha' >" . _x("ReCaptcha", "Plugin configuration ReCaptcha tab heading", "wp-any-form") . "</a></li>
        </ul>
        <div id='tabs-ui-theme' >
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
            <div class='div-any-form-row' >
                <div class='div-any-form-cell lbl w185' >
                    " . __("Select UI theme:", "wp-any-form") . "
                </div>
                <div class='div-any-form-cell' >
                    " . biz_logic_wp_custom_get_ui_theme_dirs_ddl($selected_ui_theme) . "
                    &nbsp;
                        " . ASTERISK_HTML_STR . "
                </div>
            </div>
            <div id='div-any-form-row_custom_theme' class='div-any-form-row' >
                <div class='div-any-form-cell lbl w185' >
                    " . _x("Specify custom theme:", "UI Theme configuration label", "wp-any-form") . "
                </div>
                <div class='div-any-form-cell' >
                    " . BIZLOGIC_PLUGIN_FOLDER . "/css/jquery-ui-themes/<input id='txt_custom_theme' class='txt_control_options' value='" . $path_to_custom_ui_theme . "' />
                    &nbsp;
                        " . ASTERISK_HTML_STR . "
                    &nbsp;
                    <a id='acmd-help_specifycustomuitheme' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
                </div>
                <input id='hval-custom-theme-path-is-valid' type='hidden' value='no' />
            </div>
            <div class='div-any-form-row' >
                <div class='div-any-form-cell lbl w185' >
                    " . __("Exclude from:", "wp-any-form") . "
                </div>
                <div class='div-any-form-cell' >
                    " . biz_logic_wp_custom_get_cbx_html($cbx_exclude_ui_theme_admin_options) . "
                </div>
                &nbsp;
                    <a id='acmd-help_excludeuitheme' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
            </div>
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
        </div>
        <div id='tabs-recaptcha' >
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
            <div class='div-any-form-row' >
                <div class='div-any-form-cell lbl w185' >
                    " . __("ReCaptcha Site Key:", "wp-any-form") . "
                </div>
                <div class='div-any-form-cell' >
                    <input id='txt_recaptcha_site_key' class='txt_control_options' value='" . $recaptcha_site_key . "' />
                    &nbsp;
                    <a id='acmd-help_recaptchaconfig' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
                </div>
            </div>
            <div class='div-any-form-row' >
                <div class='div-any-form-cell lbl w185' >
                    " . __("ReCaptcha Secret Key:", "wp-any-form") . "
                </div>
                <div class='div-any-form-cell' >
                    <input id='txt_recaptcha_secret_key' class='txt_control_options' value='" . $recaptcha_secret_key . "' />
                </div>
            </div>
            <div class='div-any-form-row' >
                <div class='div-any-form-cell lbl w185' >
                    " . __("ReCaptcha Language:", "wp-any-form") . "
                </div>
                <div class='div-any-form-cell' >
                    " . biz_logic_wp_custom_get_recaptcha_language_ddl($recaptcha_language) . "
                </div>
            </div>
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
        </div>
        " . biz_logic_wp_custom_get_div_clear_html("15") . "
    </div>" . biz_logic_wp_custom_get_div_clear_html("15") . 
"   <input id='btn_save_config' class='button-primary' type='submit' value='" . __("Save Configuration", "wp-any-form") . "' />
</div>";
    return $html_str;
}

function get_data_grids_html() {
    $first_option_arr = array("-1" => _x("Select form", "Data grids select form text", "wp-any-form"));
    $form_posts_drop_down_html = biz_logic_wp_custom_get_form_posts_drop_down_html(true, "", "ddl_the_form_post_ids", "", $first_option_arr, "");
    $html_str = "
<div id='div-admin-data-grids-container' class='wrap' >
    <h2>" . __("Data Grids and Export CSV", "wp-any-form") . "</h2>
    " . biz_logic_wp_custom_get_div_clear_html("15") . "
    <div id='div-admin-data-grid-options' >
        <div id='div-wpaf-table-admin-data-grid-options' class='div-wpaf-table' >
            <div class='div-wpaf-table-row' >
                <div class='div-wpaf-table-cell' >
                    " . $form_posts_drop_down_html . "    
                </div>
                <div class='div-wpaf-table-cell' >
                    <input id='btn_get_data_grid' class='button-primary' type='submit' value='" . __("Get Grid", "wp-any-form") . "' />
                </div>
                <div class='div-wpaf-table-cell' >
                    <input id='btn_export_csv' class='button-primary' type='submit' value='" . __("Export CSV", "wp-any-form") . "' />
                </div>
                <div id='div-wpaf-table-cell-csv-file' class='div-wpaf-table-cell' ></div>
            </div>
            <div class='div-wpaf-table-row' >
                <label id='lblmsg-admin-data-grids' class='lblmsg' >&nbsp;</label>
            </div>
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
            <div class='div-wpaf-table-row' >
                <div id='div-admin-data-grids-options-custom-field-keys-select-container' class='div-wpaf-table-cell' >

                </div>
            </div>
        </div>
    </div>
    " . biz_logic_wp_custom_get_div_clear_html("25") . "
    <div id='div-admin-data-grid-container' ></div>
</div>";  
    return $html_str;
}

function get_go_pro_html() {
    $html_str = "
<div id='div-admin-go-pro' class='wrap' >
    <h2>" . _x("Go Pro", "Plugin go pro heading", "wp-any-form") . "</h2>
    " . biz_logic_wp_custom_get_div_clear_html(false) . "
    <p class='p-go-pro-larger' >If you require more functionality for your forms consider upgrading to the <a href='http://biz-logic.co.za/downloads/wp-any-form/' target='_blank' >WP Any Form (Pro)</a> version now!</p>
    " . biz_logic_wp_custom_get_div_clear_html(false) . "
    <h1 class='go-pro-features-h1' >Pro Features:</h1>
    <ul class='ul-go-pro-main-features' >
        <li>Form Logic</li>
        <li>Additional form controls (File Upload, Html Editor, Date Picker, Time Picker, Slider and Signature Pad)</li>
        <li>Redirect form on submit</li>
        <li>WordPress Register and Login forms</li>
        <li>Subscribe to MailChimp list</li>
        <li>Live Preview</li>
    </ul>
    " . biz_logic_wp_custom_get_div_clear_html("5") . "
    <a href='http://biz-logic.co.za/downloads/wp-any-form/' target='_blank' class='button-primary' >Go Pro</a>
    <p class='p-go-pro-larger' >More info on Pro Features:</p>
    <div id='div-admin-go-pro-tabs' >
        <ul>
            <li><a href='#tabs-form-logic' >Form Logic</a></li>
            <li><a href='#tabs-controls' >Controls</a></li>
            <li><a href='#tabs-redirect' >Redirect</a></li>
            <li><a href='#tabs-wp-register-login' >WP Register, Login</a></li>
            <li><a href='#tabs-mailchimp' >MailChimp</a></li>
            <li><a href='#tabs-live-preview' >Live Preview</a></li>
        </ul>
        <div id='tabs-form-logic' >
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
            <h3 class='go-pro-h3' >Logic Actions Available:</h3>
            <ul class='ul-go-pro' >
                <li>Show or hide controls</li>
                <li>Redirect to url *</li>
                <li>Send specific email template(s) *</li>
            </ul>
            <p class='p-go-pro' >* Based on the values of specific form controls</p>
            <p class='p-go-pro' >The specific form controls to which form logic can be applied are controls of type Drop Down List, Checkbox and Radio Button. The values of these controls will be evaluated when the logical condition or rule are evaluated.</p>
            <p class='p-go-pro' >The following controls can be made visible or hidden:</p>
            <p class='p-go-pro' >Label, Text Box, Text Area, Drop Down List, Checkbox, Radio Button, File Upload, Html Editor, Date Picker, Time Picker, Slider, Signature Pad, Reset Button and Submit Button.</p>
            <p class='p-go-pro' >The show and hide actions happens immediately when the value of the control it is applied to changes whereas the redirect and email template actions only happen when the form is submitted successfully.</p>
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
        </div>
        <div id='tabs-controls' >
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
            <h3 class='go-pro-h3' >File Upload</h3>
            <ul class='ul-go-pro' >
                <li>Upload multiple files per file upload control</li>
                <li>More than one file upload control allowed per form</li>
                <li>Set file upload control to require at least one upload</li>
                <li>Use together with Html Editor control as a means of uploading images to use in Html Editor control content</li>
            </ul>
            <h3 class='go-pro-h3' >Html Editor</h3>
            <p class='p-go-pro' >Allow users to submit html content with multiple html functions including:</p>
            <ul class='ul-go-pro' >
                <li>Text formatting e.g. bold, underline and more</li>
                <li>Font formatting e.g. font family, font size, colour and more</li>
                <li>Bullet lists</li>
                <li>Text indent, align</li>
                <li>Tables</li>
                <li>Link to image</li>
                <li>Insert image from File Upload control</li>
                <li>Insert html anchor (link)</li>
                <li>View / edit html source</li>
            </ul>
            <h3 class='go-pro-h3' >Date Picker</h3>
            <p class='p-go-pro' >Date Picker pop up calendar date selection control with extensive options:</p>
            <ul class='ul-go-pro' >
                <li>Date Format</li>
                <li>Number of months</li>
                <li>Minimum and maximum date</li>
                <li>Date Range</li>
            </ul>
            <h3 class='go-pro-h3' >Time Picker</h3>
            <p class='p-go-pro' >Time Picker pop up time selection control:</p>
            <ul class='ul-go-pro' >
                <li>Select Hours and / or Minutes</li>
                <li>Minimum and maximum time</li>
            </ul>
            <h3 class='go-pro-h3' >Slider</h3>
            <p class='p-go-pro' >Enable the user to select a value or range of values by dragging a slider between a minimum and maximum range of possible values.</p>
            <h3 class='go-pro-h3' >Signature Pad</h3>
            <p class='p-go-pro' >The perfect form control for obtaining a signature:</p>
            <ul class='ul-go-pro' >
                <li>Easy to use signature control that enables user to apply signature by drawing on a canvas</li>
                <li>Signature is saved as image and attached to saved form data</li>
                <li>Signature image can also be sent to specified email address as attachent using email template functionality</li>
                <li>Signature control can be set to be a required field</li>
            </ul>
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
        </div>
        <div id='tabs-redirect' >
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
            <ul class='ul-go-pro' >
                <li>Redirect the form to a specific url after form is successfully submitted</li>
                <li>Specify amount of seconds to wait before redirecting</li>
            </ul>
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
        </div>
        <div id='tabs-wp-register-login' >
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
            <p class='p-go-pro' >Create a WordPress Register or Login form.</p>
            <p class='p-go-pro' >Use together with redirect to redirect user to dashboard or other specific url after registration or login.</p>
            <h3 class='go-pro-h3' >Register Options</h3>
            <ul class='ul-go-pro' >
                <li>Send WordPress automated new user notification email</li>
                <li>Auto authenticate (log in) new WordPress user after registration </li>
            </ul>
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
        </div>
        <div id='tabs-mailchimp' >
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
            <p class='p-go-pro' >Create a MailChimp subscribe form to subscribe the user email address to a specific MailChimp list.</p>
            <p class='p-go-pro' >Use together with WP Register to also subscribe the user to your website if required.</p>
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
        </div>
        <div id='tabs-live-preview' >
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
            <p class='p-go-pro' >Live preview is an extra view pane available on the form editor screen that utilises the theme currently selected for your WordPress website to give you a quick, useful representation of how the form will look when used with your theme.</p>
            <p class='p-go-pro' >There is no need to navigate to another page for a live preview, simply switch the view.</p>
            <p class='p-go-pro' >Any changes made to the controls are immediately viewable in either the Form Builder or Preview mode, without having to save the form first.</p>
            " . biz_logic_wp_custom_get_div_clear_html("15") . "
        </div>
    </div>
    " . biz_logic_wp_custom_get_div_clear_html("15") . "
</div>";
    return $html_str;
}

function add_custom_meta_boxes($postType) {
    switch ($postType) {
        case "wp_any_form":
            add_meta_box('wp_any_form_builder_shortcode', _x("Shortcode", "Form editor shortcode meta box heading", "wp-any-form"), array($this, 'wp_any_form_builder_shortcode_gethtml'), $postType, 'normal', 'high');
            add_meta_box('wp_any_form_builder_controls', _x("Controls", "Form editor controls meta box heading", "wp-any-form"), array($this, 'wp_any_form_builder_controls_gethtml'), $postType, 'normal', 'high');
            add_meta_box('wp_any_form_builder_form', _x("Form", "Form editor form meta box heading", "wp-any-form"), array($this, 'wp_any_form_builder_form_gethtml'), $postType, 'normal', 'high');
            add_meta_box('wp_any_form_builder_style', _x("Style", "Form editor style meta box heading", "wp-any-form"), array($this, 'wp_any_form_builder_style_gethtml'), $postType, 'normal', 'high');
            add_meta_box('wp_any_form_builder_submit', _x("Submit", "Form editor submit meta box heading", "wp-any-form"), array($this, 'wp_any_form_builder_submit_gethtml'), $postType, 'normal', 'high');
            add_meta_box('wp_any_form_builder_form_messages', _x("Messages", "Form editor messages meta box heading", "wp-any-form"), array($this, 'wp_any_form_builder_form_messages_gethtml'), $postType, 'normal', 'high');
            add_meta_box('wp_any_form_pop_up_form_options', _x("Pop Up Form", "Form editor pop up form meta box heading", "wp-any-form"), array($this, 'wp_any_form_pop_up_form_options_gethtml'), $postType, 'normal', 'high');
            add_meta_box('wp_any_form_builder_custom_css', _x("Custom CSS", "Form editor custom css meta box heading", "wp-any-form"), array($this, 'wp_any_form_builder_custom_css_gethtml'), $postType, 'normal', 'high');
            break;
        case "wp_any_form_data":
            add_meta_box('wp_any_form_saved_data', _x("Saved Form Data", "Saved Form Data meta box heading", "wp-any-form"), array($this, 'wp_any_form_saved_data_gethtml'), $postType, 'normal', 'high');
            break;
        case "wpaf_email_templates":
            add_meta_box('wp_any_form_email_templates', _x("Email Options", "Email Options meta box heading", "wp-any-form"), array($this, 'wp_any_form_email_template_options_gethtml'), $postType, 'normal', 'high');
            break;
    }
}

function wp_any_form_builder_shortcode_gethtml() {
    global $post;
    $the_post_id = $post->ID;
    $the_shortcode_html_str = "[wp_any_form display=\"form\" pid=\"" . $the_post_id . "\"]";
    $html_str = "
    <div id='div-wp-any-form-builder-shortcode' >
        " . $the_shortcode_html_str . "
    </div>" . biz_logic_wp_custom_get_div_clear_html(false);
    $html_str .= "<input id='hval-the-form-post-id' type='hidden' value='" . $the_post_id . "' />";
    $html_str .= biz_logic_wp_custom_get_default_admin_content("form-builder-shortcode");
    echo $html_str;
}

function wp_any_form_builder_controls_gethtml() {
    $html_str = "
    <div id='div-wp-any-form-builder-controls' >
        <ul id='ul-form-builder-controls' >
            <li><a id='a-form-builder-control_lbl' class='a-form-builder-control' href='javascript:void(0);' >" . WPAF_CONTROL_FULL_TITLE_LABEL . "</a></li>
            <li><a id='a-form-builder-control_txt' class='a-form-builder-control' href='javascript:void(0);' >" . WPAF_CONTROL_FULL_TITLE_TEXTBOX . "</a></li>
            <li><a id='a-form-builder-control_txta' class='a-form-builder-control' href='javascript:void(0);' >" . WPAF_CONTROL_FULL_TITLE_TEXTAREA . "</a></li>
            <li><a id='a-form-builder-control_ddl' class='a-form-builder-control' href='javascript:void(0);' >" . WPAF_CONTROL_FULL_TITLE_DROPDOWNLIST . "</a></li>
            <li><a id='a-form-builder-control_cbx' class='a-form-builder-control' href='javascript:void(0);' >" . WPAF_CONTROL_FULL_TITLE_CHECKBOX . "</a></li>
            <li><a id='a-form-builder-control_rbtn' class='a-form-builder-control' href='javascript:void(0);' >" . WPAF_CONTROL_FULL_TITLE_RADIOBUTTON . "</a></li>
            <li><a id='a-form-builder-control_recaptcha' class='a-form-builder-control' href='javascript:void(0);' >" . WPAF_CONTROL_FULL_TITLE_RECAPTCHA . "</a></li>
            <li><a id='a-form-builder-control_btnreset' class='a-form-builder-control' href='javascript:void(0);' >" . WPAF_CONTROL_FULL_TITLE_BTNRESET . "</a></li>
            <li><a id='a-form-builder-control_btnsubmit' class='a-form-builder-control' href='javascript:void(0);' >" . WPAF_CONTROL_FULL_TITLE_BTNSUBMIT . "</a></li>
            <li><a id='a-form-builder-control_emptycell' class='a-form-builder-control' href='javascript:void(0);' >" . WPAF_CONTROL_FULL_TITLE_EMPTYCELL . "</a></li>
        </ul>
    </div>" . biz_logic_wp_custom_get_div_clear_html(false);
    echo '<input type="hidden" name="' . BIZLOGIC_UNIQUE_PLUGIN_NAME . '_wp_any_form_form_meta_noncename" id="' . BIZLOGIC_UNIQUE_PLUGIN_NAME . '_wp_any_form_form_meta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    $html_str .= biz_logic_wp_custom_get_default_admin_content("form-builder-controls");
    echo $html_str;
}

function wp_any_form_builder_form_gethtml() {
    global $post;
    $the_post_id = $post->ID;
    $html_str = "
    <div class='div-wp-any-form-builder-form-container' >";
    $hval_form_builder_arr_for_save_meta = "";
    $hval_form_builder_arr = biz_logic_wp_custom_get_post_meta($the_post_id, "hval_form_builder_arr", "");
    $have_saved_arr_not_empty = false;
    if($hval_form_builder_arr != "") {
        $hval_form_builder_arr = json_decode($hval_form_builder_arr, true);
        if (!biz_logic_wp_custom_check_if_array_empty($hval_form_builder_arr)) {
            $have_saved_arr_not_empty = true;            
        }
    }
    if($have_saved_arr_not_empty) {
        $html_str .= biz_logic_wp_custom_build_form_from_saved_data_admin($hval_form_builder_arr);    
        $hval_form_builder_arr_for_save_meta = json_encode($hval_form_builder_arr);
    } else {
        $html_str .= "
        <div id='div-wp-any-form-builder-form' class='div-wp-any-form-builder-form' >
            <div id='div-wp-any-form-builder-form-msg' class='div-wp-any-form-builder-form-msg' ></div>
            <div id='div-wp-any-form-builder-form-rows-container' >
                <div id='div-wp-any-form-builder-form-row_1' class='div-wp-any-form-builder-form-row' >
                    <div id='div-wp-any-form-builder-form-cell_1_1' class='div-wp-any-form-builder-form-cell' >
                    
                    </div>
                    " . biz_logic_wp_custom_get_form_builder_img_cmd_html("addcell", "1") . "
                    <input id='hval-form-cell-count-row_1' type='hidden' value='1' />                
                </div>
                " . biz_logic_wp_custom_get_div_clear_html(false) . "
                " . biz_logic_wp_custom_get_form_builder_img_cmd_html("addrow") . "
            </div>
        </div>
        " . biz_logic_wp_custom_get_div_clear_html(false) . "
        <input id='hval-form-row-count' type='hidden' value='1' />";    
    }
    $html_str .= "
    </div>
    " . biz_logic_wp_custom_get_div_clear_html(false) . "
    <input id='hval_form_builder_arr' name='hval_form_builder_arr' type='hidden' value='" . $hval_form_builder_arr_for_save_meta . "' />" . biz_logic_wp_custom_get_default_admin_content("form-builder-form") . biz_logic_wp_custom_get_ui_dialog_form_html("div-dialog-form-builder", "div-dialog-content-form-builder");
    echo $html_str;
}

function wp_any_form_builder_style_gethtml() {
    global $post;
    $the_post_id = $post->ID;
    $form_default_form_width = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_form_width", "");
    $form_default_layout = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_layout", "auto");
    $form_default_cells_per_row = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_cells_per_row", "");
    $form_default_cell_spacing = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_cell_spacing", "");
    $form_default_row_spacing = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_row_spacing", "");
    $form_default_cell_padding = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_cell_padding", "");
    $form_default_font_size = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_font_size", "");
    $form_default_font_weight = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_font_weight", "");
    $form_default_font_colour = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_font_colour", "#000000");
    $form_default_font_colour_use_defined = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_font_colour_use_defined", "");
    $form_default_message_font_colour = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_message_font_colour", "#cc0000");
    $form_default_required_field_font_colour = biz_logic_wp_custom_get_post_meta($the_post_id, "form_default_required_field_font_colour", "#cc0000");
    $form_cell_vertical_align = biz_logic_wp_custom_get_post_meta($the_post_id, "form_cell_vertical_align", "middle");
    $cbx_form_default_font_colour_use_defined_options = array(
        "name_str" => "form_default_font_colour_use_defined",
        "id_str" => "cbx_form_default_font_colour_use_defined",
        "the_label" => _x("Use defined value", "Form style option", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => $form_default_font_colour_use_defined
    );
    $html_str = "
    <div id='div-wp-any-form-builder-style' >
        <div id='div-wp-any-form-builder-style-msg' class='lblmsg' >" . _x("Select None or leave empty to default to active theme styles.", "Form builder style info message", "wp-any-form") . "</div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                <a id='a-form-builder-refresh' class='button button-primary' href='javascript:void(0);' >" . __("Refresh Form", "wp-any-form") . "</a>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Form Width:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <input name='form_default_form_width' id='txt_form_default_form_width' class='txt_control_options txt_form_default_form_width numbers_only' value='" . $form_default_form_width . "' />
                &nbsp;
                px
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Layout:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                " . biz_logic_wp_custom_get_form_layout_ddl_html_str("form_default_layout", "ddl_form_default_layout", $form_default_layout) . "
                &nbsp;
                <a id='acmd-help_formdefaultlayout' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Cells per row:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <input name='form_default_cells_per_row' id='txt_form_default_cells_per_row' class='txt_control_options txt_form_default_cells_per_row numbers_only' value='" . $form_default_cells_per_row . "' />
                &nbsp;
                <a id='acmd-help_formlayoutcellsperrow' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Cell spacing:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <input name='form_default_cell_spacing' id='txt_form_default_cell_spacing' class='txt_control_options txt_form_default_cell_spacing numbers_only' value='" . $form_default_cell_spacing . "' />
                &nbsp;
                px
                &nbsp;
                <a id='acmd-help_formlayoutcellspacing' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Row spacing:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <input name='form_default_row_spacing' id='txt_form_default_row_spacing' class='txt_control_options txt_form_default_row_spacing numbers_only' value='" . $form_default_row_spacing . "' />
                &nbsp;
                px
                &nbsp;
                <a id='acmd-help_formlayoutrowspacing' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Cell padding:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <input name='form_default_cell_padding' id='txt_form_default_cell_padding' class='txt_control_options txt_form_default_cell_padding numbers_only' value='" . $form_default_cell_padding . "' />
                &nbsp;
                px
                &nbsp;
                <a id='acmd-help_formlayoutcellpadding' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Font Size:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <input name='form_default_font_size' id='txt_form_default_font_size' class='txt_control_options txt_default_font_size numbers_only' value='" . $form_default_font_size . "' />
                &nbsp;
                px
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Font Weight:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                " . biz_logic_wp_custom_get_font_weight_ddl_html_str("form_default_font_weight", "ddl_form_default_font_weight", $form_default_font_weight) . "
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Font Colour:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <input name='form_default_font_colour' id='txt_form_default_font_colour' class='txt_control_options txt_form_default_font_colour' value='" . $form_default_font_colour . "' />
            &nbsp;
                " . biz_logic_wp_custom_get_cbx_html($cbx_form_default_font_colour_use_defined_options) . "
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Form message font Colour:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <input name='form_default_message_font_colour' id='txt_form_default_message_font_colour' class='txt_control_options txt_form_default_message_font_colour' value='" . $form_default_message_font_colour . "' />
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Required fields asterisk (*) Colour:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <input name='form_default_required_field_font_colour' id='txt_form_default_required_field_font_colour' class='txt_control_options txt_form_default_required_field_font_colour' value='" . $form_default_required_field_font_colour . "' />
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . _x("Vertical alignment:", "Form style option", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                " . biz_logic_wp_custom_get_form_cell_vertical_align_ddl_html_str("form_cell_vertical_align", "ddl_form_cell_vertical_align", $form_cell_vertical_align) . "
                &nbsp;
                <a id='acmd-help_formlayoutverticalalign' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
            </div>
        </div>
    </div>" . biz_logic_wp_custom_get_div_clear_html(false);
    $html_str .= biz_logic_wp_custom_get_default_admin_content("form-builder-style");
    echo $html_str;
}

function wp_any_form_builder_submit_gethtml() {
    global $post;
    $the_post_id = $post->ID;
    $form_submit_save_submission = biz_logic_wp_custom_get_post_meta($the_post_id, "form_submit_save_submission", "");
    $form_submit_send_email = biz_logic_wp_custom_get_post_meta($the_post_id, "form_submit_send_email", "");
    $cbx_form_submit_save_submission_options = array(
        "name_str" => "form_submit_save_submission",
        "id_str" => "cbx_form_submit_save_submission",
        "the_label" => _x("Save form data", "Form submit option", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => $form_submit_save_submission
    );
    $cbx_form_submit_send_email_options = array(
        "name_str" => "form_submit_send_email",
        "id_str" => "cbx_form_submit_send_email",
        "the_label" => _x("Send email(s) with form data", "Form submit option", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => $form_submit_send_email
    );
    $html_str = "
    <div id='div-wp-any-form-builder-submit' >
        <div id='div-wp-any-form-builder-submit-msg' class='lblmsg' >" . _x("Set options for when form is submitted.", "Form submit options info message", "wp-any-form") . "</div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                " . biz_logic_wp_custom_get_cbx_html($cbx_form_submit_save_submission_options) . "
            </div>
            <div class='div-any-form-cell' >
                " . biz_logic_wp_custom_get_cbx_html($cbx_form_submit_send_email_options) . "
            </div>
        </div>
        <div id='div-any-form-row_send_email_options' class='div-any-form-row' >
            <div class='div-any-form-cell' >
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell' >
                        " . _x("Select email template(s)", "Submit form options select email template(s) label", "wp-any-form") . "
                    </div>        
                </div>
                <div class='div-any-form-row' >
                    <div class='div-any-form-cell' >
                        " . biz_logic_wp_custom_email_options_get_email_templates_for_form($the_post_id) . "        
                    </div>        
                </div>
            </div>
        </div>
    </div>" . biz_logic_wp_custom_get_div_clear_html(false);
    $html_str .= biz_logic_wp_custom_get_default_admin_content("form-builder-submit");
    echo $html_str;
}

function wp_any_form_builder_form_messages_gethtml() {
    global $post;
    $the_post_id = $post->ID;
    $wpa_form_messages_required_fields = esc_textarea(biz_logic_wp_custom_get_post_meta($the_post_id, "wpa_form_messages_required_fields", _x("* Required fields.", "Default required fields message (Form messages option)", "wp-any-form")));
    $wpa_form_messages_contacting_server = esc_textarea(biz_logic_wp_custom_get_post_meta($the_post_id, "wpa_form_messages_contacting_server", _x("Contacting server, please wait...", "Default contacting server message (Form messages option)", "wp-any-form")));
    $wpa_form_messages_success = esc_textarea(biz_logic_wp_custom_get_post_meta($the_post_id, "wpa_form_messages_success", _x("Form submitted successfully.", "Default successful form submission message (Form messages option)", "wp-any-form")));
    $wpa_form_submit_revert_to_required_msg = biz_logic_wp_custom_get_post_meta($the_post_id, "wpa_form_submit_revert_to_required_msg", "");
    $cbx_form_submit_revert_to_required_msg_options = array(
        "name_str" => "wpa_form_submit_revert_to_required_msg",
        "id_str" => "cbx_form_submit_revert_to_required_msg",
        "the_label" => _x("Revert to required fields message after success message", "Form messages option", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => $wpa_form_submit_revert_to_required_msg
    );
    $wpa_form_submit_scroll_to_msg = biz_logic_wp_custom_get_post_meta($the_post_id, "wpa_form_submit_scroll_to_msg", "");
    $cbx_form_submit_scroll_to_msg_options = array(
        "name_str" => "wpa_form_submit_scroll_to_msg",
        "id_str" => "cbx_form_submit_scroll_to_msg",
        "the_label" => _x("Scroll to form message when text changes", "Form messages option", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => $wpa_form_submit_scroll_to_msg
    );
    $html_str = "
    <div id='div-wp-any-form-builder-custom-css' >
        <div id='div-wp-any-form-builder-messages-msg' class='lblmsg' >" . __("Customise messages for form.", "wp-any-form") . "</div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . __("Required fields message:", "wp-any-form") . " 
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                <textarea name='wpa_form_messages_required_fields' id='txta_form_messages_required_fields' class='txta_form_messages' >" . $wpa_form_messages_required_fields . "</textarea>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' class='div-any-form-cell div-any-form-cell-info' >
                " . __("The * if included will be replaced with specific html to enable custom font styles.", "wp-any-form") . "
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . __("Contacting server message:", "wp-any-form") . "
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                <textarea name='wpa_form_messages_contacting_server' id='txta_form_messages_contacting_server' class='txta_form_messages' >" . $wpa_form_messages_contacting_server . "</textarea>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl' >
                " . __("Successful form submission message:", "wp-any-form") . "
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                <textarea name='wpa_form_messages_success' id='txta_form_messages_success' class='txta_form_messages' >" . $wpa_form_messages_success . "</textarea>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                " . biz_logic_wp_custom_get_cbx_html($cbx_form_submit_scroll_to_msg_options) . "
            </div>        
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                " . biz_logic_wp_custom_get_cbx_html($cbx_form_submit_revert_to_required_msg_options) . "
            </div>        
        </div>
    </div>" . biz_logic_wp_custom_get_div_clear_html(false);
    $html_str .= biz_logic_wp_custom_get_default_admin_content("form-builder-messages");
    echo $html_str;
}

function wp_any_form_pop_up_form_options_gethtml() {
    global $post;
    $the_post_id = $post->ID;
    $pop_up_form_is_pop_up = biz_logic_wp_custom_get_post_meta($the_post_id, "pop_up_form_is_pop_up", "no");
    $pop_up_form_bg_colour = biz_logic_wp_custom_get_post_meta($the_post_id, "pop_up_form_bg_colour", "#ffffff");
    $pop_up_form_link_text = biz_logic_wp_custom_get_post_meta($the_post_id, "pop_up_form_link_text", "");
    $pop_up_form_link_type = biz_logic_wp_custom_get_post_meta($the_post_id, "pop_up_form_link_type", "");
    $cbx_pop_up_form_is_pop_up_options = array(
        "name_str" => "pop_up_form_is_pop_up",
        "id_str" => "cbx_pop_up_form_is_pop_up",
        "the_label" => _x("Form is pop up form", "Pop up form option", "wp-any-form"),
        "the_value" => "yes",
        "checked_str" => $pop_up_form_is_pop_up
    );
    $ddl_pop_up_form_link_type_options_arr = array(
        "link" => _x("Link", "Pop up form link type option", "wp-any-form"),
        "btn" => _x("Button", "Pop up form link type option", "wp-any-form")
    );
    $html_str = "
    <div id='div-wp-any-form-pop-up-options' >
        <div id='div-wp-any-form-pop-up-options-msg' class='lblmsg' ></div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                " . biz_logic_wp_custom_get_cbx_html($cbx_pop_up_form_is_pop_up_options) . "
            </div>
        </div>
        <div id='div-pop-up-form-options' >
            <div class='div-any-form-row' >
                <div class='div-any-form-cell lbl' >
                    " . __("Pop up background colour:", "wp-any-form") . "
                </div>
                <div class='div-any-form-cell' >
                    <input name='pop_up_form_bg_colour' id='txt_pop_up_form_bg_colour' class='txt_control_options' value='" . $pop_up_form_bg_colour . "' />
                </div>
            </div>
            <div class='div-any-form-row' >
                <div class='div-any-form-cell lbl' >
                    " . _x("Open form link", "Pop up form options open form link heading", "wp-any-form") . "
                </div>
            </div>
            <div class='div-any-form-row' >
                <div class='div-any-form-cell lbl' >
                    " . _x("Text:", "Pop up form options open form link text label", "wp-any-form") . "
                </div>
                <div class='div-any-form-cell' >
                    <input name='pop_up_form_link_text' id='txt_pop_up_form_link_text' class='txt_control_options' type='text' value='" . $pop_up_form_link_text . "' placeholder='" . _x("Enter link text", "Pop up form options open form link text placeholder", "wp-any-form") . "' />
                </div>
            </div>
            <div class='div-any-form-row' >
                <div class='div-any-form-cell lbl' >
                    " . _x("Type:", "Pop up form options open form link type label", "wp-any-form") . "
                </div>
                <div class='div-any-form-cell' >
                    " . biz_logic_wp_custom_get_ddl_html("pop_up_form_link_type", "ddl_pop_up_form_link_type", "", "", $ddl_pop_up_form_link_type_options_arr, $pop_up_form_link_type) . "
                </div>
            </div>
        </div>
    </div>" . biz_logic_wp_custom_get_div_clear_html(false);
    $html_str .= biz_logic_wp_custom_get_default_admin_content("pop-up-form-options");
    echo $html_str;
}

function wp_any_form_builder_custom_css_gethtml() {
    global $post;
    $the_post_id = $post->ID;
    $wp_any_form_custom_css = esc_textarea(biz_logic_wp_custom_get_post_meta($the_post_id, "wp_any_form_custom_css", ""));
    $html_str = "
    <div id='div-wp-any-form-builder-custom-css' >
        <div id='div-wp-any-form-builder-custom-css-msg' class='lblmsg' >
        " . _x("Define custom css for the form here.", "Form custom css info message", "wp-any-form") . "<br />
        " . _x("The form id's are", "Form custom css form id's info message", "wp-any-form") . "<br />
        " . _x("Builder:", "Form custom css builder form id label", "wp-any-form") . " <span class='span-red-color' >div-wp-any-form-builder-form</span> " . _x("and", "Form custom css builder form id's info message 'and'", "wp-any-form") . "<br />
        " . _x("Where displayed on site:", "Form custom css public form id label", "wp-any-form") . " <span class='span-red-color' >div-wp-any-form_" . $the_post_id . "</span>
        </div>
        <textarea id='txta_wp_any_form_custom_css' name='wp_any_form_custom_css' >" . $wp_any_form_custom_css . "</textarea>
    </div>" . biz_logic_wp_custom_get_div_clear_html(false);
    $html_str .= biz_logic_wp_custom_get_default_admin_content("form-builder-custom-css");
    echo $html_str;
    echo "<style type='text/css' >" . $wp_any_form_custom_css . "</style>";
}

function wp_any_form_save_custom_fields_meta($post_id, $post) {
    if (!wp_verify_nonce( $_POST[BIZLOGIC_UNIQUE_PLUGIN_NAME . '_wp_any_form_form_meta_noncename'], plugin_basename(__FILE__) )) {
        return $post_id;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    if ($post->post_type == 'revision') {
        return;
    }
    $txt_pop_up_form_link_text = htmlentities($_POST["pop_up_form_link_text"], ENT_QUOTES);
    $the_custom_fields_names_arr = array("hval_form_builder_arr", "form_default_form_width", "form_default_layout", "form_default_cells_per_row", "form_default_cell_spacing", "form_default_row_spacing", "form_default_cell_padding", "form_default_font_size", "form_default_font_weight", "form_default_font_colour", "form_default_font_colour_use_defined", "form_default_message_font_colour", "form_default_required_field_font_colour", "form_cell_vertical_align", "wp_any_form_custom_css", "form_submit_save_submission", "form_submit_send_email", "wpa_form_messages_required_fields", "wpa_form_messages_contacting_server", "wpa_form_messages_success", "wpa_form_submit_revert_to_required_msg", "wpa_form_submit_scroll_to_msg", "pop_up_form_is_pop_up", "pop_up_form_bg_colour"/*, "pop_up_form_link_text"*/, "pop_up_form_link_type");
    $custom_fields_meta = array();
    foreach ($the_custom_fields_names_arr as $the_custom_fields_name) {
        $custom_fields_meta[$the_custom_fields_name] = $_POST[$the_custom_fields_name];    
    }
    $custom_fields_meta["pop_up_form_link_text"] = $txt_pop_up_form_link_text;    
    foreach ($custom_fields_meta as $key => $value) { 
        $value = implode(',', (array)$value);
        if(get_post_meta($post_id, $key, FALSE)) {
            update_post_meta($post_id, $key, $value);
        } else {
            add_post_meta($post_id, $key, $value);
        }
        if(!$value) delete_post_meta($post_id, $key);
    }
    $the_key = "wpaf_email_templates_selected";
    delete_post_meta($post_id, $the_key);
    if(!empty($_POST['wpaf_email_templates_selected'])) {
        $the_email_templates_arr = $_POST['wpaf_email_templates_selected'];
        if(count($the_email_templates_arr) > 0) {
            foreach ($the_email_templates_arr as $the_value) {
                add_post_meta($post_id, $the_key, $the_value);
            }
        }    
    }
}

function wp_any_form_saved_data_gethtml() {
    global $post;
    $the_post_id = $post->ID;
    $html_str = "
    <div id='div-wp-any-form-saved-data' >
        " . biz_logic_wp_custom_wp_any_form_get_saved_data($the_post_id) . "
    </div>" . biz_logic_wp_custom_get_div_clear_html(false);
    $html_str .= biz_logic_wp_custom_get_default_admin_content("form-post-data");
    echo $html_str;
}

function wp_any_form_email_template_options_gethtml() {
    global $post;
    $the_post_id = $post->ID;
    $email_template_custom_field_form_post_id = biz_logic_wp_custom_get_post_meta($the_post_id, "email_template_custom_field_form_post_id", "");
    $email_template_custom_field_subject = biz_logic_wp_custom_get_post_meta($the_post_id, "email_template_custom_field_subject", "");
    $email_template_custom_field_to_addresses = esc_textarea(biz_logic_wp_custom_get_post_meta($the_post_id, "email_template_custom_field_to_addresses", ""));
    $first_option_arr = array("" => _x("Select form...", "Email template option form select drop down first option", "wp-any-form"));
    $form_posts_drop_down_html = biz_logic_wp_custom_get_form_posts_drop_down_html(false, "email_template_custom_field_form_post_id", "ddl_email_templates_options_form_posts", "", $first_option_arr, $email_template_custom_field_form_post_id);  
    $form_fields_send_to_email_address = esc_textarea(biz_logic_wp_custom_get_post_meta($the_post_id, "form_fields_send_to_email_address", ""));      
    $html_str = "
    <div id='div-wp-any-form-email-template-options' >
        <div id='div-wp-any-form-email-template-options-msg' class='lblmsg' ></div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl w135' >
                " . _x("Form:", "Email template option form select label", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                " . $form_posts_drop_down_html . "
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl w135' >
                " . _x("Email subject:", "Email template option email subject label", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <input name='email_template_custom_field_subject' id='txt_email_template_custom_field_subject' value='" . $email_template_custom_field_subject . "' />
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl w135' >
                " . _x("Send to:", "Email template option send to label", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <textarea id='txta_wp_any_form_email_addresses_to' name='email_template_custom_field_to_addresses' >" . $email_template_custom_field_to_addresses . "</textarea>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                " . __("Seperate email addresses with ; e.g. emailaddress1@domain.com; emailaddress2@domain.com; emailaddress3@otherdomain.com", "wp-any-form") . "
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell lbl w135' >
                " . _x("Auto reply send to:", "Email template option send to (Auto reply) label", "wp-any-form") . "
            </div>
            <div class='div-any-form-cell' >
                <div class='div-any-form-cell_ddl-send-to-email-container' >" . biz_logic_wp_custom_email_options_get_auto_reply_send_to_email_address_form_fields_ddl($email_template_custom_field_form_post_id, $form_fields_send_to_email_address) . "</div>
                &nbsp;
            </div>
            <div class='div-any-form-cell' >
                <a id='acmd-help_autoreplyaddress' class='acmd-help' href='javascript:void(0);' title='" . HELP_CLICK_FOR_MORE_INFO_STR . "' ><img src='" . plugins_url('/img/help.png', dirname(__FILE__ )) . "' /></a>
            </div>
        </div>
        <div class='div-any-form-row' >
            <div class='div-any-form-cell' >
                " . sprintf(__("To insert form fields data into email template click on the %s icon in the html editor toolbar. When email is sent upon form submission the shortcode will be replaced with the data for that specific form field.", "wp-any-form"), "<img src='" . plugins_url('/js/database_add.png', dirname(__FILE__ )) . "' title='" . EMAIL_TEMPLATE_FIELD_SHORTCODE_INSERT_TITLE . "' />") . "
            </div>
        </div>
    </div>" . biz_logic_wp_custom_get_div_clear_html(false);
    $html_str .= biz_logic_wp_custom_get_default_admin_content("email-template-options") . biz_logic_wp_custom_get_ui_dialog_form_html("div-dialog-email-templates-options", "div-dialog-content-email-templates-options");
    echo $html_str;
    echo '<input type="hidden" name="' . BIZLOGIC_UNIQUE_PLUGIN_NAME . '_wp_any_form_email_template_options_meta_noncename" id="' . BIZLOGIC_UNIQUE_PLUGIN_NAME . '_wp_any_form_email_template_options_meta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
}

function wp_any_form_email_templates_options_save_custom_fields_meta($post_id, $post) {
    if (!wp_verify_nonce( $_POST[BIZLOGIC_UNIQUE_PLUGIN_NAME . '_wp_any_form_email_template_options_meta_noncename'], plugin_basename(__FILE__) )) {
        return $post_id;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    if ($post->post_type == 'revision') {
        return;
    }
    $the_custom_fields_names_arr = array("email_template_custom_field_form_post_id", "email_template_custom_field_to_addresses", "email_template_custom_field_subject", "form_fields_send_to_email_address");
    $custom_fields_meta = array();
    foreach ($the_custom_fields_names_arr as $the_custom_fields_name) {
        $custom_fields_meta[$the_custom_fields_name] = $_POST[$the_custom_fields_name];    
    }
    foreach ($custom_fields_meta as $key => $value) { 
        $value = implode(',', (array)$value);
        if(get_post_meta($post_id, $key, FALSE)) {
            update_post_meta($post_id, $key, $value);
        } else {
            add_post_meta($post_id, $key, $value);
        }
        if(!$value) delete_post_meta($post_id, $key);
    }
}

function admin_ajax_submit() {
    $return['error'] = true;	
    $cmd = htmlspecialchars($_POST['cmd']);
    switch ($cmd) {
        case 'get-control-options-html':
            $the_control_type = htmlspecialchars(trim($_POST['the_control_type']));
            $the_action_cmd = htmlspecialchars(trim($_POST['the_action_cmd']));
            $the_control_fields_arr = $_POST['the_control_fields_arr'];
            $return_arr = biz_logic_wp_custom_get_control_options_arr($the_control_type, $the_action_cmd, $the_control_fields_arr);
            switch ($the_control_type) {
                case "ddl":
                    $return['ddl_item_sets_arr'] = $return_arr["ddl_item_sets_arr"];        
                    break;
                case "cbx":
                    $return['items_arr'] = $return_arr["items_arr"];        
                    break;
                case "rbtn":
                    $return['items_arr'] = $return_arr["items_arr"];        
                    break;
            }
            $return['html_str'] = $return_arr["html_str"];
            $return['the_dialog_height'] = $return_arr["the_dialog_height"];
            $return['error'] = false;
            break;
        case 'get-control-html':
            $the_control_fields_arr = $_POST['the_control_fields_arr'];
            $return['html_str'] = biz_logic_wp_custom_get_control_html_admin($the_control_fields_arr);
            $return['error'] = false;
            break;
        case 'get-form-builder-html':
            $the_view = $_POST['the_view'];
            $hval_form_builder_arr = $_POST['the_form_arr'];
            switch ($the_view) {
                case "builder":
                    $return['html_str'] = biz_logic_wp_custom_build_form_from_saved_data_admin($hval_form_builder_arr);  
                    break;
            }
            $return['error'] = false;
            break;
        case 'submit-form':
            $the_form_submit_arr = $_POST['the_form_submit_arr'];
            $the_form_post_id = $_POST['the_form_post_id'];
            $is_in_preview_mode = $_POST['is_in_preview_mode'];
            $the_form_vars_arr = $_POST['the_form_vars_arr'];
            $return_arr = biz_logic_wp_custom_submit_form_action($the_form_post_id, $the_form_submit_arr, $is_in_preview_mode, $the_form_vars_arr);
            $return['return_msg'] = $return_arr['return_msg'];
            $return['the_form_vars_arr'] = $return_arr['the_form_vars_arr'];
            $return['error'] = false;    
            break;    
        case 'get-data-grids-options-custom-field-keys':
            $the_form_post_id = $_POST['the_form_post_id'];
            $return['html_str'] = biz_logic_wp_custom_wp_any_form_get_data_grids_options_custom_field_keys_html($the_form_post_id);
            $return['error'] = false;    
            break;    
        case 'get-data-grid':
            $the_form_post_id = $_POST['the_form_post_id'];
            $the_selected_custom_field_keys_arr = $_POST['the_selected_custom_field_keys_arr'];
            $return['html_str'] = biz_logic_wp_custom_get_data_grid_html($the_form_post_id, $the_selected_custom_field_keys_arr);
            $return['error'] = false;    
            break;  
        case 'export-data-csv':
            $the_form_post_id = $_POST['the_form_post_id'];
            $the_selected_custom_field_keys_arr = $_POST['the_selected_custom_field_keys_arr'];
            $return_arr = biz_logic_wp_custom_export_data_csv_html($the_form_post_id, $the_selected_custom_field_keys_arr);
            if($return_arr["return_msg"] == "ok") {
                $return['the_csv_file_url'] = $return_arr["the_csv_file_url"];    
                $return['error'] = false;    
            } else {
                $return['return_msg'] = $return_arr["return_msg"];    
            }
            break;    
        case 'get-email-templates-form-fields':
            $the_selected_form_post_id = $_POST['the_selected_form_post_id'];
            $return['html_str'] = biz_logic_wp_custom_email_options_get_email_templates_form_fields_ddl($the_selected_form_post_id);
            $return['error'] = false;
            break;
        case 'get-email-templates-form-fields-ddl-to-email-address':
            $the_selected_form_post_id = $_POST['the_selected_form_post_id'];
            $return['html_str'] = biz_logic_wp_custom_email_options_get_auto_reply_send_to_email_address_form_fields_ddl($the_selected_form_post_id, "-1");
            $return['error'] = false;
            break;
        case 'get-ddl-options-ddl-item-sets-html':
            $ddl_item_sets_arr = $_POST['ddl_item_sets_arr'];
            $the_selected_item_set_id = $_POST['the_selected_item_set_id'];
            $return['html_str'] = biz_logic_wp_custom_get_ddl_options_ddl_item_sets_html($ddl_item_sets_arr, $the_selected_item_set_id);
            $return['error'] = false;
            break;
        case 'get-ddl-items-html':
            $the_field_id = $_POST['the_field_id'];
            $hval_form_builder_arr = $_POST['the_form_arr'];
            $ddl_item_sets_arr = $_POST['ddl_item_sets_arr'];
            $the_selected_item_set_id = $_POST['the_selected_item_set_id'];
            $return['html_str'] = biz_logic_wp_custom_get_ddl_options_items_admin_html($the_field_id, $hval_form_builder_arr, $ddl_item_sets_arr, $the_selected_item_set_id);
            $return['error'] = false;
            break;
        case 'get-help-html':
            $for_what = $_POST['for_what'];
            $return['html_str'] = biz_logic_wp_custom_get_help_message_html($for_what);
            $return['error'] = false;
            break;
        case 'get-initial-selected-value-ddl-html':
            $ddl_item_sets_arr = $_POST['ddl_item_sets_arr'];
            $the_initial_selected_value = $_POST['the_initial_selected_value'];
            $return['html_str'] = biz_logic_wp_custom_get_initial_selected_value_ddl_html($ddl_item_sets_arr, $the_initial_selected_value);
            $return['error'] = false;
            break;
        case 'get-cbx-items-html':
            $items_arr = $_POST['items_arr'];
            $return['html_str'] = biz_logic_wp_custom_get_cbx_items_admin_html($items_arr);
            $return['error'] = false;
            break;
        case 'get-rbtn-items-html':
            $items_arr = $_POST['items_arr'];
            $return['html_str'] = biz_logic_wp_custom_get_rbtn_items_admin_html($items_arr);
            $return['error'] = false;
            break;
        case 'recaptcha-validate':
            $the_response = htmlspecialchars(trim($_POST['the_response']));
            $return['recaptcha_isvalid'] = biz_logic_wp_custom_recaptcha_check_answer($the_response);
            $return['error'] = false;
            break;
        case 'check-custom-theme':
            $the_custom_theme_current = trim($_POST['the_custom_theme_current']);
            $the_custom_theme_path = BIZLOGIC_PLUGIN_DIR_PATH . "css/jquery-ui-themes/" . $the_custom_theme_current;
            if (file_exists($the_custom_theme_path)) {
                $return['custom_theme_file_exists'] = "yes";    
            } else {
                $return['custom_theme_file_exists'] = "no";
            }
            $return['error'] = false;
            break;
        case 'save-config':
            $save_config_update_arr = array(
                BIZLOGIC_UNIQUE_PLUGIN_NAME . "_selected_ui_theme" => stripslashes_deep(sanitize_text_field($_POST['selected_custom_theme'])),
                BIZLOGIC_UNIQUE_PLUGIN_NAME . "_path_to_custom_ui_theme" => trim($_POST['path_to_custom_ui_theme']),
                BIZLOGIC_UNIQUE_PLUGIN_NAME . "_exclude_ui_theme_public" => trim($_POST['exclude_ui_theme_public']),
                BIZLOGIC_UNIQUE_PLUGIN_NAME . "_exclude_ui_theme_admin" => trim($_POST['exclude_ui_theme_admin']),
                BIZLOGIC_UNIQUE_PLUGIN_NAME . "_recaptcha_site_key" => stripslashes_deep(sanitize_text_field($_POST['recaptcha_site_key'])),
                BIZLOGIC_UNIQUE_PLUGIN_NAME . "_recaptcha_secret_key" => stripslashes_deep(sanitize_text_field($_POST['recaptcha_secret_key'])),
                BIZLOGIC_UNIQUE_PLUGIN_NAME . "_recaptcha_language" => stripslashes_deep(sanitize_text_field($_POST['recaptcha_language']))
            );
            $the_results_arr_counti = 0;
            foreach ($save_config_update_arr as $the_key => $the_value) {
                $the_results_arr_counti += 1;
                $the_results_arr[$the_results_arr_counti] = biz_logic_wp_custom_add_update_option($the_key, $the_value);
            }
            $all_ok = true;
            foreach ($the_results_arr as $a_result) {
                if ($a_result == "notok") { $all_ok = false; }    
            }
            if ($all_ok) {
                $return['error'] = false;
            }
            break;
        case 'get-confirm-form-txts-html':
            $hval_form_builder_arr = $_POST['the_form_arr'];
            $the_field_id = $_POST['the_field_id'];
            $confirm_control_txt_field_id = $_POST['confirm_control_txt_field_id'];
            $return['html_str'] = biz_logic_wp_custom_get_confirm_control_form_txts_html($hval_form_builder_arr, $the_field_id, $confirm_control_txt_field_id);
            $return['error'] = false;
            break;
    }
    $response = json_encode($return);
    header("Content-Type: application/json");
    echo $response;
    exit;
}

} // end class

?>