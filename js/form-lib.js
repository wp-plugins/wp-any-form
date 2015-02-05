var recaptcha_onload_callback;
var check_recaptcha_control_exist_time_outs_container_arr = new Array();
var check_if_recaptcha_loaded;

jQuery(function ($) {
    $.is_form_in_preview_mode =
    function is_form_in_preview_mode(the_form_post_id_val) {
        return false;
    }
    $.get_ajax_vars_public_admin_arr =
    function get_ajax_vars_public_admin_arr(the_form_post_id_val) {
        var is_in_preview_mode_val = $.is_form_in_preview_mode(the_form_post_id_val);
        var ajax_vars_arr = {};
        var the_ajax_JSO;
        var the_ajax_url_val = '';
        var the_ajax_action_val = '';
        if(is_in_preview_mode_val) {
            the_ajax_JSO = parent.WPAnyFormAdminJSO;
            the_ajax_url_val = parent.WPAnyFormAdminJSO.ajaxurl;
            the_ajax_action_val = 'wp_any_form_admin-ajax-submit';
        } else {
            the_ajax_JSO = parent.WPAnyFormPublicJSO;
            the_ajax_url_val = WPAnyFormPublicJSO.ajaxurl;
            the_ajax_action_val = 'wp_any_form-ajax-submit';
        }
        ajax_vars_arr.the_ajax_JSO = the_ajax_JSO;
        ajax_vars_arr.the_ajax_url_val = the_ajax_url_val;
        ajax_vars_arr.the_ajax_action_val = the_ajax_action_val;
        return ajax_vars_arr;
    }
    $.get_control_fields_arr_from_form_arr =
    function get_control_fields_arr_from_form_arr(the_form_arr, the_row_no_val, the_cell_no_val) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            if(the_control_fields_arr.the_row_no_val == the_row_no_val && the_control_fields_arr.the_cell_no_val == the_cell_no_val) {
                return the_control_fields_arr;
            }
        }    
        return false;
    }
    $.get_control_fields_arr_from_form_arr_field_id =
    function get_control_fields_arr_from_form_arr_field_id(the_form_arr, the_field_id_val) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            if(the_control_fields_arr.the_field_id == the_field_id_val) {
                return the_control_fields_arr;
            }
        }    
        return false;
    }
    $.get_form_post_id_from_field_id =
    function get_form_post_id_from_field_id(the_field_id_val) {
        var the_form_post_id_str = $('#' + the_field_id_val).parents('.div-wp-any-form-container').attr('id');
        var str_arr_the_form_post_id_str = the_form_post_id_str.split('_');
        var the_form_post_id_val = str_arr_the_form_post_id_str[1];
        return the_form_post_id_val;
    }
    $.get_control_fields_arr_from_form_arr_control_type =
    function get_control_fields_arr_from_form_arr_control_type(the_form_arr, the_control_type_to_check_val) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type; 
            switch(the_control_type_val) {
                case the_control_type_to_check_val:
                    return the_control_fields_arr;
                    break;
            }
        }    
        return false;
    }
    $.control_type_is_in_form_arr = 
    function control_type_is_in_form_arr(the_form_arr, the_control_type_to_check_val) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type; 
            switch(the_control_type_val) {
                case the_control_type_to_check_val:
                    return true;
                    break;
            }
        }
        return false;
    }
    $.ddl_get_initial_item_set_i = 
    function ddl_get_initial_item_set_i(ddl_item_sets_arr) {
        var the_initial_item_set_i = false;
        if(ddl_item_sets_arr != "none") {
            if(ddl_item_sets_arr.length > 0) {
                the_initial_item_set_i = 0;
                for (var i = 0; i < ddl_item_sets_arr.length; i++) {
                    var ddl_item_set_arr = ddl_item_sets_arr[i];
                    var is_initial = ddl_item_set_arr.is_initial;
                    if(is_initial == "yes") {
                        the_initial_item_set_i = i;
                    }
                }
            }
        }
        return the_initial_item_set_i;
    }
    $.perform_ddl_selected_changed = 
    function perform_ddl_selected_changed(the_form_arr, the_ddl_id_val, is_form_init) {
        var the_control_fields_arr = $.get_control_fields_arr_from_form_arr_field_id(the_form_arr, the_ddl_id_val);
        var the_ddl_selected_index_val = $('#' + the_ddl_id_val + ' option:selected').index();
        if(the_ddl_selected_index_val != '-1') {
            var ddl_item_sets_arr = the_control_fields_arr.ddl_item_sets_arr;
            var the_current_item_set_i = the_control_fields_arr.the_current_item_set_i;
            if(the_current_item_set_i == '-1') {
                the_current_item_set_i = $.ddl_get_initial_item_set_i(ddl_item_sets_arr);
            }
            var ddl_item_set_arr = ddl_item_sets_arr[the_current_item_set_i];
            var items_arr = ddl_item_set_arr.items_arr;
            var item_arr = items_arr[the_ddl_selected_index_val];
            var item_sub_option_val = item_arr.item_sub_option;
            if(item_sub_option_val != 'none') {
                $.ddl_load_sub_options(the_form_arr, the_ddl_id_val, item_sub_option_val, is_form_init);
            } else {
                var the_next_ddl_id_val = the_control_fields_arr.the_next_ddl_id_val;
                $('#' + the_next_ddl_id_val).css('display', 'none');
                if ($('#' + the_next_ddl_id_val).closest('.div-wp-any-form-cell').find('.span-required-field-custom').length > 0) { 
                    $('#' + the_next_ddl_id_val).closest('.div-wp-any-form-cell').find('.span-required-field-custom').css('display', 'none');
                }
            }    
        } else {
            $('#' + the_ddl_id_val + ' option:first-child').attr('selected', 'selected');
            $.perform_ddl_selected_changed(the_form_arr, the_ddl_id_val, is_form_init);
        }
    }
    $.ddl_load_sub_options = 
    function ddl_load_sub_options(the_form_arr, the_ddl_id_val, item_sub_option_val, is_form_init) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type; 
            switch(the_control_type_val) {
                case 'ddl':
                    if(the_control_fields_arr.the_field_id != the_ddl_id_val) {
                        var ddl_item_sets_arr = the_control_fields_arr.ddl_item_sets_arr;
                        if(ddl_item_sets_arr != "none") {
                            if(ddl_item_sets_arr.length > 0) {
                                for (var j = 0; j < ddl_item_sets_arr.length; j++) {
                                    var ddl_item_set_arr = ddl_item_sets_arr[j];
                                    var the_item_set_id_val = ddl_item_set_arr.item_set_id;
                                    if(the_item_set_id_val == item_sub_option_val) {
                                        the_control_fields_arr.the_current_item_set_i = j;
                                        the_form_arr[i] = the_control_fields_arr;
                                        var items_arr = ddl_item_set_arr.items_arr;
                                        var the_new_options_arr = new Array();
                                        for (var k = 0; k < items_arr.length; k++) {
                                            var item_arr = items_arr[k];  
                                            var item_description_val = item_arr.item_description;
                                            var item_value_val = item_arr.item_value;
                                            var the_new_option_arr = {'text': item_description_val, 'value': item_value_val};
                                            the_new_options_arr.push(the_new_option_arr);
                                        }
                                        $.replace_ddl_options(the_control_fields_arr.the_field_id, the_new_options_arr);
                                        if(is_form_init) {
                                            $('#' + the_control_fields_arr.the_field_id).val(the_control_fields_arr.the_selected_value);
                                        }
                                        $.perform_ddl_selected_changed(the_form_arr, the_control_fields_arr.the_field_id, is_form_init);
                                    }
                                }
                            }    
                        }    
                    }
                    break;
            }
        }
    }
    $.set_all_next_ddl_id_vals = 
    function set_all_next_ddl_id_vals(the_form_arr) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type; 
            switch(the_control_type_val) {
                case 'ddl':
                    var the_ddl_id_val = the_control_fields_arr.the_field_id;
                    var ddl_item_sets_arr = the_control_fields_arr.ddl_item_sets_arr;
                    if(ddl_item_sets_arr != "none") {
                        if(ddl_item_sets_arr.length > 0) {
                            for (var j = 0; j < ddl_item_sets_arr.length; j++) {
                                var ddl_item_set_arr = ddl_item_sets_arr[j];
                                var items_arr = ddl_item_set_arr.items_arr;
                                for (var k = 0; k < items_arr.length; k++) {
                                    var item_arr = items_arr[k];  
                                    var item_sub_option_val = item_arr.item_sub_option;
                                    if(item_sub_option_val != 'none') {
                                        var the_next_ddl_id_val = $.get_ddl_id_val_from_item_set_id_val(the_form_arr, item_sub_option_val);
                                        if(the_next_ddl_id_val != '-1') {
                                            $.set_the_next_ddl_id_val(the_form_arr, the_ddl_id_val, the_next_ddl_id_val);
                                        }
                                    }
                                }
                            }
                        }    
                    }    
                    break;
            }
        }
    }
    $.get_ddl_id_val_from_item_set_id_val =
    function get_ddl_id_val_from_item_set_id_val(the_form_arr, item_sub_option_val) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type;
            switch(the_control_type_val) {
                case 'ddl':
                    var the_ddl_id_val = the_control_fields_arr.the_field_id;
                    var ddl_item_sets_arr = the_control_fields_arr.ddl_item_sets_arr;
                    if(ddl_item_sets_arr != "none") {
                        if(ddl_item_sets_arr.length > 0) {
                            for (var j = 0; j < ddl_item_sets_arr.length; j++) {
                                var ddl_item_set_arr = ddl_item_sets_arr[j];
                                var the_item_set_id_val = ddl_item_set_arr.item_set_id;
                                if(the_item_set_id_val == item_sub_option_val) {
                                    return the_ddl_id_val;
                                }
                            }
                        }
                    }
                    break;
            }
        }    
        return '-1';
    }
    $.set_the_next_ddl_id_val =
    function set_the_next_ddl_id_val(the_form_arr, the_ddl_id_val, the_next_ddl_id_val) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            if(the_control_fields_arr.the_field_id == the_ddl_id_val) {
                the_control_fields_arr.the_next_ddl_id_val = the_next_ddl_id_val;
                the_form_arr[i] = the_control_fields_arr;
            }
        }    
    }
    $.hide_inactive_ddls_in_chain =
    function hide_inactive_ddls_in_chain(the_form_arr, the_prev_ddl_id_val) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            if(the_control_fields_arr.the_field_id == the_prev_ddl_id_val) {
                var the_next_ddl_id_val = the_control_fields_arr.the_next_ddl_id_val;
                if(!$('#' + the_prev_ddl_id_val).is(":visible")) {
                    $('#' + the_next_ddl_id_val).css('display', 'none');
                    if ($('#' + the_next_ddl_id_val).closest('.div-wp-any-form-cell').find('.span-required-field-custom').length > 0) { 
                        $('#' + the_next_ddl_id_val).closest('.div-wp-any-form-cell').find('.span-required-field-custom').css('display', 'none');
                    }
                }
                if(the_next_ddl_id_val != '-1') {
                    $.hide_inactive_ddls_in_chain(the_form_arr, the_next_ddl_id_val);
                }
            }
        }    
    }
    $.set_is_top_ddl_vals =
    function set_is_top_ddl_vals(the_form_arr) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type;
            switch(the_control_type_val) {
                case 'ddl':
                    var the_ddl_id_val = the_control_fields_arr.the_field_id;
                    var ddl_item_sets_ids_arr = new Array();
                    var ddl_item_sets_arr = the_control_fields_arr.ddl_item_sets_arr;
                    if(ddl_item_sets_arr != "none") {
                        if(ddl_item_sets_arr.length > 0) {
                            for (var j = 0; j < ddl_item_sets_arr.length; j++) {
                                var ddl_item_set_arr = ddl_item_sets_arr[j];
                                var the_item_set_id_val = ddl_item_set_arr.item_set_id;
                                ddl_item_sets_ids_arr.push(the_item_set_id_val);
                            }
                        }
                    }
                    if(!$.ddl_check_if_any_other_ddl_links_to_ddl_item_sets(the_form_arr, the_ddl_id_val, ddl_item_sets_ids_arr)) {
                        the_control_fields_arr.is_top_ddl_val = true;
                        the_form_arr[i] = the_control_fields_arr;
                    }
                    break;
            }
        }    
    }
    $.ddl_check_if_any_other_ddl_links_to_ddl_item_sets = 
    function ddl_check_if_any_other_ddl_links_to_ddl_item_sets(the_form_arr, the_ddl_id_val, ddl_item_sets_ids_arr) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type; 
            switch(the_control_type_val) {
                case 'ddl':
                    if(the_control_fields_arr.the_field_id != the_ddl_id_val) {
                        var ddl_item_sets_arr = the_control_fields_arr.ddl_item_sets_arr;
                        if(ddl_item_sets_arr != "none") {
                            if(ddl_item_sets_arr.length > 0) {
                                for (var j = 0; j < ddl_item_sets_arr.length; j++) {
                                    var ddl_item_set_arr = ddl_item_sets_arr[j];
                                    var items_arr = ddl_item_set_arr.items_arr;
                                    for (var k = 0; k < items_arr.length; k++) {
                                        var item_arr = items_arr[k];  
                                        var item_sub_option_val = item_arr.item_sub_option;
                                        if(item_sub_option_val != 'none') {
                                            if($.inArray(item_sub_option_val, ddl_item_sets_ids_arr) !== -1) {
                                                return true;
                                            }
                                        }
                                    }
                                }
                            }    
                        }    
                    }
                    break;
            }
        }
        return false;
    }
    $.perform_ddl_selected_changed_top_ddls_form_init = 
    function perform_ddl_selected_changed_top_ddls_form_init(the_form_arr, is_reset) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type; 
            switch(the_control_type_val) {
                case 'ddl':
                    if(the_control_fields_arr.is_top_ddl_val) {
                        var the_ddl_id_val = the_control_fields_arr.the_field_id;
                        if(is_reset) {
                            $('#' + the_ddl_id_val).val(the_control_fields_arr.the_selected_value);
                        }
                        $.perform_ddl_selected_changed(the_form_arr, the_ddl_id_val, true);
                        $.hide_inactive_ddls_in_chain(the_form_arr, the_ddl_id_val);
                    }
                    break;
            }
        }
    }
    $.ddl_validate_check_if_selected_value_is_not_selected_indicator = 
    function ddl_validate_check_if_selected_value_is_not_selected_indicator(the_control_fields_arr, the_selected_value) {
        var ddl_item_sets_arr = the_control_fields_arr.ddl_item_sets_arr;
        if(ddl_item_sets_arr != "none") {
            if(ddl_item_sets_arr.length > 0) {
                for (var i = 0; i < ddl_item_sets_arr.length; i++) {
                    var ddl_item_set_arr = ddl_item_sets_arr[i];
                    var items_arr = ddl_item_set_arr.items_arr;
                    for (var j = 0; j < items_arr.length; j++) {
                        var item_arr = items_arr[j];  
                        if(item_arr.is_not_selected_indicator == 'yes') {
                            if(item_arr.item_value == the_selected_value) {
                                return true;
                            }
                        }
                    }
                }
            }    
        }
        return false;    
    }
    $.apply_style_flexi_width_generic = 
    function apply_style_flexi_width_generic(the_form_class_id_str, the_container_class_id_str, the_row_container_class_id_str, the_cell_container_class_id_str) {
        var the_flexi_grid_cell_widths_arr = {};
        $(the_form_class_id_str).find(the_container_class_id_str).find(the_row_container_class_id_str).each(function() {
            var the_flexi_grid_cell_counteri = 0;
            $(this).find(the_cell_container_class_id_str).each(function() {
                var the_current_cell_width = $(this).width();
                if(the_flexi_grid_cell_counteri in the_flexi_grid_cell_widths_arr) {
                    var the_current_the_flexi_grid_cell_width_val = the_flexi_grid_cell_widths_arr[the_flexi_grid_cell_counteri];
                    if(the_current_cell_width > the_current_the_flexi_grid_cell_width_val) {
                        the_flexi_grid_cell_widths_arr[the_flexi_grid_cell_counteri] = +the_current_cell_width + 10;        
                    }
                } else {
                    the_flexi_grid_cell_widths_arr[the_flexi_grid_cell_counteri] = +the_current_cell_width + 10;        
                }
                the_flexi_grid_cell_counteri += 1;
            });
        });
        $(the_form_class_id_str).find(the_container_class_id_str).find(the_row_container_class_id_str).each(function() {
            var the_flexi_grid_cell_counteri = 0;
            $(this).find(the_cell_container_class_id_str).each(function() {
                var the_current_cell_width = the_flexi_grid_cell_widths_arr[the_flexi_grid_cell_counteri];
                $(this).css('width', the_current_cell_width + 'px');
                the_flexi_grid_cell_counteri += 1;
            });
        });
    }
    $.reset_style_flexi_width_generic = 
    function reset_style_flexi_width_generic(the_form_class_id_str, the_container_class_id_str, the_row_container_class_id_str, the_cell_container_class_id_str) {
        $(the_form_class_id_str).find(the_container_class_id_str).find(the_row_container_class_id_str).each(function() {
            $(this).find(the_cell_container_class_id_str).each(function() {
                $(this).css('width', 'auto');
            });
        });
    }
    $.create_the_recaptcha_controls_arr_and_init =
    function create_the_recaptcha_controls_arr_and_init() {
        for (var i = 0; i < the_forms_container_arr.length; i++) {
            var the_form_container_arr = the_forms_container_arr[i];
            var the_form_arr = the_form_container_arr.the_form_arr;
            for (var j = 0; j < the_form_arr.length; j++) {
                var the_control_fields_arr = the_form_arr[j];
                var the_control_type_val = the_control_fields_arr.the_control_type;
                switch(the_control_type_val) {
                    case 'recaptcha':
                        var the_field_id_val = the_control_fields_arr.the_field_id;
                        var the_form_recaptcha_control_arr = {};
                        the_form_recaptcha_control_arr.the_field_id = the_field_id_val;
                        the_form_recaptcha_control_arr.the_widget_id = 'init';
                        the_form_recaptcha_control_arr.the_theme = the_control_fields_arr.the_theme;
                        the_recaptcha_controls_arr.push(the_form_recaptcha_control_arr);
                        break; 
                }
            }
        }
        if(the_recaptcha_controls_arr.length > 0) {
            $.load_recaptcha_js_lib_callback(false);    
        }
    }
    $.load_recaptcha_js_lib_callback =
    function load_recaptcha_js_lib_callback(is_builder) {
        check_if_recaptcha_loaded = function(the_field_id_val) {
            $.clear_check_recaptcha_control_exist_time_out(the_field_id_val);
            if ($('#' + the_field_id_val +  ' iframe').length > 0) {
                if (is_builder) {
                    $.apply_style_form_builder();    
                } else {
                    var the_form_post_id_val = $.get_form_post_id_from_field_id(the_field_id_val);
                    var the_form_container_arr = $.get_the_form_container_arr_the_form_post_id(the_form_post_id_val);
                    var the_form_arr = the_form_container_arr.the_form_arr;
                    var the_form_vars_arr = the_form_container_arr.the_form_vars_arr;
                    $.apply_style_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr);
                }
            } else {
                var the_active_time_out = setTimeout(function() {
                    check_if_recaptcha_loaded(the_field_id_val);
                }, 100);
                var the_active_time_outs_arr = {};
                the_active_time_outs_arr.the_field_id == the_field_id_val;
                the_active_time_outs_arr.the_active_time_out = the_active_time_out;
                check_recaptcha_control_exist_time_outs_container_arr.push(the_active_time_outs_arr);
            }
        }
        $.clear_check_recaptcha_control_exist_time_out =
        function clear_check_recaptcha_control_exist_time_out(the_field_id_val) {
            var the_arr_index_to_splice_val = '-1';
            for (var i = 0; i < check_recaptcha_control_exist_time_outs_container_arr.length; i++) {
                var the_active_time_outs_arr = check_recaptcha_control_exist_time_outs_container_arr[i];
                if(the_active_time_outs_arr.the_field_id == the_field_id_val) {
                    if (the_active_time_outs_arr.the_active_time_out) {
                        clearTimeout(the_active_time_outs_arr.the_active_time_out);
                    }
                    the_arr_index_to_splice_val = i;
                }
            };
            if(the_arr_index_to_splice_val != '-1') {
                check_recaptcha_control_exist_time_outs_container_arr.splice(the_arr_index_to_splice_val, 1);
            }
        }
        var recaptcha_site_key_val = '-1';
        recaptcha_onload_callback = function() {
            for (var i = 0; i < the_recaptcha_controls_arr.length; i++) {
                var the_form_recaptcha_control_arr = the_recaptcha_controls_arr[i];
                var the_field_id_val = the_form_recaptcha_control_arr.the_field_id;
                if(recaptcha_site_key_val == '-1') {
                    if (is_builder) {
                        recaptcha_site_key_val = WPAnyFormAdminJSO.recaptcha_site_key;
                    } else {
                        var the_form_post_id_val = $.get_form_post_id_from_field_id(the_field_id_val);
                        var is_in_preview_mode_val = $.is_form_in_preview_mode(the_form_post_id_val);
                        if(is_in_preview_mode_val) {
                            recaptcha_site_key_val = parent.WPAnyFormAdminJSO.recaptcha_site_key;
                        } else {
                            recaptcha_site_key_val = WPAnyFormPublicJSO.recaptcha_site_key;
                        }
                    }
                }
                $('#' + the_field_id_val).html('');
                the_form_recaptcha_control_arr.the_widget_id = grecaptcha.render(the_field_id_val, {
                    'sitekey' : recaptcha_site_key_val,
                    'theme' : the_form_recaptcha_control_arr.the_theme
                });    
                $.update_the_recaptcha_controls_arr_form_recaptcha_control_arr(the_field_id_val, the_form_recaptcha_control_arr);   
                check_if_recaptcha_loaded(the_field_id_val);
            }
        }
        var the_recaptcha_js_api_url_str = 'https://www.google.com/recaptcha/api.js?onload=recaptcha_onload_callback&render=explicit';
        if(the_recaptcha_language_val != 'auto') {
            the_recaptcha_js_api_url_str += '&hl=' + the_recaptcha_language_val;
        }
        $.ajax({
            url: the_recaptcha_js_api_url_str,
            dataType: "script",
            async: false
        });
    }
    $.get_the_recaptcha_controls_arr_from_form_recaptcha_control_arr =
    function get_the_recaptcha_controls_arr_from_form_recaptcha_control_arr(the_field_id_val) {
        for (var i = 0; i < the_recaptcha_controls_arr.length; i++) {
            var the_form_recaptcha_control_arr = the_recaptcha_controls_arr[i];
            if(the_form_recaptcha_control_arr.the_field_id == the_field_id_val) {
                return the_form_recaptcha_control_arr;
            }
        };    
        return false;
    }
    $.update_the_recaptcha_controls_arr_form_recaptcha_control_arr =
    function update_the_recaptcha_controls_arr_form_recaptcha_control_arr(the_field_id_val, new_form_recaptcha_control_arr) {
        for (var i = 0; i < the_recaptcha_controls_arr.length; i++) {
            var the_form_recaptcha_control_arr = the_recaptcha_controls_arr[i];
            if(the_form_recaptcha_control_arr.the_field_id == the_field_id_val) {
                the_recaptcha_controls_arr[i] = new_form_recaptcha_control_arr;
            }
        };    
        return false;
    }
    $.get_the_form_container_arr_the_form_post_id =
    function get_the_form_container_arr_the_form_post_id(the_form_post_id_val_to_get) {
        for (var i = 0; i < the_forms_container_arr.length; i++) {
            var the_form_container_arr = the_forms_container_arr[i];
            var the_form_post_id_val = the_form_container_arr.the_post_id;
            if(the_form_post_id_val == the_form_post_id_val_to_get) {
                return the_form_container_arr;
            }
        }
        return false;
    }
    $.init_pop_up_form_and_apply_style_on_open = 
    function init_pop_up_form_and_apply_style_on_open(the_form_post_id_val, pop_up_form_link_type_val) {
        var the_pop_up_form_arr = {};
        the_pop_up_form_arr.the_form_post_id = the_form_post_id_val;
        the_pop_up_form_arr.init_complete = false;
        the_pop_up_forms_arr.push(the_pop_up_form_arr);
        var the_pop_up_open_id_str;
        switch(pop_up_form_link_type_val) {
            case 'link':
                the_pop_up_open_id_str = '#aopen-popup-form_' + the_form_post_id_val;
                break;
            case 'btn':
                the_pop_up_open_id_str = '#btn-open-popup-form_' + the_form_post_id_val;
                break;
        }
        $(the_pop_up_open_id_str).magnificPopup({
            items: {
                src: '#div-popup-form_' + the_form_post_id_val,
                type: 'inline'
            },
            callbacks: {
                open: function() {
                    var the_current_pop_up_id_str = $.magnificPopup.instance.currItem.data.src;
                    var str_arr_current_pop_up_id_str = the_current_pop_up_id_str.split('_');
                    var the_current_pop_up_id_str_val = str_arr_current_pop_up_id_str[1];
                    var the_form_container_arr = $.get_the_form_container_arr_the_form_post_id(the_current_pop_up_id_str_val);
                    var the_form_arr = the_form_container_arr.the_form_arr;
                    var the_form_vars_arr = the_form_container_arr.the_form_vars_arr;
                    var the_pop_up_form_arr = $.get_the_pop_up_form_arr_from_the_pop_up_forms_arr(the_current_pop_up_id_str_val);
                    if(!the_pop_up_form_arr.init_complete) {
                        $.init_control_active_js_behaviours_on_load_public(the_current_pop_up_id_str_val, the_form_arr, the_form_vars_arr);
                        $.apply_style_form_public(the_current_pop_up_id_str_val, the_form_arr, the_form_vars_arr);    
                        $(window).bind('resize orientationchange', function() {
                            $.apply_style_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr);
                        });   
                        the_pop_up_form_arr.init_complete = true;
                        $.update_the_pop_up_forms_arr_with_the_pop_up_form_arr(the_current_pop_up_id_str_val, the_pop_up_form_arr);
                    }
                }
            }  
        });
    }
    $.get_the_pop_up_form_arr_from_the_pop_up_forms_arr =
    function get_the_pop_up_form_arr_from_the_pop_up_forms_arr(the_form_post_id_val) {
        for (var i = 0; i < the_pop_up_forms_arr.length; i++) {
            var the_pop_up_form_arr = the_pop_up_forms_arr[i];
            if(the_pop_up_form_arr.the_form_post_id == the_form_post_id_val) {
                return the_pop_up_form_arr;
            }
        };    
        return false;
    }
    $.update_the_pop_up_forms_arr_with_the_pop_up_form_arr =
    function update_the_pop_up_forms_arr_with_the_pop_up_form_arr(the_form_post_id_val, new_pop_up_form_arr) {
        for (var i = 0; i < the_pop_up_forms_arr.length; i++) {
            var the_pop_up_form_arr = the_pop_up_forms_arr[i];
            if(the_pop_up_form_arr.the_form_post_id == the_form_post_id_val) {
                the_pop_up_forms_arr[i] = new_pop_up_form_arr;
            }
        };    
        return false;
    }
    $.get_the_form_container_width_val =
    function get_the_form_container_width_val(the_form_post_id_val, the_form_vars_arr, apply_width) {
        var the_form_container_class_id_str = '#div-wp-any-form-container_' + the_form_post_id_val;
        var the_form_container_width_val = $(the_form_container_class_id_str).width();
        var form_default_form_width = the_form_vars_arr.form_default_form_width;
        var pop_up_form_is_pop_up = the_form_vars_arr.pop_up_form_is_pop_up;
        if(pop_up_form_is_pop_up == 'yes') {
            if(the_form_container_width_val < form_default_form_width) {
                var mfp_content_width = $(the_form_container_class_id_str).parents('.mfp-content').width();
                the_form_container_width_val = (+mfp_content_width - 40);
                if(apply_width) {
                    $(the_form_container_class_id_str).css('width',  the_form_container_width_val + 'px');    
                }
            } else {
                if(apply_width) {
                    $(the_form_container_class_id_str).css('width',  '100%');
                }
            }
        }
        return the_form_container_width_val;
    }
    $.apply_style_form_public_resize_orientationchange_all_non_pop_up_forms =
    function apply_style_form_public_resize_orientationchange_all_non_pop_up_forms() {
        for (var i = 0; i < the_forms_container_arr.length; i++) {
            var the_form_container_arr = the_forms_container_arr[i];
            var the_form_post_id_val = the_form_container_arr.the_post_id;
            var the_form_arr = the_form_container_arr.the_form_arr;
            var the_form_vars_arr = the_form_container_arr.the_form_vars_arr;
            if(the_form_vars_arr.pop_up_form_is_pop_up == 'no') {
                $.apply_style_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr);
            }
        }
    }
    $.dialog_width_check_adjust_responsive =
    function dialog_width_check_adjust_responsive(the_dialog_width_val) {
        var the_window_width = +$(window).width() - 25;
        if(the_dialog_width_val > the_window_width) {
            the_dialog_width_val = the_window_width;
        }
        return the_dialog_width_val;
    }
});