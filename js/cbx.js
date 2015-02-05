var live_items_arr;

jQuery(function ($) {
    /* Functions Start */
    $.init_cbx_options_item_set_form =
    function init_cbx_options_item_set_form(saved_items_arr) {
        $.update_items_arr(saved_items_arr);
        var items_arr = $.get_live_items_arr();
        $.init_sortable_ul_admin_items_arr(items_arr);
        $('#div-cbx-options-item-set-form').on('click', '#btn_submit_item_form', function() {
            $('#div-cbx-options-item-set-form-msg').html('');
            $.clear_active_form_msg_time_out('#div-cbx-options-item-set-form-msg');
            var item_description_val = $.webo_sort_user_input_string($('#txt_item_description').val());
            var item_value_val = $.webo_sort_user_input_string($('#txt_item_value').val());
            var initial_value_val = $.get_yes_no_value_from_cbx('#cbx_initial_value');
            var must_be_checked_val = $.get_yes_no_value_from_cbx('#cbx_must_be_checked');
            var must_be_checked_error_msg_val = $('#txt_must_be_checked_error_msg').val();
            var error_msg_val = '';
            if($.check_if_str_value_empty(item_description_val) || $.check_if_str_value_empty(item_value_val)) {
                error_msg_val = WPAnyFormAdminJSO.cbx_item_label_value_required_error_msg;
            }
            if(error_msg_val == '') {
                if(must_be_checked_val == 'yes') {
                    if(must_be_checked_error_msg_val == '') {
                        error_msg_val = WPAnyFormAdminJSO.cbx_item_validation_error_msg_required_msg;
                    }
                }
            }
            if(error_msg_val != '') {
                $.change_form_msg_with_change_to_msg_time_out('#div-cbx-options-item-set-form-msg', error_msg_val, '', 5000);
            } else {
                var items_arr = $.get_live_items_arr();
                var the_edit_arr_index_val = $('#hval-editing-item').val();
                if(the_edit_arr_index_val == '-1') {
                    var item_arr = {};
                    item_arr.item_description = item_description_val;
                    item_arr.item_value = item_value_val;
                    item_arr.initial_value = initial_value_val;
                    item_arr.must_be_checked = must_be_checked_val;
                    item_arr.must_be_checked_error_msg = must_be_checked_error_msg_val;
                    if(items_arr == 'none') {
                        items_arr = new Array();
                    }
                    items_arr.push(item_arr);
                } else {
                    var item_arr = items_arr[the_edit_arr_index_val];        
                    item_arr.item_description = item_description_val;
                    item_arr.item_value = item_value_val;
                    item_arr.initial_value = initial_value_val;
                    item_arr.must_be_checked = must_be_checked_val;
                    item_arr.must_be_checked_error_msg = must_be_checked_error_msg_val;
                    items_arr[the_edit_arr_index_val] = item_arr;
                }
                $.reset_cbx_items_admin_form();
                $.update_items_arr(items_arr);
                $.get_cbx_items_html(items_arr);
            }
        });
        $('#div-cbx-options-item-set-form').on('click', '#btn_cancel_edit_item_form', function() {
            $.reset_cbx_items_admin_form();
        });
        $('#div-cbx-options-item-set-form').on('click', '.acmd-admin-items-arr', function() {
            var the_a_id_str = $(this).attr('id');
            var str_arr_a = the_a_id_str.split('_');
            var the_cmd_str = str_arr_a[1];
            var the_i_val = str_arr_a[2];
            var the_edit_arr_index_val = +the_i_val - 1;
            var items_arr = $.get_live_items_arr();
            switch(the_cmd_str) {
                case 'edit':
                    $('#hval-editing-item').val(the_edit_arr_index_val);
                    var item_arr = items_arr[the_edit_arr_index_val];
                    $('#txt_item_description').val($.webo_html_unescape(item_arr.item_description));
                    $('#txt_item_value').val($.webo_html_unescape(item_arr.item_value));
                    if(item_arr.initial_value == 'yes') {
                        $('#cbx_initial_value').prop('checked', true);
                    } else {
                        $('#cbx_initial_value').prop('checked', false);
                    }
                    if(item_arr.must_be_checked == 'yes') {
                        $('#cbx_must_be_checked').prop('checked', true);
                        $('#div-any-form-row_must_be_checked_error_msg').fadeIn();  
                        $('#txt_must_be_checked_error_msg').val(item_arr.must_be_checked_error_msg);
                    } else {
                        $('#cbx_must_be_checked').prop('checked', false);
                        $('#div-any-form-row_must_be_checked_error_msg').fadeOut();  
                        $('#txt_must_be_checked_error_msg').val('');
                    }
                    $('#div-cbx-item-set-form-heading').html(WPAnyFormAdminJSO.cbx_item_form_heading_update);
                    $('#btn_submit_item_form').val(WPAnyFormAdminJSO.cbx_item_form_btn_text_update);
                    $('#btn_cancel_edit_item_form').fadeIn();
                    break;
                case 'delete':
                    items_arr.splice(the_edit_arr_index_val, 1);
                    if(items_arr.length == 0) { 
                        items_arr = 'none';
                    }
                    $.update_items_arr(items_arr);
                    $.get_cbx_items_html(items_arr);
                    $.reset_cbx_items_admin_form();
                    break;
            }
        });
        $('#div-cbx-options-item-set-form').on('change', '#cbx_must_be_checked', function() {
            var must_be_checked_val = $.get_yes_no_value_from_cbx('#cbx_must_be_checked');
            if(must_be_checked_val == 'yes') {
                $('#div-any-form-row_must_be_checked_error_msg').fadeIn();
            } else {
                $('#div-any-form-row_must_be_checked_error_msg').fadeOut();  
                $('#txt_must_be_checked_error_msg').val('');  
            }
            return false;
        });
    }
    $.get_cbx_items_html =
    function get_cbx_items_html(items_arr) {
        $.ajax({ 
            type : 'POST', 
            url : WPAnyFormAdminJSO.ajaxurl,  
            dataType : 'json', 
            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-cbx-items-html', items_arr: items_arr }, 
            success : function(data) {
                if (!data.error) {
                    $('#div-cbx-items-container').html(data.html_str);
                    $.init_sortable_ul_admin_items_arr(items_arr);
                } else {
                    $('#div-cbx-options-item-set-form-msg').html(server_error_msg_val);
                }
            } 
        });
    }
    $.init_sortable_ul_admin_items_arr =
    function init_sortable_ul_admin_items_arr(items_arr) {
        $('#ul-admin-items-arr').sortable({ 
            update: function(event, ui) {
                var the_order_val = $(this).sortable('toArray').toString();
                $.change_order_cbx_item_set_arr(the_order_val, items_arr);
            }
        }).disableSelection();
    }
    $.reset_cbx_items_admin_form = 
    function reset_cbx_items_admin_form() {
        $('#hval-editing-item').val('-1');
        $('#txt_item_description').val('');
        $('#txt_item_value').val('');
        $('#cbx_initial_value').prop('checked', false);
        $('#cbx_must_be_checked').prop('checked', false);
        $('#div-cbx-item-set-form-heading').html(WPAnyFormAdminJSO.cbx_item_form_heading_add);
        $('#btn_submit_item_form').val(WPAnyFormAdminJSO.cbx_item_form_btn_text_add);
        $('#div-any-form-row_must_be_checked_error_msg').fadeOut();    
        $('#txt_must_be_checked_error_msg').val('');
        $('#btn_cancel_edit_item_form').fadeOut();    
    }
    $.change_order_cbx_item_set_arr =
    function change_order_cbx_item_set_arr(the_order_val) {
        var items_arr = $.get_live_items_arr();
        var new_items_arr = new Array();
        /* ul-admin-ddl-set-item_0,ul-admin-ddl-set-item_2,ul-admin-ddl-set-item_1 */
        var the_order_arr = the_order_val.split(",");
        for (var i = 0; i < the_order_arr.length; i++) {
            var the_li_id_str = the_order_arr[i];
            var str_arr_li = the_li_id_str.split('_');
            var the_order_arr_index_val = str_arr_li[1];
            new_items_arr[i] = items_arr[the_order_arr_index_val];
        };
        items_arr = new_items_arr;
        $.update_items_arr(items_arr);
        $.get_cbx_items_html(items_arr);   
        $.reset_cbx_items_admin_form();  
        return false;
    }
    $.get_live_items_arr =
    function get_live_items_arr() {
        return live_items_arr;
    }
    $.update_items_arr =
    function update_items_arr(new_items_arr) {
        live_items_arr = new_items_arr;
        temp_cbx_items_arr = live_items_arr;
        return false;
    }
    /* Functions End */
});