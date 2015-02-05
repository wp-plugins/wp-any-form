var the_form_container_class_id_str = '.div-wp-any-form-builder-form-container';
var the_form_class_id_str = '#div-wp-any-form-builder-form';
var the_row_container_class_id_str = '.div-wp-any-form-builder-form-row';
var the_cell_container_class_id_str = '.div-wp-any-form-builder-form-cell';
var the_cell_width_val;
var temp_ddl_item_sets_arr = 'none';
var temp_cbx_items_arr = 'none';
var temp_rbtn_items_arr = 'none';
var the_recaptcha_controls_arr = new Array();
var the_recaptcha_language_val = '-1';

jQuery(function ($) {
    /* Functions Start */
    $.get_form_builder_html = 
    function get_form_builder_html(the_current_form_builder_view, view_has_changed) {
        if(the_form_arr.length > 0) {
            var the_post_id_val = $.webogetquerystrbyname('post');
            var the_form_vars_arr = false;
            $.ajax({ 
                type : 'POST', 
                url : WPAnyFormAdminJSO.ajaxurl,  
                dataType : 'json', 
                data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-form-builder-html', the_view: the_current_form_builder_view, the_form_arr: the_form_arr, the_post_id: the_post_id_val, the_form_vars_arr: the_form_vars_arr }, 
                success : function(data) {
                    if (!data.error) {
                        switch(the_current_form_builder_view) {
                            case 'builder':
                                $('.div-wp-any-form-builder-form-container').html(data.html_str);
                                $.init_form_builder();
                                $(".div-control-container").draggable( { revert: true } );
                                break;
                        }
                    } else {
                        $('#div-wp-any-form-builder-form-msg').html(server_error_msg_val);
                    }
                } 
            });
        } else {
            $('#div-wp-any-form-builder-form-msg').html('');
            $.clear_active_form_msg_time_out('#div-wp-any-form-builder-form-msg');
            $.change_form_msg_with_change_to_msg_time_out('#div-wp-any-form-builder-form-msg', WPAnyFormAdminJSO.form_builder_error_msg_add_controls_first, '', 5000);
        }
    }
    $.init_form_builder = 
    function init_form_builder() {
        $.init_droppables('.div-wp-any-form-builder-form-cell');
        $.init_control_active_js_behaviours_on_load();
        $.apply_style_form_builder();
    }
    $.init_droppables = 
    function init_droppables(the_class_or_id_str) {
        $(the_class_or_id_str).droppable({
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",
            accept: function(d) { 
                if(d.hasClass("a-form-builder-control")||d.hasClass("div-control-container")){ 
                    return true;
                }
            },
            drop: function( event, ui ) {
                $('#div-wp-any-form-builder-form-msg').html('');
                $.clear_active_form_msg_time_out('#div-wp-any-form-builder-form-msg');
                if($(ui.draggable).hasClass("a-form-builder-control")) {
                    var ok_to_add_control_error_msg_val = '';
                    var the_return_arr = $.dropped_get_row_cell_nos($(this));
                    var the_control_id_str = $(ui.draggable).attr("id");
                    var str_arr_control = the_control_id_str.split('_');
                    var the_control_type_val = str_arr_control[1];
                    switch(the_control_type_val) {
                        case 'btnsubmit': 
                            if ($('#div-wp-any-form-builder-form').find(':submit.div-wp-any-form-btnsubmit').length > 0) { 
                                ok_to_add_control_error_msg_val = WPAnyFormAdminJSO.form_builder_error_msg_submit_btn_limit;
                            }
                            break;
                        case 'btnreset': 
                            if ($('#div-wp-any-form-builder-form').find('.div-wp-any-form-btnreset').length > 0) { 
                                ok_to_add_control_error_msg_val = WPAnyFormAdminJSO.form_builder_error_msg_reset_btn_limit;
                            }
                            break;
                        case 'recaptcha': 
                            if ($('#div-wp-any-form-builder-form').find('.div-recaptcha-container').length > 0) { 
                                ok_to_add_control_error_msg_val = WPAnyFormAdminJSO.form_builder_error_msg_recaptcha_btn_limit;
                            }
                            break;
                    }
                    if(ok_to_add_control_error_msg_val != '') {
                        $.change_form_msg_with_change_to_msg_time_out('#div-wp-any-form-builder-form-msg', ok_to_add_control_error_msg_val, '', 5000);
                    } else {
                        $.get_control_options('add', the_control_type_val, the_return_arr.the_row_no_val, the_return_arr.the_cell_no_val);    
                    }
                }
                if($(ui.draggable).hasClass("div-control-container")) {
                    var the_return_arr = $.dropped_get_row_cell_nos($(this));
                    var the_control_container_id_str = $(ui.draggable).attr("id");
                    var str_arr_control_container = the_control_container_id_str.split('_');
                    var the_current_row_no_val = str_arr_control_container[1];
                    var the_current_cell_no_val = str_arr_control_container[2];
                    if ($("#div-wp-any-form-builder-form-cell_" + the_return_arr.the_row_no_val + "_" + the_return_arr.the_cell_no_val).find(".div-control-container").length > 0) { 
                        if(the_return_arr.the_row_no_val == the_current_row_no_val && the_return_arr.the_cell_no_val == the_current_cell_no_val) {
                            return false;
                        } else {
                            $.change_form_msg_with_change_to_msg_time_out('#div-wp-any-form-builder-form-msg', WPAnyFormAdminJSO.form_builder_error_msg_can_only_move_empty_cell, '', 5000);
                        }                                    
                    } else {
                        var the_control_fields_arr = $.get_control_fields_arr_from_form_arr(the_form_arr, the_current_row_no_val, the_current_cell_no_val);
                        $.delete_control_form_cell(the_current_row_no_val, the_current_cell_no_val);
                        $.remove_control_form_arr(the_current_row_no_val, the_current_cell_no_val);
                        the_control_fields_arr.the_row_no_val = the_return_arr.the_row_no_val;
                        the_control_fields_arr.the_cell_no_val = the_return_arr.the_cell_no_val;
                        the_form_arr.push(the_control_fields_arr);
                        $.update_hval_form_builder_arr(the_form_arr);
                        $.get_control_html(the_control_fields_arr);
                        if(the_current_row_no_val == "1" && the_current_cell_no_val == "1") {
                            $("#div-wp-any-form-builder-form-cell_1_1").find(".div-form-builder-img-cmd-container-row.del").remove();
                        }
                    }
                }
            }
        });    
    }
    $.dropped_get_row_cell_nos = 
    function dropped_get_row_cell_nos(the_cell_obj) {
        var the_return_arr = {};
        var the_cell_id_str = the_cell_obj.attr('id');
        var str_arr_cell = the_cell_id_str.split('_');
        the_return_arr.the_cell_no_val = str_arr_cell[2];
        var the_parent_row = the_cell_obj.parent().closest('.div-wp-any-form-builder-form-row');
        var the_row_id_str = the_parent_row.attr('id');
        var str_arr_row = the_row_id_str.split('_');
        the_return_arr.the_row_no_val = str_arr_row[1];
        return the_return_arr;
    }
    $.get_control_options = 
    function get_control_options(the_action_cmd, the_control_type_val, the_row_no_val, the_cell_no_val) {
        var the_window_width = +$(window).width() - 25;
        var the_control_options_form_msg_init_val = '';
        var remove_current_arr_item = false;
        var the_add_or_save_cmd_txt = '';
        var the_control_fields_arr = '';
        switch(the_action_cmd) {
            case 'add':
                the_add_or_save_cmd_txt = WPAnyFormAdminJSO.form_builder_control_options_dialog_btn_txt_add;
                if ($("#div-wp-any-form-builder-form-cell_" + the_row_no_val + "_" + the_cell_no_val).find(".div-control-container").length > 0) { 
                    the_control_options_form_msg_init_val = WPAnyFormAdminJSO.form_builder_msg_control_replaced;
                    remove_current_arr_item = true;
                }
                break;
            case 'edit':
                the_add_or_save_cmd_txt = WPAnyFormAdminJSO.form_builder_control_options_dialog_btn_txt_update;
                the_control_fields_arr = $.get_control_fields_arr_from_form_arr(the_form_arr, the_row_no_val, the_cell_no_val);
                the_control_type_val = the_control_fields_arr.the_control_type;
                break;
        }
        $.ajax({ 
            type : 'POST', 
            url : WPAnyFormAdminJSO.ajaxurl,  
            dataType : 'json', 
            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-control-options-html', the_control_type: the_control_type_val, the_action_cmd: the_action_cmd, the_control_fields_arr: the_control_fields_arr }, 
            success : function(data) {
                if (!data.error) {
                    $('#div-dialog-content-form-builder').html(data.html_str);
                    var the_dialog_height_val = data.the_dialog_height;
                    var the_dialog_width_val = $.dialog_width_check_adjust_responsive(600);
                    var the_dialog_buttons_obj = [{ 
                        text: the_add_or_save_cmd_txt,
                        click: function () {
                            $('.div-msg-overlay').remove(); 
                            var error_msg_val = '';
                            switch(the_control_type_val) {
                                case 'lbl':
                                    var lbl_txt_val = $.webo_sort_user_input_string($('#txt_lbl_text').val());
                                    if($.check_if_str_value_empty(lbl_txt_val)) {
                                        error_msg_val = required_fields_error_msg_val;
                                    }        
                                    break;
                                case 'txt':
                                    var the_field_name_val = $.webo_remove_whitespace_trim($.webo_html_remove_special_chars($('#txt_field_name').val()));
                                    if($.check_if_str_value_empty(the_field_name_val)) {
                                        error_msg_val = required_fields_error_msg_val;
                                    }  
                                    var is_confirm_val;
                                    var confirm_control_txt_field_id_val = '-1';
                                    if(error_msg_val == '') {
                                        is_confirm_val = $.get_yes_no_value_from_cbx('#cbx_txt_is_confirm');
                                        if(is_confirm_val == 'yes') {
                                            confirm_control_txt_field_id_val = $('#ddl_confirm_control_form_txts').val();
                                            if(confirm_control_txt_field_id_val == '-1') {
                                                error_msg_val = required_fields_error_msg_val;
                                            }
                                        }
                                    }      
                                    break;
                                case 'txta':
                                    var the_field_name_val = $.webo_remove_whitespace_trim($.webo_html_remove_special_chars($('#txt_field_name').val()));
                                    if($.check_if_str_value_empty(the_field_name_val)) {
                                        error_msg_val = required_fields_error_msg_val;
                                    }        
                                    break;
                                case 'ddl':
                                    var the_field_name_val = $.webo_remove_whitespace_trim($.webo_html_remove_special_chars($('#txt_field_name').val()));
                                    if($.check_if_str_value_empty(the_field_name_val)) {
                                        error_msg_val = required_fields_error_msg_val;
                                    }        
                                    break;
                                case 'cbx':
                                    var the_field_name_val = $.webo_remove_whitespace_trim($.webo_html_remove_special_chars($('#txt_field_name').val()));
                                    if($.check_if_str_value_empty(the_field_name_val)) {
                                        error_msg_val = required_fields_error_msg_val;
                                    }        
                                    break;
                                case 'rbtn':
                                    var the_field_name_val = $.webo_remove_whitespace_trim($.webo_html_remove_special_chars($('#txt_field_name').val()));
                                    if($.check_if_str_value_empty(the_field_name_val)) {
                                        error_msg_val = required_fields_error_msg_val;
                                    }        
                                    break;    
                                case 'recaptcha':
                                    var validating_recaptcha_msg_val = $.webo_sort_user_input_string($('#txta_validating_recaptcha_msg').val());
                                    var recaptcha_validation_failed_msg_val = $.webo_sort_user_input_string($('#txta_recaptcha_validation_failed_msg').val());
                                    if($.check_if_str_value_empty(validating_recaptcha_msg_val) || $.check_if_str_value_empty(recaptcha_validation_failed_msg_val)) {
                                        error_msg_val = required_fields_error_msg_val;
                                    }     
                                    break;    
                                case 'btnsubmit':
                                    var btnsubmit_txt_val = $.webo_sort_user_input_string($('#txt_btnsubmit_text').val());
                                    if($.check_if_str_value_empty(btnsubmit_txt_val)) {
                                        error_msg_val = required_fields_error_msg_val;
                                    }        
                                    break;
                                case 'btnreset':
                                    var btnreset_txt_val = $.webo_sort_user_input_string($('#txt_btnreset_text').val());
                                    if($.check_if_str_value_empty(btnreset_txt_val)) {
                                        error_msg_val = required_fields_error_msg_val;
                                    }        
                                    break;
                            }
                            if(error_msg_val != '') {
                                $('#div-control-options-form-msg').html(error_msg_val);
                            } else {
                                var the_control_fields_arr = {};
                                the_control_fields_arr.the_control_type = the_control_type_val;
                                switch(the_control_type_val) {
                                    case 'lbl':
                                        the_control_fields_arr.the_initial_display = 'visible';
                                        the_control_fields_arr.the_text = lbl_txt_val;
                                        the_control_fields_arr.the_field_id = $('#hval-field-id').val();
                                        the_control_fields_arr.the_font_size = $('#txt_lbl_font_size').val();
                                        the_control_fields_arr.the_font_weight = $('#ddl_form_lbl_font_weight').val();
                                        the_control_fields_arr.the_font_colour = $('#txt_lbl_font_colour').val();
                                        the_control_fields_arr.the_font_colour_use_control_defined = $.get_yes_no_value_from_cbx('#cbx_font_colour_use_control_defined');
                                        the_control_fields_arr.the_custom_css_class = $.webo_remove_whitespace_trim($('#txt_custom_css_class').val());
                                        break;
                                    case 'txt':
                                        the_control_fields_arr.the_initial_display = 'visible';
                                        the_control_fields_arr.is_wp_username = 'no';
                                        the_control_fields_arr.is_wp_email = 'no';
                                        the_control_fields_arr.is_wp_password = 'no';
                                        the_control_fields_arr.the_field_name = the_field_name_val;
                                        the_control_fields_arr.the_field_id = $('#hval-field-id').val();
                                        the_control_fields_arr.the_value = $.webo_sort_user_input_string($('#txt_value').val());
                                        the_control_fields_arr.the_placeholder = $.webo_sort_user_input_string($('#txt_placeholder').val());
                                        the_control_fields_arr.the_max_length = $('#txt_max_length').val();
                                        the_control_fields_arr.is_required = $.get_yes_no_value_from_cbx('#cbx_txt_is_required');
                                        the_control_fields_arr.is_numeric = $.get_yes_no_value_from_cbx('#cbx_txt_is_numeric');
                                        the_control_fields_arr.is_password = $.get_yes_no_value_from_cbx('#cbx_txt_is_password');
                                        the_control_fields_arr.is_email = $.get_yes_no_value_from_cbx('#cbx_txt_is_email');
                                        the_control_fields_arr.is_confirm = is_confirm_val;
                                        the_control_fields_arr.confirm_control_txt_field_id = confirm_control_txt_field_id_val;
                                        the_control_fields_arr.the_font_size = $('#txt_font_size').val();
                                        the_control_fields_arr.the_font_weight = $('#ddl_form_txt_font_weight').val();
                                        the_control_fields_arr.the_font_colour = $('#txt_font_colour').val();
                                        the_control_fields_arr.the_font_colour_use_control_defined = $.get_yes_no_value_from_cbx('#cbx_font_colour_use_control_defined');
                                        the_control_fields_arr.the_width = $('#txt_width').val();
                                        the_control_fields_arr.the_height = $('#txt_height').val();
                                        the_control_fields_arr.the_custom_css_class = $.webo_remove_whitespace_trim($('#txt_custom_css_class').val());
                                        break;
                                    case 'txta':
                                        the_control_fields_arr.the_initial_display = 'visible';
                                        the_control_fields_arr.the_field_name = the_field_name_val;
                                        the_control_fields_arr.the_field_id = $('#hval-field-id').val();
                                        the_control_fields_arr.the_value = $.webo_sort_user_input_string($('#txta_value').val());
                                        the_control_fields_arr.the_placeholder = $.webo_sort_user_input_string($('#txt_placeholder').val());
                                        the_control_fields_arr.the_max_length = $('#txt_max_length').val();
                                        the_control_fields_arr.is_required = $.get_yes_no_value_from_cbx('#cbx_control_is_required');
                                        the_control_fields_arr.the_font_size = $('#txt_font_size').val();
                                        the_control_fields_arr.the_font_weight = $('#ddl_form_control_font_weight').val();
                                        the_control_fields_arr.the_font_colour = $('#txt_font_colour').val();
                                        the_control_fields_arr.the_font_colour_use_control_defined = $.get_yes_no_value_from_cbx('#cbx_font_colour_use_control_defined');
                                        the_control_fields_arr.the_width = $('#txt_width').val();
                                        the_control_fields_arr.the_height = $('#txt_height').val();
                                        the_control_fields_arr.the_custom_css_class = $.webo_remove_whitespace_trim($('#txt_custom_css_class').val());
                                        break;    
                                    case 'ddl':
                                        the_control_fields_arr.the_initial_display = 'visible';
                                        the_control_fields_arr.ddl_item_sets_arr = temp_ddl_item_sets_arr;
                                        temp_ddl_item_sets_arr = 'none';
                                        the_control_fields_arr.the_field_name = the_field_name_val;
                                        the_control_fields_arr.the_field_id = $('#hval-field-id').val();
                                        the_control_fields_arr.the_selected_value = $('#ddl_initial_selected_value').val();
                                        the_control_fields_arr.the_current_item_set_i = '-1';
                                        the_control_fields_arr.the_next_ddl_id_val = '-1';
                                        the_control_fields_arr.is_top_ddl_val = false;
                                        the_control_fields_arr.is_required = $.get_yes_no_value_from_cbx('#cbx_control_is_required');
                                        the_control_fields_arr.the_font_size = $('#txt_font_size').val();
                                        the_control_fields_arr.the_font_weight = $('#ddl_form_control_font_weight').val();
                                        the_control_fields_arr.the_font_colour = $('#txt_font_colour').val();
                                        the_control_fields_arr.the_font_colour_use_control_defined = $.get_yes_no_value_from_cbx('#cbx_font_colour_use_control_defined');
                                        the_control_fields_arr.the_width = $('#txt_width').val();
                                        the_control_fields_arr.the_custom_css_class = $.webo_remove_whitespace_trim($('#txt_custom_css_class').val());
                                        break;  
                                    case 'cbx':
                                        the_control_fields_arr.the_initial_display = 'visible';
                                        the_control_fields_arr.items_arr = temp_cbx_items_arr;
                                        temp_cbx_items_arr = 'none';
                                        the_control_fields_arr.the_field_name = the_field_name_val;
                                        the_control_fields_arr.the_field_id = $('#hval-field-id').val();
                                        the_control_fields_arr.saved_data_values_separator = $('#ddl_saved_data_values_separator').val();
                                        the_control_fields_arr.is_required = $.get_yes_no_value_from_cbx('#cbx_control_is_required');
                                        the_control_fields_arr.the_font_size = $('#txt_font_size').val();
                                        the_control_fields_arr.the_font_weight = $('#ddl_form_control_font_weight').val();
                                        the_control_fields_arr.the_font_colour = $('#txt_font_colour').val();
                                        the_control_fields_arr.the_font_colour_use_control_defined = $.get_yes_no_value_from_cbx('#cbx_font_colour_use_control_defined');
                                        the_control_fields_arr.use_flexi_width = $.get_yes_no_value_from_cbx('#cbx_use_flexi_width');
                                        the_control_fields_arr.cbxs_per_row = $('#txt_cbxs_per_row').val();
                                        the_control_fields_arr.the_custom_css_class = $.webo_remove_whitespace_trim($('#txt_custom_css_class').val());
                                        break;  
                                    case 'rbtn':
                                        the_control_fields_arr.the_initial_display = 'visible';
                                        the_control_fields_arr.items_arr = temp_rbtn_items_arr;
                                        temp_rbtn_items_arr = 'none';
                                        the_control_fields_arr.the_field_name = the_field_name_val;
                                        the_control_fields_arr.the_field_id = $('#hval-field-id').val();
                                        the_control_fields_arr.is_required = $.get_yes_no_value_from_cbx('#cbx_control_is_required');
                                        the_control_fields_arr.the_font_size = $('#txt_font_size').val();
                                        the_control_fields_arr.the_font_weight = $('#ddl_form_control_font_weight').val();
                                        the_control_fields_arr.the_font_colour = $('#txt_font_colour').val();
                                        the_control_fields_arr.the_font_colour_use_control_defined = $.get_yes_no_value_from_cbx('#cbx_font_colour_use_control_defined');
                                        the_control_fields_arr.use_flexi_width = $.get_yes_no_value_from_cbx('#cbx_use_flexi_width');
                                        the_control_fields_arr.rbtns_per_row = $('#txt_rbtns_per_row').val();
                                        the_control_fields_arr.the_custom_css_class = $.webo_remove_whitespace_trim($('#txt_custom_css_class').val());
                                        break;  
                                    case 'recaptcha':
                                        the_control_fields_arr.the_field_id = $('#hval-field-id').val();
                                        the_control_fields_arr.validating_recaptcha_msg = validating_recaptcha_msg_val;
                                        the_control_fields_arr.recaptcha_validation_failed_msg = recaptcha_validation_failed_msg_val;
                                        the_control_fields_arr.the_theme = $('#ddl_theme').val();
                                        break;
                                    case 'btnsubmit':
                                        the_control_fields_arr.the_initial_display = 'visible';
                                        the_control_fields_arr.the_btn_id = $('#hval-btn-id').val();
                                        the_control_fields_arr.the_text = btnsubmit_txt_val;
                                        the_control_fields_arr.the_font_size = $('#txt_btnsubmit_font_size').val();
                                        the_control_fields_arr.the_font_weight = $('#ddl_form_btnsubmit_font_weight').val();
                                        the_control_fields_arr.the_font_colour = $('#txt_btnsubmit_font_colour').val();
                                        the_control_fields_arr.the_font_colour_use_control_defined = $.get_yes_no_value_from_cbx('#cbx_font_colour_use_control_defined');
                                        the_control_fields_arr.the_control_align = $('#ddl_form_control_align').val();
                                        the_control_fields_arr.the_custom_css_class = $.webo_remove_whitespace_trim($('#txt_custom_css_class').val());
                                        break;
                                    case 'btnreset':
                                        the_control_fields_arr.the_initial_display = 'visible';
                                        the_control_fields_arr.the_btn_id = $('#hval-btn-id').val();
                                        the_control_fields_arr.the_text = btnreset_txt_val;
                                        the_control_fields_arr.the_font_size = $('#txt_btnreset_font_size').val();
                                        the_control_fields_arr.the_font_weight = $('#ddl_form_btnreset_font_weight').val();
                                        the_control_fields_arr.the_font_colour = $('#txt_btnreset_font_colour').val();
                                        the_control_fields_arr.the_font_colour_use_control_defined = $.get_yes_no_value_from_cbx('#cbx_font_colour_use_control_defined');
                                        the_control_fields_arr.the_control_align = $('#ddl_form_control_align').val();
                                        the_control_fields_arr.the_custom_css_class = $.webo_remove_whitespace_trim($('#txt_custom_css_class').val());
                                        break;
                                    case 'emptycell':
                                        break;    
                                }    
                                var error_msg_add_control_val = '';
                                if($.check_for_duplicate_field_name(the_control_fields_arr)) {
                                    error_msg_add_control_val = WPAnyFormAdminJSO.form_builder_error_msg_field_name_unique;                                    
                                }
                                if(error_msg_add_control_val != '') {
                                    $('#div-control-options-form-msg').html(error_msg_add_control_val);    
                                } else {
                                    the_control_fields_arr.the_row_no_val = the_row_no_val;
                                    the_control_fields_arr.the_cell_no_val = the_cell_no_val;                           
                                    if(the_action_cmd == 'edit' || remove_current_arr_item) {
                                        $.remove_control_form_arr(the_row_no_val, the_cell_no_val);
                                    }
                                    the_form_arr.push(the_control_fields_arr);
                                    $.get_control_html(the_control_fields_arr);
                                    $.update_hval_form_builder_arr(the_form_arr);
                                    $(this).dialog('close');
                                    $('.div-control-options-container').remove();
                                } 
                            }
                        }
                    },
                    {
                        text: WPAnyFormAdminJSO.form_builder_control_options_dialog_btn_txt_cancel,
                        click : function () { 
                            $(this).dialog('close');
                            close_cancel_control_options_dialog_action();
                        }    
                    }];
                    if(the_action_cmd == 'edit' && the_control_type_val == 'emptycell') {
                        the_dialog_buttons_obj = [{ 
                            text: 'Ok',
                            click: function () { 
                                $(this).dialog('close');
                                $('.div-control-options-container').remove();
                            }
                        }];
                    }
                    $('#div-control-options-form-msg').html(the_control_options_form_msg_init_val);
                    $('#div-dialog-form-builder').dialog({ title: WPAnyFormAdminJSO.form_builder_control_options_dialog_title, height: the_dialog_height_val, width: the_dialog_width_val, modal: true,
                        buttons: the_dialog_buttons_obj, 
                        close: function(event) {
                            if (event.originalEvent) {
                                close_cancel_control_options_dialog_action();                           
                            }
                        }
                    });
                    switch(the_control_type_val) {
                        case 'lbl':
                            $.init_colour_picker('#txt_lbl_font_colour', true);
                            $('#div-control-options-tabs').tabs();
                            $.init_numbers_only('.numbers_only');
                            $.init_css_class_txt('.txt_custom_css_class');
                            break;
                        case 'txt':
                            $.init_validation_cbxs();
                            $.init_colour_picker('#txt_font_colour', true);
                            $('#div-control-options-tabs').tabs();
                            $.init_numbers_only('.numbers_only');
                            $.init_no_special_chars('#txt_field_name');
                            $.init_css_class_txt('.txt_custom_css_class');
                            $.init_cbx_txt_is_confirm_changed();
                            $.cbx_txt_is_confirm_changed();
                            break;
                        case 'txta':
                            $.init_colour_picker('#txt_font_colour', true);
                            $('#div-control-options-tabs').tabs();
                            $.init_numbers_only('.numbers_only');
                            $.init_no_special_chars('#txt_field_name');
                            $.init_css_class_txt('.txt_custom_css_class');
                            break;
                        case 'ddl':
                            $.init_colour_picker('#txt_font_colour', true);
                            $.init_ddl_options_item_set_form(data.ddl_item_sets_arr);
                            $('#div-control-options-tabs').tabs();
                            $.init_numbers_only('.numbers_only');
                            $.init_no_special_chars('#txt_field_name');
                            $.init_css_class_txt('.txt_custom_css_class');
                            break;
                        case 'cbx':
                            $.init_colour_picker('#txt_font_colour', true);
                            $.init_cbx_options_item_set_form(data.items_arr);
                            $('#div-control-options-tabs').tabs();
                            $.init_numbers_only('.numbers_only');
                            $.init_no_special_chars('#txt_field_name');
                            $.init_css_class_txt('.txt_custom_css_class');
                            break;
                        case 'rbtn':
                            $.init_colour_picker('#txt_font_colour', true);
                            $.init_rbtn_options_item_set_form(data.items_arr);
                            $('#div-control-options-tabs').tabs();
                            $.init_numbers_only('.numbers_only');
                            $.init_no_special_chars('#txt_field_name');
                            $.init_css_class_txt('.txt_custom_css_class');
                            break;
                        case 'recaptcha':
                            $('#div-control-options-tabs').tabs();
                            break;
                        case 'btnsubmit':
                            $.init_colour_picker('#txt_btnsubmit_font_colour', true);
                            $('#div-control-options-tabs').tabs();
                            $.init_numbers_only('.numbers_only');
                            $.init_css_class_txt('.txt_custom_css_class');
                            break;    
                        case 'btnreset':
                            $.init_colour_picker('#txt_btnreset_font_colour', true);
                            $('#div-control-options-tabs').tabs();
                            $.init_numbers_only('.numbers_only');
                            $.init_css_class_txt('.txt_custom_css_class');
                            break;    
                    }
                } else {
                    $.clear_active_form_msg_time_out('#div-wp-any-form-builder-form-msg');
                    $('#div-wp-any-form-builder-form-msg').html(server_error_msg_val);
                }
            } 
        });
    }
    function close_cancel_control_options_dialog_action() {
        $('.div-control-options-container').remove();
        $('.div-msg-overlay').remove();
    }
    $.check_for_duplicate_field_name = 
    function check_for_duplicate_field_name(the_control_fields_arr_to_check) {
        var the_control_type_to_check_val = the_control_fields_arr_to_check.the_control_type;
        switch(the_control_type_to_check_val) {
            case 'lbl': case 'recaptcha': case 'btnsubmit': case 'btnreset': case 'emptycell':
                break;
            default:
                for (var i = 0; i < the_form_arr.length; i++) {
                    var the_control_fields_arr = the_form_arr[i];
                    var the_control_type_val = the_control_fields_arr.the_control_type;
                    switch(the_control_type_val) {
                        case 'lbl': case 'recaptcha': case 'btnsubmit': case 'btnreset': case 'emptycell':
                            break;
                        default:
                            var the_field_name_to_check_val = the_control_fields_arr_to_check.the_field_name;
                            var the_field_id_to_check_val = the_control_fields_arr_to_check.the_field_id;
                            var the_field_name_val = the_control_fields_arr.the_field_name;
                            var the_field_id_val = the_control_fields_arr.the_field_id;
                            if(the_field_id_to_check_val != the_field_id_val) {
                                if(the_field_name_to_check_val.toLowerCase() == the_field_name_val.toLowerCase()) {
                                    return true;
                                }
                            }
                            break;
                    }
                };        
                break;
        }
        return false;
    }
    $.get_control_html = 
    function get_control_html(the_control_fields_arr) {
        var the_row_no_val = the_control_fields_arr.the_row_no_val;
        var the_cell_no_val = the_control_fields_arr.the_cell_no_val;
        var the_control_type_val = the_control_fields_arr.the_control_type;
        $.ajax({ 
            type : 'POST', 
            url : WPAnyFormAdminJSO.ajaxurl,  
            dataType : 'json', 
            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-control-html', the_control_fields_arr: the_control_fields_arr }, 
            success : function(data) {
                if (!data.error) {
                    $('#div-wp-any-form-builder-form-row_' + the_control_fields_arr.the_row_no_val).find('#div-wp-any-form-builder-form-cell_' + the_control_fields_arr.the_row_no_val + '_' + the_control_fields_arr.the_cell_no_val).html(data.html_str);
                    $("#div-control-container_" + the_row_no_val + "_" + the_cell_no_val).draggable( { revert: true } );
                    $.init_control_active_js_behaviours(the_control_type_val, the_control_fields_arr);
                    switch(the_control_type_val) {
                        case 'recaptcha':
                            break;
                        default:
                            $.apply_style_form_builder();
                            break;
                    }
                } else {
                    $.clear_active_form_msg_time_out('#div-wp-any-form-builder-form-msg');
                    $('#div-wp-any-form-builder-form-msg').html(server_error_msg_val);
                }
            } 
        });
    }
    $.init_control_active_js_behaviours = 
    function init_control_active_js_behaviours(the_control_type_val, the_control_fields_arr) {
        switch(the_control_type_val) {
            case 'lbl':
                break;
            case 'txt':
                $.init_numbers_only('.is_numeric'); 
                break;
            case 'txta':
                break;
            case 'ddl':
                break;
            case 'cbx':
                break;    
            case 'rbtn':
                break;   
            case 'recaptcha':
                var the_field_id_val = the_control_fields_arr.the_field_id;
                var the_form_recaptcha_control_arr = {};
                the_form_recaptcha_control_arr.the_field_id = the_field_id_val;
                the_form_recaptcha_control_arr.the_widget_id = 'init';
                the_form_recaptcha_control_arr.the_theme = the_control_fields_arr.the_theme;
                the_recaptcha_controls_arr = new Array();
                the_recaptcha_controls_arr.push(the_form_recaptcha_control_arr);
                $.load_recaptcha_js_lib_callback(true);
                break; 
            case 'btnsubmit':
                $(the_form_class_id_str).find('.div-wp-any-form-btnsubmit').on('click',function() {
                    return false;
                });
                //$.init_numbers_only('.numbers_only'); 
                break;
            case 'btnreset':
                $(the_form_class_id_str).find('.div-wp-any-form-btnreset').on('click',function() {
                    return false;
                });
                //$.init_numbers_only('.numbers_only'); 
                break;
        }
    }
    $.init_control_active_js_behaviours_on_load = 
    function init_control_active_js_behaviours_on_load() {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type;
            $.init_control_active_js_behaviours(the_control_type_val, the_control_fields_arr);
        };
    }
    $.add_new_droppable = 
    function add_new_droppable(add_what, the_row_no_val) {
        switch(add_what) {
            case 'row':
                var the_new_row_no_val = +the_row_no_val + 1;
                $("#div-wp-any-form-builder-form-rows-container").children(".div-wp-any-form-builder-form-row").last().after("<div id='div-wp-any-form-builder-form-row_" + the_new_row_no_val + "' class='div-wp-any-form-builder-form-row' ><div id='div-wp-any-form-builder-form-cell_" + the_new_row_no_val + "_1' class='div-wp-any-form-builder-form-cell' >" + $.get_form_builder_img_cmd_html_js("delcell", the_new_row_no_val, "1") + "</div>" + $.get_form_builder_img_cmd_html_js("addcell", the_new_row_no_val, "1") + "<input id='hval-form-cell-count-row_" + the_new_row_no_val + "' type='hidden' value='1' /></div>");
                $('#hval-form-row-count').val(the_new_row_no_val);
                $.init_droppables("#div-wp-any-form-builder-form-cell_" + the_new_row_no_val + "_1");
                break;
            case 'cell':
                var the_cell_no_val = $('#hval-form-cell-count-row_' + the_row_no_val).val();
                var the_new_cell_no_val = +the_cell_no_val + 1;
                $("#div-wp-any-form-builder-form-row_" + the_row_no_val).children(".div-wp-any-form-builder-form-cell").last().after("<div id='div-wp-any-form-builder-form-cell_" + the_row_no_val + "_" + the_new_cell_no_val + "' class='div-wp-any-form-builder-form-cell' >" + $.get_form_builder_img_cmd_html_js("delcell", the_row_no_val, the_new_cell_no_val) + "</div>");
                $('#hval-form-cell-count-row_' + the_row_no_val).val(the_new_cell_no_val);
                $.init_droppables("#div-wp-any-form-builder-form-cell_" + the_row_no_val + "_" + the_new_cell_no_val);
                break;
        }
        $.apply_style_form_builder();
    }
    $.delete_form_cell =
    function delete_form_cell(the_row_no_val, the_cell_no_val) {
        $("#div-wp-any-form-builder-form-cell_" + the_row_no_val + "_" + the_cell_no_val).remove();
        if($("#div-wp-any-form-builder-form-row_" + the_row_no_val).find(".div-wp-any-form-builder-form-cell").length == 0) {
            $("#div-wp-any-form-builder-form-row_" + the_row_no_val).remove();
        }
    }
    $.delete_control_form_cell =
    function delete_control_form_cell(the_row_no_val, the_cell_no_val) {
        $("#div-wp-any-form-builder-form-cell_" + the_row_no_val + "_" + the_cell_no_val).find(".div-control-container").remove();
        $("#div-wp-any-form-builder-form-cell_" + the_row_no_val + "_" + the_cell_no_val).find(".div-form-builder-img-cmd-container-row.options").remove();
        $.remove_control_form_arr(the_row_no_val, the_cell_no_val);
        $.update_hval_form_builder_arr(the_form_arr);
    }
    $.remove_control_form_arr =
    function remove_control_form_arr(the_row_no_val, the_cell_no_val) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            if(the_control_fields_arr.the_row_no_val == the_row_no_val && the_control_fields_arr.the_cell_no_val == the_cell_no_val) {
                the_form_arr.splice(i, 1);
            }
        };
    }
    $.update_hval_form_builder_arr =
    function update_hval_form_builder_arr(the_form_arr) {
        $('#hval_form_builder_arr').val(JSON.stringify(the_form_arr));
    }
    $.init_validation_cbxs =
    function init_validation_cbxs() {
        $('#div-dialog-content-form-builder').on("click", ".cbx_txt_checked_exclusive", function() { 
            var the_cbx_id_str = $(this).attr('id');
            switch(the_cbx_id_str) {
                case 'cbx_txt_is_numeric':
                    if($('#cbx_txt_is_numeric').prop('checked')) {
                        $('#cbx_txt_is_email').prop('checked', false);
                    }
                    break;
                case 'cbx_txt_is_email':
                    if($('#cbx_txt_is_email').prop('checked')) {
                        $('#cbx_txt_is_numeric').prop('checked', false);
                    }
                    break;
            }
        });
    }
    $.init_cbx_txt_is_confirm_changed =
    function init_cbx_txt_is_confirm_changed() {
        $('#div-dialog-content-form-builder').on("click", "#cbx_txt_is_confirm", function() { 
            $.cbx_txt_is_confirm_changed();
        });
    }
    $.cbx_txt_is_confirm_changed =
    function cbx_txt_is_confirm_changed() {
        if ($.get_yes_no_value_from_cbx('#cbx_txt_is_confirm') == 'yes') {
            $('#div-any-form-row_txt-confirm-select-control').fadeIn();
            var the_field_id_val = $('#hval-field-id').val();
            var confirm_control_txt_field_id_val = $('#hval-confirm-control-txt-field-id').val();
            $.ajax({ 
                type : 'POST', 
                url : WPAnyFormAdminJSO.ajaxurl,  
                dataType : 'json', 
                data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-confirm-form-txts-html', the_form_arr: the_form_arr, the_field_id: the_field_id_val, confirm_control_txt_field_id: confirm_control_txt_field_id_val }, 
                success : function(data) {
                    if (!data.error) {
                        $('#div-any-form-cell_txt-confirm-select-control-ddl-container').html(data.html_str);
                    } else {
                        $.clear_active_form_msg_time_out('#div-wp-any-form-builder-form-msg');
                        $('#div-wp-any-form-builder-form-msg').html(server_error_msg_val);
                    }
                } 
            });
        } else {
            $('#div-any-form-row_txt-confirm-select-control').fadeOut();
            $('#div-any-form-cell_txt-confirm-select-control-ddl-container').html('');
        }
    }
    $.init_form_builder_img_cmds =
    function init_form_builder_img_cmds() {
        $('.div-wp-any-form-builder-form-container').on("click", ".a-form-builder-img-cmd", function() {
            var the_cmd_id_str = $(this).attr('id');
            var str_arr_cmd = the_cmd_id_str.split('_');
            var the_cmd_val = str_arr_cmd[1];
            switch(the_cmd_val) {
                case 'addcell':
                    var the_row_no_val = str_arr_cmd[2];
                    $.add_new_droppable('cell', the_row_no_val);
                    break;
                case 'addrow':
                    var the_row_no_val = $('#hval-form-row-count').val();
                    $.add_new_droppable('row', the_row_no_val);
                    break;
                case 'delcell':
                    var the_row_no_val = str_arr_cmd[2];
                    var the_cell_no_val = str_arr_cmd[3];
                    if(the_row_no_val == "1" && the_cell_no_val == "1") {
                        $('#div-dialog-content-form-builder').html("<div class='div-delete-cell-options'>" + WPAnyFormAdminJSO.form_builder_control_confirm_delete_text + " " + WPAnyFormAdminJSO.form_builder_control_confirm_delete_option_control_only + "</div>");
                        var the_delete_control_dialog_buttons_obj = [{ 
                            text: WPAnyFormAdminJSO.form_builder_control_confirm_delete_option_dialog_btn_confirm,
                            click: function () {
                                $.delete_control_form_cell(the_row_no_val, the_cell_no_val);
                                $("#div-wp-any-form-builder-form-cell_" + the_row_no_val + "_" + the_cell_no_val).find(".div-form-builder-img-cmd-container-row.del").remove();
                                $(this).dialog('close');
                            }
                        },
                        {
                            text: WPAnyFormAdminJSO.form_builder_control_confirm_delete_option_dialog_btn_cancel,
                            click : function () { 
                                $(this).dialog('close');
                            }    
                        }];
                        $('#div-dialog-form-builder').dialog({ title: WPAnyFormAdminJSO.form_builder_control_confirm_delete_option_dialog_title, height: 185, width: $.dialog_width_check_adjust_responsive(600), modal: true,
                            buttons: the_delete_control_dialog_buttons_obj
                        });
                    } else {
                        if ($("#div-wp-any-form-builder-form-cell_" + the_row_no_val + "_" + the_cell_no_val).find(".div-control-container").length > 0) { 
                            $('#div-dialog-content-form-builder').html("<div class='div-delete-cell-options'>" + WPAnyFormAdminJSO.form_builder_control_confirm_delete_text + " <select id='ddl_del_cell_options' ><option value='control_only' >" + WPAnyFormAdminJSO.form_builder_control_confirm_delete_option_control_only + "</option><option value='all' >" + WPAnyFormAdminJSO.form_builder_control_confirm_delete_option_control_and_cell + "</option></select></div>");
                            var the_delete_control_dialog_buttons_obj = [{ 
                                text: WPAnyFormAdminJSO.form_builder_control_confirm_delete_option_dialog_btn_confirm,
                                click: function () {
                                    var the_delete_choice_val = $('#ddl_del_cell_options').val();
                                    switch(the_delete_choice_val) {
                                        case 'control_only':
                                            $.delete_control_form_cell(the_row_no_val, the_cell_no_val);
                                            break;
                                        case 'all':
                                            $.delete_control_form_cell(the_row_no_val, the_cell_no_val);
                                            $.delete_form_cell(the_row_no_val, the_cell_no_val);
                                            break;
                                    }
                                    $(this).dialog('close');
                                }
                            },
                            {
                                text: WPAnyFormAdminJSO.form_builder_control_confirm_delete_option_dialog_btn_cancel,
                                click : function () { 
                                    $(this).dialog('close');
                                }    
                            }];
                            $('#div-dialog-form-builder').dialog({ title: WPAnyFormAdminJSO.form_builder_control_confirm_delete_option_dialog_title, height: 205, width: $.dialog_width_check_adjust_responsive(600), modal: true,
                                buttons: the_delete_control_dialog_buttons_obj
                            });
                        } else {
                            $.delete_form_cell(the_row_no_val, the_cell_no_val);
                        }    
                    }
                    break;
                case 'options':
                    var the_row_no_val = str_arr_cmd[2];
                    var the_cell_no_val = str_arr_cmd[3];
                    $.get_control_options('edit', '', the_row_no_val, the_cell_no_val);
                    break;
            }
        });   
    }
    $.get_form_builder_img_cmd_html_js = 
    function get_form_builder_img_cmd_html_js(the_cmd, the_row_no_val, the_cell_no_val) {
        var html_str = "";
        switch(the_cmd) {
            case "addcell":
                html_str = "<div class='div-form-builder-img-cmd-container-cell' ><a id='a-form-builder-img-cmd_addcell_" + the_row_no_val + "' class='a-form-builder-img-cmd' href='javascript:void(0);' ><img src='" + WPAnyFormAdminJSO.plugin_url + "/wp-any-form/img/add.png' title='" + WPAnyFormAdminJSO.form_builder_img_cmd_title_addcell + "' /></a></div>";
                break;
            case "delcell":
                html_str = "<div class='div-form-builder-img-cmd-container-row del' ><a id='a-form-builder-img-cmd_delcell_" + the_row_no_val + "_" + the_cell_no_val + "' class='a-form-builder-img-cmd' href='javascript:void(0);' ><img src='" + WPAnyFormAdminJSO.plugin_url + "/wp-any-form/img/delete.png' title='" + WPAnyFormAdminJSO.form_builder_img_cmd_title_delcell + "' /></a></div>";
                break;
        }
        return html_str;
    }
    $.apply_individual_elements_style_form_builder = 
    function apply_individual_elements_style_form_builder() {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type;
            switch(the_control_type_val) {
                case 'lbl':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    var the_row_no_val = the_control_fields_arr.the_row_no_val;
                    var the_cell_no_val = the_control_fields_arr.the_cell_no_val;
                    $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-lbl-control').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-lbl-control').css('color', the_font_colour_val);
                    }
                    break;
                case 'txt':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    var the_row_no_val = the_control_fields_arr.the_row_no_val;
                    var the_cell_no_val = the_control_fields_arr.the_cell_no_val;
                    $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' input').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' input').css('color', the_font_colour_val);
                    }
                    break;
                case 'txta':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    var the_row_no_val = the_control_fields_arr.the_row_no_val;
                    var the_cell_no_val = the_control_fields_arr.the_cell_no_val;
                    $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' textarea').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' textarea').css('color', the_font_colour_val);
                    }
                    break;
                case 'ddl':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    var the_row_no_val = the_control_fields_arr.the_row_no_val;
                    var the_cell_no_val = the_control_fields_arr.the_cell_no_val;
                    $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' select').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' select').css('color', the_font_colour_val);
                    }
                    $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' select').css('font-size', the_font_size_val + 'px').css('height', 'auto');
                    break;
                case 'cbx':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    var use_flexi_width_val = the_control_fields_arr.use_flexi_width;
                    var the_row_no_val = the_control_fields_arr.the_row_no_val;
                    var the_cell_no_val = the_control_fields_arr.the_cell_no_val;
                    $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-cbx-set-container-cell').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-cbx-set-container-cell').css('color', the_font_colour_val);
                    }
                    $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-cbx-set-container-cell').css('font-size', the_font_size_val + 'px').css('height', 'auto');
                    if(use_flexi_width_val == 'yes') {
                        $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-cbx-set-container-cell').css('width', 'auto');
                        $.apply_style_flexi_width_generic('#div-wp-any-form-builder-form', '#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-cbx-set-container', '.div-cbx-set-container-row', '.div-cbx-set-container-cell');    
                    }
                    break;
                case 'rbtn':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    var use_flexi_width_val = the_control_fields_arr.use_flexi_width;
                    var the_row_no_val = the_control_fields_arr.the_row_no_val;
                    var the_cell_no_val = the_control_fields_arr.the_cell_no_val;
                    $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-rbtn-set-container-cell').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-rbtn-set-container-cell').css('color', the_font_colour_val);
                    }
                    $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-rbtn-set-container-cell').css('font-size', the_font_size_val + 'px').css('height', 'auto');
                    if(use_flexi_width_val == 'yes') {
                        $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-rbtn-set-container-cell').css('width', 'auto');
                        $.apply_style_flexi_width_generic('#div-wp-any-form-builder-form', '#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-rbtn-set-container', '.div-rbtn-set-container-row', '.div-rbtn-set-container-cell');    
                    }
                    break;   
                case 'recaptcha':
                    break;
                case 'btnsubmit':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    var the_row_no_val = the_control_fields_arr.the_row_no_val;
                    var the_cell_no_val = the_control_fields_arr.the_cell_no_val;
                    $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' :submit.div-wp-any-form-btnsubmit').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' :submit.div-wp-any-form-btnsubmit').css('color', the_font_colour_val);
                    }
                    break;
                case 'btnreset':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    var the_row_no_val = the_control_fields_arr.the_row_no_val;
                    var the_cell_no_val = the_control_fields_arr.the_cell_no_val;
                    $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' :submit.div-wp-any-form-btnreset').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $('#div-wp-any-form-builder-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' :submit.div-wp-any-form-btnreset').css('color', the_font_colour_val);
                    }
                    break;
            }    
        }
    }
    $.apply_style_form_builder = 
    function apply_style_form_builder() {
        var apply_responsive = false;
        var the_form_container_width_val = +$(the_form_container_class_id_str).width() - 25;
        if(the_form_container_width_val < 244) {
            the_form_container_width_val = 244;
        }
        var the_default_form_styles_arr = $.get_form_default_styles_arr_admin();
        var form_default_form_width = the_default_form_styles_arr.form_default_form_width;
        var the_form_default_layout = the_default_form_styles_arr.form_default_layout;
        var form_default_cells_per_row = the_default_form_styles_arr.form_default_cells_per_row;
        if(the_form_default_layout == 'grid' && form_default_cells_per_row == '') {
            the_form_default_layout = 'auto';
        }
        var form_default_cell_spacing = the_default_form_styles_arr.form_default_cell_spacing;
        var form_default_row_spacing = the_default_form_styles_arr.form_default_row_spacing;
        var form_default_cell_padding = the_default_form_styles_arr.form_default_cell_padding;
        var form_default_font_size = the_default_form_styles_arr.form_default_font_size;
        var form_default_font_weight = the_default_form_styles_arr.form_default_font_weight;
        var form_default_font_colour = the_default_form_styles_arr.form_default_font_colour;
        var form_default_font_colour_use_defined = the_default_form_styles_arr.form_default_font_colour_use_defined;
        var form_default_message_font_colour = the_default_form_styles_arr.form_default_message_font_colour;
        var form_default_required_field_font_colour = the_default_form_styles_arr.form_default_required_field_font_colour;
        var form_cell_vertical_align = the_default_form_styles_arr.form_cell_vertical_align;
        if(form_default_font_size != '') {
            $(the_form_class_id_str).css('font-size', form_default_font_size + 'px');
            $(the_form_class_id_str + ' input').css('font-size', form_default_font_size + 'px');
            $(the_form_class_id_str + ' textarea').css('font-size', form_default_font_size + 'px');
            $(the_form_class_id_str + ' select').css('font-size', form_default_font_size + 'px');
        }
        if(form_default_font_weight != '') {
            $(the_form_class_id_str).css('font-weight', form_default_font_weight);
            $(the_form_class_id_str + ' input').css('font-weight', form_default_font_weight);
            $(the_form_class_id_str + ' textarea').css('font-weight', form_default_font_weight);
            $(the_form_class_id_str + ' select').css('font-weight', form_default_font_weight);
        }
        if(form_default_font_colour != '' && form_default_font_colour_use_defined == 'yes') {
            $(the_form_class_id_str).css('color', form_default_font_colour);
            $(the_form_class_id_str + ' input').css('color', form_default_font_colour);
            $(the_form_class_id_str + ' textarea').css('color', form_default_font_colour);
            $(the_form_class_id_str + ' select').css('color', form_default_font_colour);
        }
        if(form_default_message_font_colour != '') {
            $('.div-wp-any-form-builder-form-msg').css('color', form_default_message_font_colour);
        }
        if(form_default_required_field_font_colour != '') {
            $('.span-required-field-custom').css('color', form_default_required_field_font_colour);
        }
        $.apply_individual_elements_style_form_builder();
        if(form_cell_vertical_align != '') {
            $('.div-vertical-spacing-cell').css('vertical-align', form_cell_vertical_align);
        }
        if(form_default_cell_spacing != '') {
            $(the_cell_container_class_id_str).css('margin-left', form_default_cell_spacing + 'px');
            $(the_row_container_class_id_str).find(the_cell_container_class_id_str + ':first').css('margin-left', '0px');
        }
        if(form_default_row_spacing != '') {
            $(the_row_container_class_id_str).css('margin-top', form_default_row_spacing + 'px');
        }
        if(form_default_cell_padding != '') {
            $(the_cell_container_class_id_str).css('padding', form_default_cell_padding + 'px');
        }
        switch(the_form_default_layout) {
            case 'auto':
                if(form_default_form_width == '') {
                    $(the_form_class_id_str).css('width', 'auto');
                    apply_responsive = true;
                } else {
                    if(the_form_container_width_val < form_default_form_width) {
                        $(the_form_class_id_str).css('width', the_form_container_width_val + 'px');
                        apply_responsive = true;
                    } else {
                        $(the_form_class_id_str).css('width', form_default_form_width + 'px');
                    }
                }
                $(the_cell_container_class_id_str).css('width', 'auto');
                $('.div-control-container').css('width', 'auto');
                $('.div-lbl-control').css('width', 'auto');
                var form_max_width_val = '';
                if(form_default_form_width == '') {
                    form_max_width_val = the_form_container_width_val;
                } else {
                    if(the_form_container_width_val < form_default_form_width) {
                        form_max_width_val = the_form_container_width_val;
                    } else {
                        form_max_width_val = form_default_form_width;
                    }
                }
                var the_max_control_width_val = +form_max_width_val - 90;
                /* lbl */
                $(the_form_class_id_str).find('.div-lbl-control').css('max-width', the_max_control_width_val + 'px');
                /* txt */
                $(the_form_class_id_str).find('.txt_control').css('max-width', the_max_control_width_val + 'px');
                /* txta */
                $(the_form_class_id_str).find('.txta_control').css('max-width', the_max_control_width_val + 'px');
                /* ddl */
                $(the_form_class_id_str).find('.ddl_control').css('max-width', the_max_control_width_val + 'px');
                /* cbx */
                $(the_form_class_id_str).find('.div-cbx-set-container').css('max-width', the_max_control_width_val + 'px');
                /* rbtn */
                $(the_form_class_id_str).find('.div-rbtn-set-container').css('max-width', the_max_control_width_val + 'px');
                /* recaptcha */
                $(the_form_class_id_str).find('.div-recaptcha-container').css('max-width', the_max_control_width_val + 'px');
                /* btnsubmit */
                $(the_form_class_id_str).find(':submit.div-wp-any-form-btnsubmit').css('max-width', the_max_control_width_val + 'px');
                /* btnreset */
                $(the_form_class_id_str).find(':submit.div-wp-any-form-btnreset').css('max-width', the_max_control_width_val + 'px');
                break;
            case 'grid':
                if(form_default_form_width == '') {
                    form_default_form_width = the_form_container_width_val;
                }
                var the_row_width_val;
                if(the_form_container_width_val < form_default_form_width) {
                    $(the_form_class_id_str).css('width', the_form_container_width_val + 'px');
                    the_row_width_val = the_form_container_width_val;
                    apply_responsive = true;
                } else {
                    $(the_form_class_id_str).css('width', form_default_form_width + 'px');
                    the_row_width_val = form_default_form_width;
                }
                the_row_width_val = +the_row_width_val - 26; /* div-form-builder-img-cmd-container-cell addcell */
                /* spacing */
                if(form_default_cell_spacing != '') {
                    the_row_width_val = +the_row_width_val - (form_default_cell_spacing * (+form_default_cells_per_row-1));    
                }
                /* padding */
                if(form_default_cell_padding != '') {
                    the_row_width_val = +the_row_width_val - (form_default_cell_padding * form_default_cells_per_row * 2);    
                }
                the_cell_width_val = the_row_width_val / form_default_cells_per_row;
                if(the_cell_width_val < 244) {
                    the_cell_width_val = 244;
                    the_row_width_val = the_cell_width_val * form_default_cells_per_row;   
                }
                $(the_cell_container_class_id_str).css('width', the_cell_width_val + 'px');
                /* control container */
                var the_control_container_width_val = +the_cell_width_val - 35; /* div-form-builder-img-cmd-container-row del */
                $('.div-control-container').css('width', the_control_container_width_val + 'px');
                /* lbl */
                var the_lbl_control_width_val = +the_control_container_width_val - 22; /* div-form-builder-img-cmd-container-row options */
                $('.div-lbl-control').css('width', the_lbl_control_width_val + 'px');
                /* txt */
                var the_max_control_width_val = +the_control_container_width_val - 25;
                $(the_form_class_id_str).find('.txt_control').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_builder_cell_width_required_span('.txt_control', the_max_control_width_val);
                /* txta */
                $(the_form_class_id_str).find('.txta_control').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_builder_cell_width_required_span('.txta_control', the_max_control_width_val);
                /* ddl */
                $(the_form_class_id_str).find('.ddl_control').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_builder_cell_width_required_span('.ddl_control', the_max_control_width_val);
                /* cbx */
                $(the_form_class_id_str).find('.div-cbx-set-container').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_builder_cell_width_required_span('.div-cbx-set-container', the_max_control_width_val);
                /* rbtn */
                $(the_form_class_id_str).find('.div-rbtn-set-container').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_builder_cell_width_required_span('.div-rbtn-set-container', the_max_control_width_val);
                /* recaptcha */
                var the_max_control_width_val = +the_control_container_width_val - 25;
                $(the_form_class_id_str).find('.div-recaptcha-container').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_builder_cell_width_required_span('.div-recaptcha-container', the_max_control_width_val);
                /* btnsubmit */
                var the_max_btnsubmit_control_width_val = +the_control_container_width_val - 25;
                $(the_form_class_id_str).find(':submit.div-wp-any-form-btnsubmit').css('max-width', the_max_btnsubmit_control_width_val + 'px');
                /* btnreset */
                var the_max_btnreset_control_width_val = +the_control_container_width_val - 25;
                $(the_form_class_id_str).find(':submit.div-wp-any-form-btnreset').css('max-width', the_max_btnreset_control_width_val + 'px');
                break;
            case 'flexi':
                if(form_default_form_width == '') {
                    $(the_form_class_id_str).css('width', 'auto');
                    apply_responsive = true;
                } else {
                    if(the_form_container_width_val < form_default_form_width) {
                        $(the_form_class_id_str).css('width', the_form_container_width_val + 'px');
                        apply_responsive = true;
                    } else {
                        $(the_form_class_id_str).css('width', form_default_form_width + 'px');
                    }
                }
                $(the_cell_container_class_id_str).css('width', 'auto');
                $('.div-control-container').css('width', 'auto');
                $('.div-lbl-control').css('width', 'auto');
                var form_max_width_val = '';
                if(form_default_form_width == '') {
                    form_max_width_val = the_form_container_width_val;
                } else {
                    if(the_form_container_width_val < form_default_form_width) {
                        form_max_width_val = the_form_container_width_val;
                    } else {
                        form_max_width_val = form_default_form_width;
                    }
                }
                var the_max_control_width_val = +form_max_width_val - 95;
                /* lbl */
                $(the_form_class_id_str).find('.div-lbl-control').css('max-width', the_max_control_width_val + 'px');
                /* txt */
                $(the_form_class_id_str).find('.txt_control').css('max-width', the_max_control_width_val + 'px');
                /* txta */
                $(the_form_class_id_str).find('.txta_control').css('max-width', the_max_control_width_val + 'px');
                /* ddl */
                $(the_form_class_id_str).find('.ddl_control').css('max-width', the_max_control_width_val + 'px');
                /* cbx */
                $(the_form_class_id_str).find('.div-cbx-set-container').css('max-width', the_max_control_width_val + 'px');
                /* rbtn */
                $(the_form_class_id_str).find('.div-rbtn-set-container').css('max-width', the_max_control_width_val + 'px');
                /* recaptcha */
                $(the_form_class_id_str).find('.div-recaptcha-container').css('max-width', the_max_control_width_val + 'px');
                /* btnsubmit */
                $(the_form_class_id_str).find(':submit.div-wp-any-form-btnsubmit').css('max-width', the_max_control_width_val + 'px');
                /* btnreset */
                $(the_form_class_id_str).find(':submit.div-wp-any-form-btnreset').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_builder_flexi_grid_width();
                break;
        }
        $('.div-wp-any-form-builder-form-container').css('height', 'auto');
        if(apply_responsive) {
           $.apply_style_form_builder_margin_left(the_form_container_width_val, form_default_cell_spacing);
        }
        $.apply_style_form_builder_row_height(the_form_container_width_val, form_default_cell_spacing);
    }
    $.get_form_default_styles_arr_admin = 
    function get_form_default_styles_arr_admin() {
        var the_default_form_styles_arr = {};
        the_default_form_styles_arr.form_default_form_width = $('#txt_form_default_form_width').val();
        the_default_form_styles_arr.form_default_layout = $('#ddl_form_default_layout').val();
        the_default_form_styles_arr.form_default_cells_per_row = $('#txt_form_default_cells_per_row').val();
        the_default_form_styles_arr.form_default_cell_spacing = $('#txt_form_default_cell_spacing').val();
        the_default_form_styles_arr.form_default_row_spacing = $('#txt_form_default_row_spacing').val();
        the_default_form_styles_arr.form_default_cell_padding = $('#txt_form_default_cell_padding').val();
        the_default_form_styles_arr.form_default_font_size = $('#txt_form_default_font_size').val();
        the_default_form_styles_arr.form_default_font_weight = $('#ddl_form_default_font_weight').val();
        the_default_form_styles_arr.form_default_font_colour = $('#txt_form_default_font_colour').val();
        the_default_form_styles_arr.form_default_font_colour_use_defined = $.get_yes_no_value_from_cbx('#cbx_form_default_font_colour_use_defined');
        the_default_form_styles_arr.form_default_message_font_colour = $('#txt_form_default_message_font_colour').val();
        the_default_form_styles_arr.form_default_required_field_font_colour = $('#txt_form_default_required_field_font_colour').val();
        the_default_form_styles_arr.form_cell_vertical_align = $('#ddl_form_cell_vertical_align').val();
        return the_default_form_styles_arr;
    } 
    $.apply_style_form_builder_row_height_get_max_height = 
    function apply_style_form_builder_row_height_get_max_height(the_max_height_for_row_val, the_cell_id_str, the_control_class_id_str) {
        var the_new_cell_height_val = 1; 
        var the_cell_control_height_val = $('#' + the_cell_id_str).find(the_control_class_id_str).height();
        if(the_cell_control_height_val > 1) {
            the_new_cell_height_val = +the_cell_control_height_val + 30; 
            if(the_new_cell_height_val > the_max_height_for_row_val) {
                the_max_height_for_row_val = the_new_cell_height_val;
                switch(the_control_class_id_str) {
                    case '.div-cbx-set-container': case '.div-rbtn-set-container':
                        var the_div_vertical_spacing_cell_height_val = $('#' + the_cell_id_str).find('.div-vertical-spacing-cell').height();
                        if(the_div_vertical_spacing_cell_height_val > the_max_height_for_row_val) {
                            the_max_height_for_row_val = the_div_vertical_spacing_cell_height_val;
                        }
                        break;
                }
            }
        }
        return the_max_height_for_row_val;
    }
    $.apply_style_form_builder_row_height = 
    function apply_style_form_builder_row_height(the_form_container_width_val, form_default_cell_spacing) {
        $(the_row_container_class_id_str).each(function() {
            var the_row_id_str = $(this).attr('id');
            var the_row_has_cells_wider_than_screen = $.apply_style_form_builder_responsive_check_if_row_cells_wider_than_screen(the_form_container_width_val, the_row_id_str, form_default_cell_spacing);
            var the_max_height_for_row_val = '-1';
            $('#' + the_row_id_str).find('.div-wp-any-form-builder-form-cell').each(function() {
                var the_cell_id_str = $(this).attr('id');
                var str_arr_cell = the_cell_id_str.split('_');
                var the_row_no_val = str_arr_cell[1];
                var the_cell_no_val = str_arr_cell[2];
                var the_control_fields_arr = $.get_control_fields_arr_from_form_arr(the_form_arr, the_row_no_val, the_cell_no_val);
                var the_control_type_val = the_control_fields_arr.the_control_type;
                switch(the_control_type_val) {
                    case 'lbl':
                        the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height(the_max_height_for_row_val, the_cell_id_str, '.div-lbl-control');
                        break;
                    case 'txt':
                        the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height(the_max_height_for_row_val, the_cell_id_str, '.txt_control');
                        break;
                    case 'txta':
                        the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height(the_max_height_for_row_val, the_cell_id_str, '.txta_control');
                        break;    
                    case 'ddl':
                        the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height(the_max_height_for_row_val, the_cell_id_str, '.ddl_control');
                        break;
                    case 'cbx':
                        the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height(the_max_height_for_row_val, the_cell_id_str, '.div-cbx-set-container');
                        break;
                    case 'rbtn':
                        the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height(the_max_height_for_row_val, the_cell_id_str, '.div-rbtn-set-container');
                        break;
                    case 'recaptcha':
                        the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height(the_max_height_for_row_val, the_cell_id_str, '.div-recaptcha-container');
                        break;     
                    case 'btnsubmit':
                        the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height(the_max_height_for_row_val, the_cell_id_str, '.div-wp-any-form-btnsubmit');
                        break;
                    case 'btnreset':
                        the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height(the_max_height_for_row_val, the_cell_id_str, '.div-wp-any-form-btnreset');
                        break;
                }
                if(the_row_has_cells_wider_than_screen) {
                    if(+the_max_height_for_row_val < 50) {
                        the_max_height_for_row_val = 50;
                    }
                    $('#' + the_cell_id_str).find('.div-control-container').css('height', the_max_height_for_row_val + 'px');
                    $('#' + the_cell_id_str).css('height', the_max_height_for_row_val + 'px');    
                }
            });
            if(the_max_height_for_row_val != '-1') {
                if(+the_max_height_for_row_val < 50) {
                    the_max_height_for_row_val = 50;
                }
                if(!the_row_has_cells_wider_than_screen) {
                    $('#' + the_row_id_str).find('.div-control-container').css('height', the_max_height_for_row_val + 'px');
                    $('#' + the_row_id_str).find(the_cell_container_class_id_str).css('height', the_max_height_for_row_val + 'px');
                    $('#' + the_row_id_str).css('height', the_max_height_for_row_val + 'px');    
                }
                the_max_height_for_row_val = '-1';
            }                      
        });
    }
    $.apply_style_form_builder_margin_left = 
    function apply_style_form_builder_margin_left(the_form_container_width_val, form_default_cell_spacing) {
        $(the_row_container_class_id_str).each(function() {
            var the_row_id_str = $(this).attr('id');
            if($.apply_style_form_builder_responsive_check_if_row_cells_wider_than_screen(the_form_container_width_val, the_row_id_str, form_default_cell_spacing)) {
                $('#' + the_row_id_str).find(the_cell_container_class_id_str).css('margin-left', '0px');
            }
        });
    }
    $.apply_style_form_builder_responsive_check_if_row_cells_wider_than_screen = 
    function apply_style_form_builder_responsive_check_if_row_cells_wider_than_screen(the_form_container_width_val, the_row_id_str, form_default_cell_spacing) {
        var the_row_cells_total_width = 0;
        var the_row_cell_counteri = 0;
        $('#' + the_row_id_str).find(the_cell_container_class_id_str).each(function() {
            the_row_cell_counteri += 1;
            var the_current_cell_width = $(this).width();
            the_row_cells_total_width += the_current_cell_width;
        });
        the_row_cells_total_width = +the_row_cells_total_width + ((+the_row_cell_counteri - 1) * form_default_cell_spacing);
        the_row_cells_total_width += 26; //a-form-builder-img-cmd_addcell_
        if(the_form_container_width_val < the_row_cells_total_width) {
            return true;
        } else {
            return false;
        }
    }
    $.apply_style_form_builder_flexi_grid_width = 
    function apply_style_form_builder_flexi_grid_width() {
        var the_flexi_grid_cell_widths_arr = {};
        $(the_row_container_class_id_str).each(function() {
            var the_row_id_str = $(this).attr('id');
            var the_flexi_grid_cell_counteri = 0;
            $('#' + the_row_id_str).find(the_cell_container_class_id_str).each(function() {
                var the_current_cell_width = $(this).width();
                if(the_flexi_grid_cell_counteri in the_flexi_grid_cell_widths_arr) {
                    var the_current_the_flexi_grid_cell_width_val = the_flexi_grid_cell_widths_arr[the_flexi_grid_cell_counteri];
                    if(the_current_cell_width > the_current_the_flexi_grid_cell_width_val) {
                        the_flexi_grid_cell_widths_arr[the_flexi_grid_cell_counteri] = +the_current_cell_width + 5;        
                    }
                } else {
                    the_flexi_grid_cell_widths_arr[the_flexi_grid_cell_counteri] = +the_current_cell_width + 5;        
                }
                the_flexi_grid_cell_counteri += 1;
            });
        });
        $(the_row_container_class_id_str).each(function() {
            var the_row_id_str = $(this).attr('id');
            var the_flexi_grid_cell_counteri = 0;
            $('#' + the_row_id_str).find(the_cell_container_class_id_str).each(function() {
                var the_current_cell_width = the_flexi_grid_cell_widths_arr[the_flexi_grid_cell_counteri];
                $(this).css('width', the_current_cell_width + 'px');
                /* control container */
                var the_control_container_width_val = +the_current_cell_width - 35; /* div-form-builder-img-cmd-container-row del */
                $(this).find('.div-control-container').css('width', the_control_container_width_val + 'px');
                /* lbl */
                var the_lbl_control_width_val = +the_control_container_width_val - 22; /* div-form-builder-img-cmd-container-row options */
                $(this).find('.div-lbl-control').css('width', the_lbl_control_width_val + 'px');
                the_flexi_grid_cell_counteri += 1;
            });
        });
    }
    $.apply_style_form_builder_cell_width_required_span = 
    function apply_style_form_builder_cell_width_required_span(the_control_class_id_str, the_max_control_width_val) {
        $(the_cell_container_class_id_str).has('.span-required-field-custom').each(function() {
            var the_cell_obj = $(this);
            var the_required_field_asterisk_span_width_val = the_cell_obj.find('.span-required-field-custom').width() + 5;
            var the_new_max_control_width_val = +the_max_control_width_val - the_required_field_asterisk_span_width_val;
            the_cell_obj.find(the_control_class_id_str).css('max-width', the_new_max_control_width_val + 'px');
        });
    }
    /* Functions End */
});