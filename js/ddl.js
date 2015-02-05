jQuery(function ($) {
    /* Functions Start */
    $.init_ddl_options_item_set_form =
    function init_ddl_options_item_set_form(ddl_item_sets_arr) {
        temp_ddl_item_sets_arr = ddl_item_sets_arr;
        if(ddl_item_sets_arr == 'none') {
            ddl_item_sets_arr = new Array();
        }
        var the_item_set_val;
        var the_selected_item_set_id_val = '';
        $('#div-ddl-options-item-sets-form input, #div-ddl-options-item-sets-form textarea').placeholder();
        $('#div-ddl-options-item-sets-form').on('change', '#ddl_item_sets', function() {
            the_item_set_val = $('#ddl_item_sets').val();    
            the_selected_item_set_id_val = the_item_set_val;
            switch(the_item_set_val) {
                case '-1':
                    $('#div-ddl-items-container').html('');
                    $('#div-ddl-item-set-name-form').fadeOut();
                    $('#acmd-admin-item-set_delete').fadeOut();
                    break;
                case 'add_new_item_set':
                    $('#div-ddl-items-container').html('');
                    $('#txt_item_set_name').val('');
                    $('#div-ddl-item-set-name-form').fadeIn();    
                    $('#btn_submit_item_set').val(WPAnyFormAdminJSO.ddl_item_set_form_btn_text_add);
                    $('#cbx_is_initial_item_set').prop('checked', false);
                    $('#acmd-admin-item-set_delete').fadeOut();
                    break;
                default:
                    $('#txt_item_set_name').val($('#ddl_item_sets option:selected').text());
                    var is_initial_val = $.get_ddl_item_set_is_initial_value_from_arr(ddl_item_sets_arr, the_selected_item_set_id_val);
                    if(is_initial_val == 'yes') {
                        $('#cbx_is_initial_item_set').prop('checked', true);
                    } else {
                        $('#cbx_is_initial_item_set').prop('checked', false);
                    }
                    $.get_ddl_items_html(ddl_item_sets_arr, the_selected_item_set_id_val);
                    $('#div-ddl-item-set-name-form').fadeIn();
                    $('#btn_submit_item_set').val(WPAnyFormAdminJSO.ddl_item_set_form_btn_text_update);
                    $('#acmd-admin-item-set_delete').fadeIn();
                    break;
            }
        });
        $('#div-ddl-options-item-sets-form').on('click', '#btn_submit_item_set', function() {
            $('#div-ddl-item-sets-form-msg').html('');
            $.clear_active_form_msg_time_out('#div-ddl-item-sets-form-msg');
            var the_item_set_name_val = $.webo_sort_user_input_string($('#txt_item_set_name').val());
            if($.check_if_str_value_empty(the_item_set_name_val)) {
                $.change_form_msg_with_change_to_msg_time_out('#div-ddl-item-sets-form-msg', WPAnyFormAdminJSO.ddl_item_set_form_name_required_msg, '', 5000);
            } else {
                the_selected_item_set_id_val = $('#ddl_item_sets').val();
                switch(the_selected_item_set_id_val) {
                    case '-1':
                        return false;
                        break;
                    case 'add_new_item_set':
                        var ddl_item_set_arr = {};
                        the_selected_item_set_id_val = $('#hval-new-ddl-item-set-id').val();
                        ddl_item_set_arr.item_set_id = the_selected_item_set_id_val;
                        ddl_item_set_arr.item_set_name = the_item_set_name_val;
                        ddl_item_set_arr.is_initial = $.get_yes_no_value_from_cbx('#cbx_is_initial_item_set');
                        ddl_item_set_arr.items_arr = 'none';
                        ddl_item_sets_arr.push(ddl_item_set_arr);
                        temp_ddl_item_sets_arr = ddl_item_sets_arr;
                        $.get_ddl_item_sets_html(ddl_item_sets_arr, the_selected_item_set_id_val);
                        $.get_ddl_items_html(ddl_item_sets_arr, the_selected_item_set_id_val);
                        $.get_initial_selected_value_ddl_html(ddl_item_sets_arr);
                        $('#btn_submit_item_set').val(WPAnyFormAdminJSO.ddl_item_set_form_btn_text_update);
                        $('#acmd-admin-item-set_delete').fadeIn();
                        break;
                    default:
                        var ddl_item_set_arr = $.get_ddl_item_set_arr_from_ddl_item_sets_arr(ddl_item_sets_arr, the_selected_item_set_id_val);
                        ddl_item_set_arr.item_set_name = the_item_set_name_val;
                        ddl_item_set_arr.is_initial = $.get_yes_no_value_from_cbx('#cbx_is_initial_item_set');
                        $.update_ddl_item_set_arr_in_ddl_item_sets_arr(ddl_item_sets_arr, the_selected_item_set_id_val, ddl_item_set_arr);
                        $.get_ddl_item_sets_html(ddl_item_sets_arr, the_selected_item_set_id_val);
                        $.get_initial_selected_value_ddl_html(ddl_item_sets_arr);
                        $.change_form_msg_with_change_to_msg_time_out('#div-ddl-item-sets-form-msg', WPAnyFormAdminJSO.ddl_item_set_form_updated_msg, '', 5000);
                        break;
                }
            }
        });
        $('#div-ddl-options-item-sets-form').on('click', '.acmd-admin-item-set', function() {
            var the_a_id_str = $(this).attr('id');
            var str_arr_a = the_a_id_str.split('_');
            var the_cmd_str = str_arr_a[1];
            switch(the_cmd_str) {
                case 'delete':
                    var confirm_delete_html_str = "<p>" + WPAnyFormAdminJSO.ddl_item_set_confirm_delete_msg + "</p>";
                    var confirm_delete_btn_html_str = "<a id='acmd-admin-item-set_confirmdelete' class='acmd-admin-item-set' href='javascript:void(0);' >" + WPAnyFormAdminJSO.ddl_item_set_confirm_delete_action_text + "</a>";
                    $.show_msg_overlay_center(confirm_delete_html_str, confirm_delete_btn_html_str, WPAnyFormAdminJSO.ddl_item_set_confirm_cancel_delete_action_text);
                    break;
            }
        });
        $.confirm_delete_admin_ddl_item_set = 
        function confirm_delete_admin_ddl_item_set() {
            var the_selected_item_set_id_val = $('#ddl_item_sets').val();
            for (var i = 0; i < ddl_item_sets_arr.length; i++) {
                var ddl_item_set_arr = ddl_item_sets_arr[i];
                if(ddl_item_set_arr.item_set_id == the_selected_item_set_id_val) {
                    ddl_item_sets_arr.splice(i, 1);
                    temp_ddl_item_sets_arr = ddl_item_sets_arr;
                    $.get_ddl_item_sets_html(ddl_item_sets_arr, '-1');
                    $.get_initial_selected_value_ddl_html(ddl_item_sets_arr);
                    $('#div-ddl-items-container').html('');
                    $('#div-ddl-item-set-name-form').fadeOut();
                    $('#acmd-admin-item-set_delete').fadeOut();
                    $('.div-msg-overlay').remove();
                    return false;
                }
            }
        }
        $('#div-ddl-options-item-sets-form').on('change', '#cbx_is_initial_item_set', function() {
            var is_initial_val = $.get_yes_no_value_from_cbx('#cbx_is_initial_item_set');
            if(is_initial_val == 'yes') {
                var the_selected_item_set_id_val = $('#ddl_item_sets').val();
                for (var i = 0; i < ddl_item_sets_arr.length; i++) {
                    var ddl_item_set_arr = ddl_item_sets_arr[i];
                    if(ddl_item_set_arr.item_set_id != the_selected_item_set_id_val) {
                        if(ddl_item_set_arr.is_initial == 'yes') {
                            $(this).prop('checked', false);
                            $('#div-ddl-item-sets-form-msg').html('');
                            $.clear_active_form_msg_time_out('#div-ddl-item-sets-form-msg');
                            $.change_form_msg_with_change_to_msg_time_out('#div-ddl-item-sets-form-msg', WPAnyFormAdminJSO.ddl_item_set_initial_only_once_error_msg, '', 5000);
                            return false;
                        }        
                    }
                }
            }
            return false;
        });
        $('#div-ddl-options-item-sets-form').on('click', '#btn_submit_item_form', function() {
            $('#div-ddl-item-sets-form-msg').html('');
            $.clear_active_form_msg_time_out('#div-ddl-item-sets-form-msg');
            the_selected_item_set_id_val = $('#ddl_item_sets').val();
            switch(the_selected_item_set_id_val) {
                case '-1': case 'add_new_item_set':
                    return false;
                    break;
                default:
                    var item_description_val = $.webo_sort_user_input_string($('#txt_item_description').val());
                    var item_value_val = $.webo_sort_user_input_string($('#txt_item_value').val());
                    if($.check_if_str_value_empty(item_description_val) || $.check_if_str_value_empty(item_value_val)) {
                        $.change_form_msg_with_change_to_msg_time_out('#div-ddl-item-sets-form-msg', WPAnyFormAdminJSO.ddl_item_desc_value_required_error_msg, '', 5000);
                    } else {
                        var is_not_selected_indicator_val = $.get_yes_no_value_from_cbx('#cbx_is_not_selected_indicator');
                        var item_sub_option_val = $('#ddl_item_sub_options').val();
                        var ddl_item_set_arr = $.get_ddl_item_set_arr_from_ddl_item_sets_arr(ddl_item_sets_arr, the_selected_item_set_id_val);
                        var items_arr = ddl_item_set_arr.items_arr;
                        var the_edit_arr_index_val = $('#hval-editing-item').val();
                        if(the_edit_arr_index_val == '-1') {
                            var item_arr = {};
                            item_arr.item_description = item_description_val;
                            item_arr.item_value = item_value_val;
                            item_arr.is_not_selected_indicator = is_not_selected_indicator_val;
                            item_arr.item_sub_option = item_sub_option_val;
                            if(items_arr == 'none') {
                                items_arr = new Array();
                            }
                            items_arr.push(item_arr);
                            ddl_item_set_arr.items_arr = items_arr;
                        } else {
                            var item_arr = items_arr[the_edit_arr_index_val];        
                            item_arr.item_description = item_description_val;
                            item_arr.item_value = item_value_val;
                            item_arr.is_not_selected_indicator = is_not_selected_indicator_val;
                            item_arr.item_sub_option = item_sub_option_val;
                            ddl_item_set_arr.items_arr = items_arr;
                            $('#hval-editing-item').val('-1');
                        }
                        $.update_ddl_item_set_arr_in_ddl_item_sets_arr(ddl_item_sets_arr, the_selected_item_set_id_val, ddl_item_set_arr);
                        $.get_ddl_items_html(ddl_item_sets_arr, the_selected_item_set_id_val);
                        $.get_initial_selected_value_ddl_html(ddl_item_sets_arr);
                    }
                    break;
            }
        });
        $('#div-ddl-options-item-sets-form').on('click', '#btn_cancel_edit_item_form', function() {
            $('#hval-editing-item').val('-1');
            $('#txt_item_description').val('');
            $('#txt_item_value').val('');
            $('#ddl_item_sub_options').val('none');
            $('#cbx_is_not_selected_indicator').prop('checked', false);
            $('#div-ddl-item-set-form-heading').html(WPAnyFormAdminJSO.ddl_item_form_heading_add);
            $('#btn_submit_item_form').val(WPAnyFormAdminJSO.ddl_item_form_btn_text_add);
            $('#btn_cancel_edit_item_form').fadeOut();
        });
        $('#div-ddl-options-item-sets-form').on('click', '.acmd-admin-items-arr', function() {
            var the_a_id_str = $(this).attr('id');
            var str_arr_a = the_a_id_str.split('_');
            var the_cmd_str = str_arr_a[1];
            var the_i_val = str_arr_a[2];
            var the_edit_arr_index_val = +the_i_val - 1;
            the_selected_item_set_id_val = $('#ddl_item_sets').val();
            var ddl_item_set_arr = $.get_ddl_item_set_arr_from_ddl_item_sets_arr(ddl_item_sets_arr, the_selected_item_set_id_val);
            var items_arr = ddl_item_set_arr.items_arr;
            switch(the_cmd_str) {
                case 'edit':
                    $('#hval-editing-item').val(the_edit_arr_index_val);
                    var item_arr = items_arr[the_edit_arr_index_val];
                    $('#txt_item_description').val($.webo_html_unescape(item_arr.item_description));
                    $('#txt_item_value').val($.webo_html_unescape(item_arr.item_value));
                    $('#ddl_item_sub_options').val(item_arr.item_sub_option);
                    if(item_arr.is_not_selected_indicator == 'yes') {
                        $('#cbx_is_not_selected_indicator').prop('checked', true);
                    } else {
                        $('#cbx_is_not_selected_indicator').prop('checked', false);
                    }
                    $('#div-ddl-item-set-form-heading').html(WPAnyFormAdminJSO.ddl_item_form_heading_update);
                    $('#btn_submit_item_form').val(WPAnyFormAdminJSO.ddl_item_form_btn_text_update);
                    $('#btn_cancel_edit_item_form').fadeIn();
                    break;
                case 'delete':
                    items_arr.splice(the_edit_arr_index_val, 1);
                    if(items_arr.length == 0) { 
                        items_arr = 'none';
                    }
                    ddl_item_set_arr.items_arr = items_arr;
                    $.update_ddl_item_set_arr_in_ddl_item_sets_arr(ddl_item_sets_arr, the_selected_item_set_id_val, ddl_item_set_arr);
                    $.get_ddl_items_html(ddl_item_sets_arr, the_selected_item_set_id_val);    
                    $.get_initial_selected_value_ddl_html(ddl_item_sets_arr);
                    $('#hval-editing-item').val('-1');
                    break;
            }
        });
    }
    $.get_ddl_item_sets_html =
    function get_ddl_item_sets_html(ddl_item_sets_arr, the_selected_item_set_id_val) {
        $.ajax({ 
            type : 'POST', 
            url : WPAnyFormAdminJSO.ajaxurl,  
            dataType : 'json', 
            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-ddl-options-ddl-item-sets-html', ddl_item_sets_arr: ddl_item_sets_arr, the_selected_item_set_id: the_selected_item_set_id_val }, 
            success : function(data) {
                if (!data.error) {
                    $('#ddl_item_sets-container').html(data.html_str);
                } else {
                    $('#div-ddl-item-sets-form-msg').html(server_error_msg_val);
                }
            } 
        });
    }
    $.get_ddl_item_set_is_initial_value_from_arr =
    function get_ddl_item_set_is_initial_value_from_arr(ddl_item_sets_arr, the_selected_item_set_id_val) {
        var ddl_item_set_arr = $.get_ddl_item_set_arr_from_ddl_item_sets_arr(ddl_item_sets_arr, the_selected_item_set_id_val);
        return ddl_item_set_arr.is_initial;
    }
    $.get_ddl_items_html =
    function get_ddl_items_html(ddl_item_sets_arr, the_selected_item_set_id_val) {
        var the_field_id_val = $('#hval-field-id').val();
        $.ajax({ 
            type : 'POST', 
            url : WPAnyFormAdminJSO.ajaxurl,  
            dataType : 'json', 
            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-ddl-items-html', the_field_id: the_field_id_val, the_form_arr: the_form_arr, ddl_item_sets_arr: ddl_item_sets_arr, the_selected_item_set_id: the_selected_item_set_id_val }, 
            success : function(data) {
                if (!data.error) {
                    $('#div-ddl-items-container').html(data.html_str);
                    $('#ul-admin-items-arr').sortable({ 
                        update: function(event, ui) {
                            var the_order_val = $(this).sortable('toArray').toString();
                            $.change_order_ddl_item_set_arr(the_order_val, ddl_item_sets_arr, the_selected_item_set_id_val);
                        }
                    }).disableSelection();
                } else {
                    $('#div-ddl-item-sets-form-msg').html(server_error_msg_val);
                }
            } 
        });
    }
    $.get_initial_selected_value_ddl_html =
    function get_initial_selected_value_ddl_html(ddl_item_sets_arr) {
        var the_initial_selected_value = $('#ddl_initial_selected_value').val();
        $.ajax({ 
            type : 'POST', 
            url : WPAnyFormAdminJSO.ajaxurl,  
            dataType : 'json', 
            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-initial-selected-value-ddl-html', ddl_item_sets_arr: ddl_item_sets_arr, the_initial_selected_value: the_initial_selected_value }, 
            success : function(data) {
                if (!data.error) {
                    $('#div-any-form-cell-ddl_initial_selected_value').html(data.html_str);
                } else {
                    $('#div-ddl-item-sets-form-msg').html(server_error_msg_val);
                }
            } 
        });
    }
    $.change_order_ddl_item_set_arr =
    function change_order_ddl_item_set_arr(the_order_val, ddl_item_sets_arr, the_selected_item_set_id_val) {
        var ddl_item_set_arr = $.get_ddl_item_set_arr_from_ddl_item_sets_arr(ddl_item_sets_arr, the_selected_item_set_id_val);
        var items_arr = ddl_item_set_arr.items_arr;
        var new_items_arr = new Array();
        /* ul-admin-ddl-set-item_0,ul-admin-ddl-set-item_2,ul-admin-ddl-set-item_1 */
        var the_order_arr = the_order_val.split(",");
        for (var i = 0; i < the_order_arr.length; i++) {
            var the_li_id_str = the_order_arr[i];
            var str_arr_li = the_li_id_str.split('_');
            var the_order_arr_index_val = str_arr_li[1];
            new_items_arr[i] = items_arr[the_order_arr_index_val];
        };
        ddl_item_set_arr.items_arr = new_items_arr;
        $.update_ddl_item_set_arr_in_ddl_item_sets_arr(ddl_item_sets_arr, the_selected_item_set_id_val, ddl_item_set_arr);
        $.get_ddl_items_html(ddl_item_sets_arr, the_selected_item_set_id_val);    
        return false;
    }
    $.get_ddl_item_set_arr_from_ddl_item_sets_arr =
    function get_ddl_item_set_arr_from_ddl_item_sets_arr(ddl_item_sets_arr, the_selected_item_set_id_val) {
        for (var i = 0; i < ddl_item_sets_arr.length; i++) {
            var ddl_item_set_arr = ddl_item_sets_arr[i];
            if(ddl_item_set_arr.item_set_id == the_selected_item_set_id_val) {
                return ddl_item_set_arr;
            }
        };    
        return false;
    }
    $.update_ddl_item_set_arr_in_ddl_item_sets_arr =
    function update_ddl_item_set_arr_in_ddl_item_sets_arr(ddl_item_sets_arr, the_selected_item_set_id_val, new_ddl_item_set_arr) {
        for (var i = 0; i < ddl_item_sets_arr.length; i++) {
            var ddl_item_set_arr = ddl_item_sets_arr[i];
            if(ddl_item_set_arr.item_set_id == the_selected_item_set_id_val) {
                ddl_item_sets_arr[i] = new_ddl_item_set_arr;
            }
            temp_ddl_item_sets_arr = ddl_item_sets_arr;
        };    
        return false;
    }
    /* Functions End */
});