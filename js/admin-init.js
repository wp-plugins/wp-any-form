var the_form_arr = new Array();
var server_error_msg_val = WPAnyFormAdminJSO.server_error_msg_admin;
var required_fields_error_msg_val = WPAnyFormAdminJSO.required_fields_error_msg_admin;
var the_timeout_doneTyping_cells_per_row;
var the_timeout_doneTyping_custom_theme;
var the_current_form_builder_view = 'builder';

jQuery(function ($) {
    /* Functions Start */
    $.init_colour_picker = 
    function init_colour_picker(the_class_or_id_str, run_check_cbx_use_control_defined) {
        $(the_class_or_id_str).spectrum({
            showInput: true,
            showPalette: false,
            showInitial: true,
            cancelText: 'Cancel',
            chooseText: 'Select',
            preferredFormat: 'hex',
            change: function(color) {
                if(run_check_cbx_use_control_defined) {
                    switch(the_class_or_id_str) {
                        case '#txt_clear_link_font_colour':
                            $('#cbx_clear_link_font_colour_use_control_defined').prop('checked', true);
                            break;
                        default:
                            $('#cbx_font_colour_use_control_defined').prop('checked', true);
                            break;
                    }
                }
                //console.log(color.toHexString()); // #ff0000
            }
        });
    }
    $.init_get_help_message =
    function init_get_help_message() {
        $(document).on('click', '.acmd-help', function() {
            var the_cmd_id_str = $(this).attr('id');
            var str_arr_cmd = the_cmd_id_str.split('_');
            var the_cmd_val = str_arr_cmd[1];
            var for_what_val = str_arr_cmd[1];
            $.perform_get_help_message(for_what_val);
        });
    }
    $.perform_get_help_message =
    function perform_get_help_message(for_what_val) {
        $.ajax({ 
            type : 'POST', 
            url : WPAnyFormAdminJSO.ajaxurl,  
            dataType : 'json', 
            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-help-html', for_what: for_what_val }, 
            success : function(data) {
                if (!data.error) {
                    $.show_msg_overlay_help(data.html_str);
                }
            } 
        });    
    }
    /* Functions End */
    $("input.wp_any_form_admin_jslib[type=hidden]").each(function() {
        var jslibval = $(this).val();
   	    switch(jslibval) {
            case 'form-builder-controls':
                $('.a-form-builder-control').draggable( { revert: true } );
                break;
            case 'form-builder-form':
                jQuery(window).load(function() {
                    $('.div-wp-any-form-builder-form-container').css('display', 'block');
                    $.init_form_builder();   
                    $.init_form_builder_img_cmds();
                });
                $(window).bind('resize orientationchange', function() {
                    $.call_apply_style_from_admin_style_controls(); 
                });
                break;
            case 'form-builder-form-saved':
                the_form_arr = the_saved_form_arr;
                $.update_hval_form_builder_arr(the_form_arr);
                $(".div-control-container").draggable( { revert: true } );
                break;
            case 'form-builder-style':
                /* Functions Start */
                $.call_apply_style_from_admin_style_controls = 
                function call_apply_style_from_admin_style_controls() {
                    switch(the_current_form_builder_view) {
                        case 'builder':
                            $.apply_style_form_builder();   
                            break;
                    }
                }
                /* Functions End */
                $.apply_style_flexi_width_generic('#wp_any_form_builder_style', '#div-wp-any-form-builder-style', '.div-any-form-row', '.div-any-form-cell');
                //$.call_apply_style_from_admin_style_controls();
                $('#a-form-builder-refresh').on('click', function() {
                    $.call_apply_style_from_admin_style_controls();
                });
                $.init_colour_picker('#txt_form_default_font_colour', false);
                $.init_colour_picker('#txt_form_default_message_font_colour', false);
                $.init_colour_picker('#txt_form_default_required_field_font_colour', false);
                break;
            case 'form-builder-submit':
                /* Functions Start */
                function cbx_form_submit_send_email_changed() {
                    if ($.get_yes_no_value_from_cbx('#cbx_form_submit_send_email') == 'yes') {
                        $('#div-any-form-row_send_email_options').css('display', 'block');
                    } else {
                        $('#div-any-form-row_send_email_options').css('display', 'none');
                    }    
                }
                /* Functions End */
                $('#cbx_form_submit_send_email').on('change',function() {
                   cbx_form_submit_send_email_changed(); 
                });
                jQuery(window).load(function() {
                   cbx_form_submit_send_email_changed();
                });
                break;
            case 'pop-up-form-options':
                /* Functions Start */
                function cbx_pop_up_form_is_pop_up_changed() {
                    if ($.get_yes_no_value_from_cbx('#cbx_pop_up_form_is_pop_up') == 'yes') {
                        $('#div-pop-up-form-options').css('display', 'block');
                        $.apply_style_flexi_width_generic('#wp_any_form_pop_up_form_options', '#div-wp-any-form-pop-up-options', '.div-any-form-row', '.div-any-form-cell');
                    } else {
                        $('#div-pop-up-form-options').css('display', 'none');
                    }    
                }
                /* Functions End */
                $('#cbx_pop_up_form_is_pop_up').on('change',function() {
                    cbx_pop_up_form_is_pop_up_changed(); 
                });
                jQuery(window).load(function() {
                    cbx_pop_up_form_is_pop_up_changed();  
                    $.init_colour_picker('#txt_pop_up_form_bg_colour', false);
                });
                break;
            case 'data-grids':
                jQuery(window).load(function() {
                    if($('#ddl_the_form_post_ids > option').length == 1) {
                        $('#lblmsg-admin-data-grids').html('No form data found.')                        
                        $('#ddl_the_form_post_ids').fadeOut();
                        $('#btn_get_data_grid').fadeOut();
                        $('#btn_export_csv').fadeOut();
                    }
                });
                $('#div-admin-data-grids-container').on('change', '#cbx_custom_field_keys_select_all', function() {
                    if($(this).prop('checked')) {
                        $('.cbx_custom_field_keys').prop('checked', true);
                    } else {
                        $('.cbx_custom_field_keys').prop('checked', false);
                    }
                });
                $('#ddl_the_form_post_ids').on('change',function() {
                    $('#lblmsg-admin-data-grids').html('');
                    $.clear_active_form_msg_time_out('#lblmsg-admin-data-grids');
                    $('#div-admin-data-grid-container').html('');
                    var the_form_post_id_val = $('#ddl_the_form_post_ids').val();
                    if(the_form_post_id_val != '-1') {
                        $.ajax({ 
                            type : 'POST', 
                            url : WPAnyFormAdminJSO.ajaxurl,  
                            dataType : 'json', 
                            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-data-grids-options-custom-field-keys', the_form_post_id: the_form_post_id_val }, 
                            success : function(data) {
                                if (!data.error) {
                                    $('#div-admin-data-grids-options-custom-field-keys-select-container').html(data.html_str);
                                } else {
                                    $('#lblmsg-admin-data-grids').html(server_error_msg_val);
                                }
                            } 
                        });    
                    }
                });
                $('#btn_get_data_grid').on('click', function() {
                    $('#lblmsg-admin-data-grids').html('');
                    $.clear_active_form_msg_time_out('#lblmsg-admin-data-grids');
                    $('#div-wpaf-table-cell-csv-file').html('');
                    var the_form_post_id_val = $('#ddl_the_form_post_ids').val();
                    if(the_form_post_id_val == '-1') {
                        $.change_form_msg_with_change_to_msg_time_out('#lblmsg-admin-data-grids', WPAnyFormAdminJSO.data_grid_csv_select_form_first_error_msg, '', 5000);
                    } else {
                        var the_selected_custom_field_keys_arr = new Array();
                        $('.cbx_custom_field_keys').each(function() {
                            if($(this).prop('checked')) {
                                the_selected_custom_field_keys_arr.push($(this).val());
                            }
                        });
                        if(the_selected_custom_field_keys_arr.length == 0) {
                            $.change_form_msg_with_change_to_msg_time_out('#lblmsg-admin-data-grids', WPAnyFormAdminJSO.data_grid_csv_field_required_error_msg, '', 5000);
                        } else {
                            $.ajax({ 
                                type : 'POST', 
                                url : WPAnyFormAdminJSO.ajaxurl,  
                                dataType : 'json', 
                                data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-data-grid', the_form_post_id: the_form_post_id_val, the_selected_custom_field_keys_arr: the_selected_custom_field_keys_arr }, 
                                success : function(data) {
                                    if (!data.error) {
                                        $('#div-admin-data-grid-container').html(data.html_str);
                                        $('#tbl-admin-data-grid').footable();
                                    } else {
                                        $('#lblmsg-admin-data-grids').html(server_error_msg_val);
                                    }
                                } 
                            });           
                        }
                    }
                });
                $('#btn_export_csv').on('click', function() {
                    $('#lblmsg-admin-data-grids').html('');
                    $.clear_active_form_msg_time_out('#lblmsg-admin-data-grids');
                    $('#div-wpaf-table-cell-csv-file').html('');
                    var the_form_post_id_val = $('#ddl_the_form_post_ids').val();
                    if(the_form_post_id_val == '-1') {
                        $.change_form_msg_with_change_to_msg_time_out('#lblmsg-admin-data-grids', WPAnyFormAdminJSO.data_grid_csv_select_form_first_error_msg, '', 5000);
                    } else {
                        var the_selected_custom_field_keys_arr = new Array();
                        $('.cbx_custom_field_keys').each(function() {
                            if($(this).prop('checked')) {
                                the_selected_custom_field_keys_arr.push($(this).val());
                            }
                        });
                        if(the_selected_custom_field_keys_arr.length == 0) {
                            $.change_form_msg_with_change_to_msg_time_out('#lblmsg-admin-data-grids', WPAnyFormAdminJSO.data_grid_csv_field_required_error_msg, '', 5000);
                        } else {
                            $.ajax({ 
                                type : 'POST', 
                                url : WPAnyFormAdminJSO.ajaxurl,  
                                dataType : 'json', 
                                data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'export-data-csv', the_form_post_id: the_form_post_id_val, the_selected_custom_field_keys_arr: the_selected_custom_field_keys_arr }, 
                                success : function(data) {
                                    if (!data.error) {
                                        $('#div-wpaf-table-cell-csv-file').html("<a href='" + data.the_csv_file_url + "' >" + WPAnyFormAdminJSO.data_grid_csv_download_file_link_text + "</a>");
                                        $('#lblmsg-admin-data-grids').html(WPAnyFormAdminJSO.data_grid_csv_download_file_msg);
                                    } else {
                                        $.change_form_msg_with_change_to_msg_time_out('#lblmsg-admin-data-grids', data.return_msg, '', 5000);
                                    }
                                } 
                            });           
                        }
                    }
                    return false;
                });
                break;
            case 'form-post-data':
                $('#title').prop('disabled', true);
                $.apply_style_flexi_width_generic('#wp_any_form_saved_data', '#div-wpaf-table-form-data', '.div-wpaf-table-row', '.div-wpaf-table-cell');  
                $(window).bind('resize orientationchange', function() {
                    $.reset_style_flexi_width_generic('#wp_any_form_saved_data', '#div-wpaf-table-form-data', '.div-wpaf-table-row', '.div-wpaf-table-cell');
                    $.apply_style_flexi_width_generic('#wp_any_form_saved_data', '#div-wpaf-table-form-data', '.div-wpaf-table-row', '.div-wpaf-table-cell');  
                });
                break;
            case 'email-template-options':
                $('#ddl_email_templates_options_form_posts').on('change', function() {
                    get_auto_reply_send_to_email_address_form_fields_ddl();
                });
                /*jQuery(window).load(function() {
                    get_auto_reply_send_to_email_address_form_fields_ddl();    
                });*/
                function get_auto_reply_send_to_email_address_form_fields_ddl() {
                    var the_email_template_reply_to_custom_field_form_post_id_val = $('#ddl_email_templates_options_form_posts').val();
                    /*if(the_email_template_reply_to_custom_field_form_post_id_val == '') {
                        $('.div-any-form-cell_ddl-send-to-email-container').html(WPAnyFormAdminJSO.email_templates_send_to_email_form_fields_no_form_selected_error_msg);
                    } else {*/
                        $.ajax({ 
                            type : 'POST', 
                            url : WPAnyFormAdminJSO.ajaxurl,  
                            dataType : 'json', 
                            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-email-templates-form-fields-ddl-to-email-address', the_selected_form_post_id: the_email_template_reply_to_custom_field_form_post_id_val }, 
                            success : function(data) {
                                if (!data.error) {
                                    $('.div-any-form-cell_ddl-send-to-email-container').html(data.html_str);    
                                }
                            } 
                        });
                        
                    //}
                }
                break;
            case 'config':
                $('#div-admin-config-tabs').tabs();
                $('#btn_save_config').on('click',function() {
                    var error_msg_val = '';
                    var the_selected_custom_theme_val = $('#ddl_ui_theme_dirs').val();
                    var the_custom_theme_current_val = '';
                    if(the_selected_custom_theme_val == 'custom') {
                        the_custom_theme_current_val = $('#txt_custom_theme').val();   
                        var custom_theme_path_is_valid_val = $('#hval-custom-theme-path-is-valid').val();
                        if (custom_theme_path_is_valid_val != 'yes') {
                            check_custom_theme_name(the_custom_theme_current_val, true);
                        } else {
                            submit_save_config(the_selected_custom_theme_val, the_custom_theme_current_val);
                        }
                    } else {
                        submit_save_config(the_selected_custom_theme_val, the_custom_theme_current_val);
                    }
                }); 
                function submit_save_config(the_selected_custom_theme_val, the_custom_theme_current_val) {
                    $('.div-msg-overlay').remove();
                    $.clear_active_form_msg_time_out('#div-wp-any-form-config-msg');
                    var exclude_ui_theme_admin_val = $.get_yes_no_value_from_cbx('#cbx_exclude_ui_theme_admin');
                    if(the_custom_theme_current_val == '') {
                        the_custom_theme_current_val = 'smoothness/jquery-ui.min.css';
                    }
                    var recaptcha_site_key_val = $('#txt_recaptcha_site_key').val();
                    var recaptcha_secret_key_val = $('#txt_recaptcha_secret_key').val();
                    var recaptcha_language_val = $('#ddl_recaptcha_language').val();
                    $('#div-wp-any-form-config-msg').html(WPAnyFormAdminJSO.config_contacting_server_msg);
                    $.ajax({ 
                        type : 'POST', 
                        url : WPAnyFormAdminJSO.ajaxurl,  
                        dataType : 'json', 
                        data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'save-config', selected_custom_theme: the_selected_custom_theme_val, path_to_custom_ui_theme: the_custom_theme_current_val, exclude_ui_theme_admin: exclude_ui_theme_admin_val, recaptcha_site_key: recaptcha_site_key_val, recaptcha_secret_key: recaptcha_secret_key_val, recaptcha_language: recaptcha_language_val }, 
                        success : function(data) {
                            if (!data.error) {
                                $.change_form_msg_with_change_to_msg_time_out('#div-wp-any-form-config-msg', WPAnyFormAdminJSO.config_saved_msg, '', 5000);
                            } else {
                                $.change_form_msg_with_change_to_msg_time_out('#div-wp-any-form-config-msg', server_error_msg_val, '', 5000);
                            }
                        } 
                    });
                }
                function check_custom_theme_name(the_custom_theme_current_val, is_save_config_action) {
                    $('#hval-custom-theme-path-is-valid').val('no');
                    $('#div-wp-any-form-config-msg').html('');
                    $.clear_active_form_msg_time_out('#div-wp-any-form-config-msg');
                    if(the_custom_theme_current_val == '') {
                        $.change_form_msg_with_change_to_msg_time_out('#div-wp-any-form-config-msg', WPAnyFormAdminJSO.config_custom_theme_value_error_msg, '', 5000);
                    } else {
                        $.ajax({ 
                            type : 'POST', 
                            url : WPAnyFormAdminJSO.ajaxurl,  
                            dataType : 'json', 
                            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'check-custom-theme', the_custom_theme_current: the_custom_theme_current_val }, 
                            success : function(data) {
                                if (!data.error) {
                                    if(data.custom_theme_file_exists == 'yes') {
                                        $('#hval-custom-theme-path-is-valid').val('yes');
                                        $.change_form_msg_with_change_to_msg_time_out('#div-wp-any-form-config-msg', WPAnyFormAdminJSO.config_custom_theme_file_check_ok_msg, '', 5000);
                                        if(is_save_config_action) {
                                            var the_selected_custom_theme_val = $('#ddl_ui_theme_dirs').val();
                                            submit_save_config(the_selected_custom_theme_val, the_custom_theme_current_val);
                                        }
                                    } else {
                                        $('#hval-custom-theme-path-is-valid').val('no');
                                        $.change_form_msg_with_change_to_msg_time_out('#div-wp-any-form-config-msg', WPAnyFormAdminJSO.config_custom_theme_file_check_error_msg, '', 5000);
                                    }
                                } else {
                                    $.change_form_msg_with_change_to_msg_time_out('#div-wp-any-form-config-msg', server_error_msg_val, '', 5000);
                                }
                            } 
                        });        
                    }
                }
                function ddl_ui_theme_dirs_changed() {
                    var the_selected_custom_theme_val = $('#ddl_ui_theme_dirs').val();
                    switch(the_selected_custom_theme_val) {
                        case 'custom':
                            $('#div-any-form-row_custom_theme').css('display', 'block');
                            break;
                        default:
                            $('#div-any-form-row_custom_theme').css('display', 'none');
                            break;
                    }
                }
                function doneTyping_cells_custom_theme(){
                    if (!the_timeout_doneTyping_custom_theme){
                        return; 
                    }
                    the_timeout_doneTyping_custom_theme = null;
                    var the_custom_theme_current_val = $('#txt_custom_theme').val();
                    if(the_custom_themes_changed && the_custom_theme_current_val != '') {
                        check_custom_theme_name(the_custom_theme_current_val, false);
                    }
                }
                var the_custom_themes_changed = false;
                var the_saved_custom_theme_val = $('#txt_custom_theme').val();
                $('#txt_custom_theme').keyup(function(e) {
                    if (e.which != 9 && e.which != 13) { 
                        //if key pressed is not tab nor enter
                        if($('#txt_custom_theme').val().replace(/[^0-9\.]/g,'') != the_saved_custom_theme_val) {
                            the_custom_themes_changed = true;    
                        }
                    }
                });
                $('#txt_custom_theme').keypress(function() {
                    var el = this;
                    if (the_timeout_doneTyping_custom_theme) clearTimeout(the_timeout_doneTyping_custom_theme);
                    the_timeout_doneTyping_custom_theme = setTimeout(function() {
                        doneTyping_cells_custom_theme.call(el);
                    }, 3500);
                });
                $('#txt_custom_theme').blur(function(){
                    doneTyping_cells_custom_theme.call(this);
                });
                jQuery(window).load(function() {
                    ddl_ui_theme_dirs_changed();
                });
                $('#div-admin-configuration-tabs-container').on('change', '#ddl_ui_theme_dirs', function() {
                    ddl_ui_theme_dirs_changed();
                });
                break;    
            case 'go-pro':
                $('#div-admin-go-pro-tabs').tabs();
                break;
        }
    });    
    $.init_numbers_only('.numbers_only'); 
    $.init_get_help_message();
    $.init_msg_overlay_close_btn();
});