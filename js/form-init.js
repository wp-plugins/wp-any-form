var the_forms_container_arr = new Array();
var the_recaptcha_controls_arr = new Array();
var the_recaptcha_language_val = '-1';
var the_pop_up_forms_arr = new Array();

jQuery(function ($) {
    /* Functions Start */
    $.init_control_active_js_behaviours_on_load_public = 
    function init_control_active_js_behaviours_on_load_public(the_form_post_id_val, the_form_arr, the_form_vars_arr) {
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type;
            switch(the_control_type_val) {
                case 'btnsubmit':
                    var the_btn_id_val = the_control_fields_arr.the_btn_id;
                    $('#' + the_btn_id_val).on('click',function() {
                        $.submit_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr);
                    });
                    break;
                case 'btnreset':
                    var the_btn_id_val = the_control_fields_arr.the_btn_id;
                    $('#' + the_btn_id_val).on('click',function() {
                        $.reset_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr, false, false);
                    });
                    break;
                case 'ddl':
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    $('#' + the_field_id_val).on('change',function() {
                        var the_ddl_id_val = $(this).attr('id');
                        $.perform_ddl_selected_changed(the_form_arr, the_ddl_id_val, false);
                        $.hide_inactive_ddls_in_chain(the_form_arr, the_ddl_id_val);
                    });
                    break;
                case 'rbtn':
                    break;
                case 'cbx':
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    $('.' + the_field_id_val).on('change',function() {
                        if($(this).prop('checked')) {
                            $(this).closest('.div-cbx-set-container-cell').css('border', '0px');
                        }
                    });
                    break;
                case 'recaptcha':
                    break; 
            }
        };
        $.init_required_fields_msg(the_form_post_id_val, the_form_vars_arr);
        $.set_is_top_ddl_vals(the_form_arr);
        $.set_all_next_ddl_id_vals(the_form_arr);
        $.perform_ddl_selected_changed_top_ddls_form_init(the_form_arr, false);
    }
    $.init_required_fields_msg = 
    function init_required_fields_msg(the_form_post_id_val, the_form_vars_arr) {
        if ($("#div-wp-any-form_" + the_form_post_id_val).find(".span-required-field-custom").length > 0) {
            var form_messages_required_fields_val = the_form_vars_arr.form_messages_required_fields;
            if(form_messages_required_fields_val.indexOf('*') > -1) {
                form_messages_required_fields_val = form_messages_required_fields_val.replace('*', the_asterisk_html_str_user_html_str); 
            }
            $('#div-wp-any-form-msg_' + the_form_post_id_val).html(form_messages_required_fields_val);
            if(the_form_vars_arr.form_default_required_field_font_colour != '') {
                $("#div-wp-any-form_" + the_form_post_id_val).find('.span-required-field-custom').css('color', the_form_vars_arr.form_default_required_field_font_colour);
            }
        }
    }
    $.submit_form_public = 
    function submit_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr) {
        $.clear_active_form_msg_time_out('#div-wp-any-form-msg_' + the_form_post_id_val);
        $('#div-wp-any-form-msg_' + the_form_post_id_val).html('');
        var error_msg_val = '';
        var the_validate_return_arr = $.validate_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr);
        error_msg_val = the_validate_return_arr.error_msg_val;
        if(error_msg_val == '') {
            var the_form_submit_arr = the_validate_return_arr.the_form_submit_arr;
            if($.control_type_is_in_form_arr(the_form_arr, 'recaptcha')) {
                var the_control_fields_arr = $.get_control_fields_arr_from_form_arr_control_type(the_form_arr, 'recaptcha');
                $.validate_recaptcha_and_continue_submit_if_check_ok(the_control_fields_arr, the_form_post_id_val, the_form_arr, the_form_vars_arr, the_form_submit_arr);
            } else {
                $.post_submitted_validated_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr, the_form_submit_arr);
            }    
        } else {
            $('#div-wp-any-form-msg_' + the_form_post_id_val).html(error_msg_val); 
            if(the_form_vars_arr.form_submit_scroll_to_msg == 'yes') {
                $.scroll_to_element('#div-wp-any-form-msg_' + the_form_post_id_val, 35);     
            }
        }
    }
    $.validate_recaptcha_and_continue_submit_if_check_ok = 
    function validate_recaptcha_and_continue_submit_if_check_ok(the_control_fields_arr, the_form_post_id_val, the_form_arr, the_form_vars_arr, the_form_submit_arr) {
        var the_field_id_val = the_control_fields_arr.the_field_id;
        var the_form_recaptcha_control_arr = $.get_the_recaptcha_controls_arr_from_form_recaptcha_control_arr(the_field_id_val);
        var the_widget_id_val = the_form_recaptcha_control_arr.the_widget_id;
        var the_response_val = grecaptcha.getResponse(the_widget_id_val);
        $('#div-wp-any-form-msg_' + the_form_post_id_val).html(the_control_fields_arr.validating_recaptcha_msg);
        if(the_form_vars_arr.form_submit_scroll_to_msg == 'yes') {
            $.scroll_to_element('#div-wp-any-form-msg_' + the_form_post_id_val, 35);     
        }
        var ajax_vars_arr = $.get_ajax_vars_public_admin_arr(the_form_post_id_val);
        $.ajax({ 
            type : 'POST', 
            url : ajax_vars_arr.the_ajax_url_val,  
            dataType : 'json', 
            data: { action : ajax_vars_arr.the_ajax_action_val, cmd: 'recaptcha-validate', the_response: the_response_val }, 
            success : function(data) {
                if (!data.error) {
                    if (data.recaptcha_isvalid) {
                        $.post_submitted_validated_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr, the_form_submit_arr);
                    } else {
                        grecaptcha.reset(the_widget_id_val);
                        $('#div-wp-any-form-msg_' + the_form_post_id_val).html(the_control_fields_arr.recaptcha_validation_failed_msg);
                        if(the_form_vars_arr.form_submit_scroll_to_msg == 'yes') {
                            $.scroll_to_element('#div-wp-any-form-msg_' + the_form_post_id_val, 35);     
                        }
                    }
                } else {
                    grecaptcha.reset(the_widget_id_val);
                    $('#div-wp-any-form-msg_' + the_form_post_id_val).html(server_error_msg_val);
                    if(the_form_vars_arr.form_submit_scroll_to_msg == 'yes') {
                        $.scroll_to_element('#div-wp-any-form-msg_' + the_form_post_id_val, 35);     
                    }
                }
            }
        });
    }
    $.validate_form_public = 
    function validate_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr) {
        var error_msg_val = '';
        var the_validate_return_arr = {};
        var the_form_submit_arr = new Array();
        for (var i = 0; i < the_form_arr.length; i++) {
            if(error_msg_val != '') {
                the_validate_return_arr.error_msg_val = error_msg_val;
                the_validate_return_arr.the_form_submit_arr = the_form_submit_arr;
                return the_validate_return_arr;
            }
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type;
            switch(the_control_type_val) {
                case 'txt':
                    var the_field_name_val = the_control_fields_arr.the_field_name;
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    var is_required_val = the_control_fields_arr.is_required;
                    var is_numeric_val = the_control_fields_arr.is_numeric;
                    var is_email_val = the_control_fields_arr.is_email;
                    var is_confirm_val = the_control_fields_arr.is_confirm;
                    var the_txt_val = $('#' + the_field_id_val).val();
                    if(is_required_val == 'yes' && $.check_if_str_value_empty(the_txt_val)) {
                        error_msg_val = the_field_name_val + ' ' + validation_str_required_val + '.';
                    }
                    if(error_msg_val == '') {
                        if(is_email_val == 'yes' && !$.webovalidEmail(the_txt_val)) {
                            error_msg_val = validation_str_email_val + ' ' + the_field_name_val + '.';     
                        }
                    }
                    if(error_msg_val == '') {
                        if(is_confirm_val == 'yes') {
                            var confirm_control_txt_field_id_val = the_control_fields_arr.confirm_control_txt_field_id;
                            var the_txt_to_confirm_val = $('#' + confirm_control_txt_field_id_val).val();
                            if(the_txt_val != the_txt_to_confirm_val) {
                                var the_control_fields_arr_to_confirm = $.get_control_fields_arr_from_form_arr_field_id(the_form_arr, confirm_control_txt_field_id_val);
                                var the_field_name_to_confirm_val = the_control_fields_arr_to_confirm.the_field_name;
                                error_msg_val = validation_str_confirm_val + ' ' + the_field_name_to_confirm_val + ' ' + validation_str_confirm_and_val + ' ' + the_field_name_val;
                            }
                        }
                    }
                    if(error_msg_val == '') {
                        var the_submit_form_fields_arr = {};
                        the_submit_form_fields_arr.the_control_type = the_control_type_val;
                        the_submit_form_fields_arr.the_field_id = the_field_id_val;
                        the_submit_form_fields_arr.the_field_name = the_field_name_val;
                        the_submit_form_fields_arr.is_email = is_email_val;
                        the_submit_form_fields_arr.the_txt = the_txt_val;
                        the_form_submit_arr.push(the_submit_form_fields_arr);
                    }
                    break;
                case 'txta':
                    var the_field_name_val = the_control_fields_arr.the_field_name;
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    var is_required_val = the_control_fields_arr.is_required;
                    var the_txta_val = $('#' + the_field_id_val).val();
                    if(is_required_val == 'yes' && $.check_if_str_value_empty(the_txta_val)) {
                        error_msg_val = the_field_name_val + ' ' + validation_str_required_val + '.';
                    }    
                    if(error_msg_val == '') {
                        var the_submit_form_fields_arr = {};
                        the_submit_form_fields_arr.the_control_type = the_control_type_val;
                        the_submit_form_fields_arr.the_field_id = the_field_id_val;
                        the_submit_form_fields_arr.the_field_name = the_field_name_val;
                        the_submit_form_fields_arr.the_txt = the_txta_val;
                        the_form_submit_arr.push(the_submit_form_fields_arr);
                    }
                    break;
                case 'ddl':
                    var the_field_name_val = the_control_fields_arr.the_field_name;
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    var is_required_val = the_control_fields_arr.is_required;
                    var the_ddl_val = $('#' + the_field_id_val).val();
                    if(!$('#' + the_field_id_val).is(":visible")) {
                        the_ddl_val = '';
                    } else {
                        if(is_required_val == 'yes') {
                            if($.ddl_validate_check_if_selected_value_is_not_selected_indicator(the_control_fields_arr, the_ddl_val)) {
                                error_msg_val = the_field_name_val + ' ' + validation_str_required_val + '.';
                            }    
                        }    
                    }
                    if(error_msg_val == '') {
                        var the_submit_form_fields_arr = {};
                        the_submit_form_fields_arr.the_control_type = the_control_type_val;
                        the_submit_form_fields_arr.the_field_id = the_field_id_val;
                        the_submit_form_fields_arr.the_field_name = the_field_name_val;
                        the_submit_form_fields_arr.the_txt = the_ddl_val;
                        the_form_submit_arr.push(the_submit_form_fields_arr);
                    }
                    break;
                case 'cbx':
                    var the_field_name_val = the_control_fields_arr.the_field_name;
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    var the_saved_data_values_separator_val = the_control_fields_arr.saved_data_values_separator;
                    var is_required_val = the_control_fields_arr.is_required;
                    var items_arr = the_control_fields_arr.items_arr;
                    for (var j = 0; j < items_arr.length; j++) {
                        if(error_msg_val == '') {
                            var item_arr = items_arr[j];
                            if(item_arr.must_be_checked == 'yes') {
                                var the_cbx_id_str = 'cbx' + the_field_id_val + '_' + (j+1);
                                if(!$('#' + the_cbx_id_str).prop('checked')) {
                                    error_msg_val = item_arr.must_be_checked_error_msg;        
                                    $('#' + the_cbx_id_str).closest('.div-cbx-set-container-cell').css('border', '1px solid ' + the_form_vars_arr.form_default_required_field_font_colour);
                                }    
                            }    
                        }
                    };
                    var the_cbxs_checked_values_arr;
                    if(error_msg_val == '') {
                        the_cbxs_checked_values_arr = new Array();
                        $('.' + the_field_id_val).each(function() {
                            if($(this).prop('checked')) {
                                the_cbxs_checked_values_arr.push($(this).val());
                            }
                        });
                        if(is_required_val == 'yes') {
                            if(the_cbxs_checked_values_arr.length == 0) {
                                error_msg_val = validation_str_cbx_val + ' ' + the_field_name_val;
                            }
                        }
                    }
                    if(error_msg_val == '') {
                        var the_submit_form_fields_arr = {};
                        the_submit_form_fields_arr.the_control_type = the_control_type_val;
                        the_submit_form_fields_arr.the_field_id = the_field_id_val;
                        the_submit_form_fields_arr.saved_data_values_separator = the_saved_data_values_separator_val;
                        the_submit_form_fields_arr.the_field_name = the_field_name_val;
                        the_submit_form_fields_arr.the_cbxs_checked_values_arr = the_cbxs_checked_values_arr;
                        the_form_submit_arr.push(the_submit_form_fields_arr);
                    }
                    break;
                case 'rbtn':
                    var the_field_name_val = the_control_fields_arr.the_field_name;
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    var is_required_val = the_control_fields_arr.is_required;
                    var the_checked_val = $.get_checked_value_rbtns_name(the_field_id_val);
                    if(is_required_val == 'yes') {
                        if(the_checked_val == '') {
                            error_msg_val = the_field_name_val + ' ' + validation_str_required_val + '.';
                        }
                    }
                    if(error_msg_val == '') {
                        var the_submit_form_fields_arr = {};
                        the_submit_form_fields_arr.the_control_type = the_control_type_val;
                        the_submit_form_fields_arr.the_field_id = the_field_id_val;
                        the_submit_form_fields_arr.the_field_name = the_field_name_val;
                        the_submit_form_fields_arr.the_checked_val = the_checked_val;
                        the_form_submit_arr.push(the_submit_form_fields_arr);
                    }
                    break;
                case 'recaptcha':
                    break;
            }
        }
        the_validate_return_arr.error_msg_val = error_msg_val;
        the_validate_return_arr.the_form_submit_arr = the_form_submit_arr;
        return the_validate_return_arr;
    }
    $.post_submitted_validated_form_public = 
    function post_submitted_validated_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr, the_form_submit_arr) {
        var is_in_preview_mode_val = $.is_form_in_preview_mode(the_form_post_id_val);
        //submit form
        var save_the_form_val = false;
        if(the_form_vars_arr.form_submit_save_submission == 'yes') {
            save_the_form_val = true;
        }
        var send_email_val = false;
        if(the_form_vars_arr.form_submit_send_email == 'yes') {
            send_email_val = true;
        }
        if(!(the_form_submit_arr.length > 0)) {
            save_the_form_val = false;
            send_email_val = false;       
        }
        if(save_the_form_val || send_email_val) {
            var ajax_vars_arr = $.get_ajax_vars_public_admin_arr(the_form_post_id_val);
            $('#div-wp-any-form-msg_' + the_form_post_id_val).html(the_form_vars_arr.form_messages_contacting_server);
            if(the_form_vars_arr.form_submit_scroll_to_msg == 'yes') {
                $.scroll_to_element('#div-wp-any-form-msg_' + the_form_post_id_val, 35);     
            }
            $.ajax({ 
                type : 'POST', 
                url : ajax_vars_arr.the_ajax_url_val,  
                dataType : 'json', 
                data: { action : ajax_vars_arr.the_ajax_action_val, cmd: 'submit-form', the_form_post_id: the_form_post_id_val,  the_form_submit_arr: the_form_submit_arr, is_in_preview_mode: is_in_preview_mode_val, the_form_vars_arr: the_form_vars_arr }, 
                success : function(data) {
                    if (!data.error) {
                        var the_return_msg_val = data.return_msg;
                        if(data.return_msg == 'ok') {
                            $('#div-wp-any-form-msg_' + the_form_post_id_val).html(the_form_vars_arr.form_messages_success);
                            if(the_form_vars_arr.form_submit_scroll_to_msg == 'yes') {
                                $.scroll_to_element('#div-wp-any-form-msg_' + the_form_post_id_val, 35);     
                            }
                            the_form_vars_arr = data.the_form_vars_arr;
                            $.reset_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr, true, false);    
                        } else {
                            $.clear_active_form_msg_time_out('#div-wp-any-form-msg_' + the_form_post_id_val);
                            $('#div-wp-any-form-msg_' + the_form_post_id_val).html(the_return_msg_val);
                        }
                    } else {
                        $.change_form_msg_with_change_to_msg_time_out('#div-wp-any-form-msg_' + the_form_post_id_val, server_error_msg_val, '', 5000);
                    }
                } 
            });
        } else {
            $('#div-wp-any-form-msg_' + the_form_post_id_val).html(form_validated_ok_str_val);    
            if(the_form_vars_arr.form_submit_scroll_to_msg == 'yes') {
                $.scroll_to_element('#div-wp-any-form-msg_' + the_form_post_id_val, 35);     
            }
            $.reset_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr, true, false);
        }
    }
    $.reset_form_public = 
    function reset_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr, from_submit_action, force_revert_required_fields_msg) {
        var is_in_preview_mode_val = $.is_form_in_preview_mode(the_form_post_id_val);
        var save_the_form_val = false;
        if(the_form_vars_arr.form_submit_save_submission == 'yes') {
            save_the_form_val = true;
        }
        if(the_form_vars_arr.form_submit_revert_to_required_msg == 'yes' || force_revert_required_fields_msg) {
            $.clear_active_form_msg_time_out('#div-wp-any-form-msg_' + the_form_post_id_val);
            if(from_submit_action) {
                var the_active_time_outs_arr = {};
                the_active_time_outs_arr.the_msg_container_id_class_str = '#div-wp-any-form-msg_' + the_form_post_id_val;
                the_active_time_outs_arr.the_active_time_out = setTimeout(function() { 
                    $.init_required_fields_msg(the_form_post_id_val, the_form_vars_arr);    
                }, 8500);
                the_active_time_outs_container_arr.push(the_active_time_outs_arr);
            } else {
                $.init_required_fields_msg(the_form_post_id_val, the_form_vars_arr);
            }
        }
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type;
            switch(the_control_type_val) {
                case 'txt':
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    var the_value_val = the_control_fields_arr.the_value;
                    $('#' + the_field_id_val).val(the_value_val);
                    break;
                case 'txta':
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    var the_value_val = the_control_fields_arr.the_value;
                    $('#' + the_field_id_val).val(the_value_val);
                    break;
                case 'ddl':
                    $.perform_ddl_selected_changed_top_ddls_form_init(the_form_arr, true);
                    break;
                case 'cbx':
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    $('.' + the_field_id_val).prop('checked', false);
                    var items_arr = the_control_fields_arr.items_arr;
                    for (var j = 0; j < items_arr.length; j++) {
                        var item_arr = items_arr[j];
                        if(item_arr.initial_value == 'yes') {
                            var the_cbx_id_str = 'cbx' + the_field_id_val + '_' + (j+1);
                            $('#' + the_cbx_id_str).prop('checked', true);
                        }
                    }
                    break;
                case 'rbtn':
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    $('input[name=' + the_field_id_val + ']:checked').prop('checked', false);
                    var items_arr = the_control_fields_arr.items_arr;
                    for (var j = 0; j < items_arr.length; j++) {
                        var item_arr = items_arr[j];
                        if(item_arr.initial_value == 'yes') {
                            var the_rbtn_id_str = 'rbtn' + the_field_id_val + '_' + (j+1);
                            $('#' + the_rbtn_id_str).prop('checked', true);
                        }
                    }
                    break;
                case 'recaptcha':
                    var the_field_id_val = the_control_fields_arr.the_field_id;
                    var the_form_recaptcha_control_arr = $.get_the_recaptcha_controls_arr_from_form_recaptcha_control_arr(the_field_id_val);
                    var the_widget_id_val = the_form_recaptcha_control_arr.the_widget_id;
                    grecaptcha.reset(the_widget_id_val);
                    break;
            }
        }
        $.apply_style_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr);    
    }
    $.check_forms_container_arr_for_duplicate_forms =
    function check_forms_container_arr_for_duplicate_forms() {
        var the_forms_post_id_val_arr = new Array();
        for (var i = 0; i < the_forms_container_arr.length; i++) {
            var the_form_container_arr = the_forms_container_arr[i];
            var the_form_post_id_val = the_form_container_arr.the_post_id;
            if($.inArray(the_form_post_id_val, the_forms_post_id_val_arr) !== -1) {
                return true;
            }
            the_forms_post_id_val_arr.push(the_form_post_id_val);
        }
        return false;
    }
    /* Functions End */
    jQuery(window).load(function() {
        if($.check_forms_container_arr_for_duplicate_forms()) {
            alert(duplicate_form_error_msg_val);
        } else {
            $.create_the_recaptcha_controls_arr_and_init();
            for (var i = 0; i < the_forms_container_arr.length; i++) {
                var the_form_container_arr = the_forms_container_arr[i];
                var the_form_post_id_val = the_form_container_arr.the_post_id;
                var the_form_arr = the_form_container_arr.the_form_arr;
                var the_form_vars_arr = the_form_container_arr.the_form_vars_arr;
                var the_form_container_class_id_str = '#div-wp-any-form-container_' + the_form_post_id_val;     
                $(the_form_container_class_id_str).css('display', 'block');
                if(the_form_vars_arr.pop_up_form_is_pop_up == 'yes') {
                    $.init_pop_up_form_and_apply_style_on_open(the_form_post_id_val, the_form_vars_arr.pop_up_form_link_type);
                } else {
                    $.init_control_active_js_behaviours_on_load_public(the_form_post_id_val, the_form_arr, the_form_vars_arr);
                    $.apply_style_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr); 
                    $(window).bind('resize orientationchange', function() {
                        $.apply_style_form_public_resize_orientationchange_all_non_pop_up_forms();
                    });   
                }
            }
        }
        $.init_numbers_only('.is_numeric'); 
    });
});