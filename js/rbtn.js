var rbtn_live_items_arr;

jQuery(function ($) {
    /* Functions Start */
    $.init_rbtn_options_item_set_form =
    function init_rbtn_options_item_set_form(saved_items_arr) {
        $.rbtn_update_items_arr(saved_items_arr);
        var items_arr = $.rbtn_get_live_items_arr();
        $.init_rbtn_sortable_ul_admin_items_arr(items_arr);
        $('#div-rbtn-options-item-set-form').on('click', '#btn_submit_item_form', function() {
            $('#div-rbtn-options-item-set-form-msg').html('');
            $.clear_active_form_msg_time_out('#div-rbtn-options-item-set-form-msg');
            var item_description_val = $.webo_sort_user_input_string($('#txt_item_description').val());
            var item_value_val = $.webo_sort_user_input_string($('#txt_item_value').val());
            var initial_value_val = $.get_checked_value_rbtns_name('rbtn_initial_value');
            var error_msg_val = '';
            if($.check_if_str_value_empty(item_description_val) || $.check_if_str_value_empty(item_value_val)) {
                error_msg_val = WPAnyFormAdminJSO.rbtn_item_label_value_required_error_msg;
            }
            if(error_msg_val != '') {
                $.change_form_msg_with_change_to_msg_time_out('#div-rbtn-options-item-set-form-msg', error_msg_val, '', 5000);
            } else {
                var items_arr = $.rbtn_get_live_items_arr();
                var the_edit_arr_index_val = $('#hval-editing-item').val();
                if(the_edit_arr_index_val == '-1') {
                    var item_arr = {};
                    item_arr.item_description = item_description_val;
                    item_arr.item_value = item_value_val;
                    item_arr.initial_value = initial_value_val;
                    if(items_arr == 'none') {
                        items_arr = new Array();
                    }
                    items_arr.push(item_arr);
                } else {
                    var item_arr = items_arr[the_edit_arr_index_val];        
                    item_arr.item_description = item_description_val;
                    item_arr.item_value = item_value_val;
                    item_arr.initial_value = initial_value_val;
                    items_arr[the_edit_arr_index_val] = item_arr;
                }
                $.reset_rbtn_items_admin_form();
                $.rbtn_update_items_arr(items_arr);
                $.get_rbtn_items_html(items_arr);
            }
        });
        $('#div-rbtn-options-item-set-form').on('click', '#btn_cancel_edit_item_form', function() {
            $.reset_rbtn_items_admin_form();
        });
        $('#div-rbtn-options-item-set-form').on('click', '.acmd-admin-items-arr', function() {
            var the_a_id_str = $(this).attr('id');
            var str_arr_a = the_a_id_str.split('_');
            var the_cmd_str = str_arr_a[1];
            var the_i_val = str_arr_a[2];
            var the_edit_arr_index_val = +the_i_val - 1;
            var items_arr = $.rbtn_get_live_items_arr();
            switch(the_cmd_str) {
                case 'edit':
                    $('#hval-editing-item').val(the_edit_arr_index_val);
                    var item_arr = items_arr[the_edit_arr_index_val];
                    $('#txt_item_description').val($.webo_html_unescape(item_arr.item_description));
                    $('#txt_item_value').val($.webo_html_unescape(item_arr.item_value));
                    if(item_arr.initial_value == 'yes') {
                        $('#rbtn_initial_value_yes').prop('checked', true);
                    } else {
                        $('#rbtn_initial_value_no').prop('checked', true);
                    }
                    $('#div-rbtn-item-set-form-heading').html(WPAnyFormAdminJSO.rbtn_item_form_heading_update);
                    $('#btn_submit_item_form').val(WPAnyFormAdminJSO.rbtn_item_form_btn_text_update);
                    $('#btn_cancel_edit_item_form').fadeIn();
                    break;
                case 'delete':
                    items_arr.splice(the_edit_arr_index_val, 1);
                    if(items_arr.length == 0) { 
                        items_arr = 'none';
                    }
                    $.rbtn_update_items_arr(items_arr);
                    $.get_rbtn_items_html(items_arr);
                    $.reset_rbtn_items_admin_form();
                    break;
            }
        });
        $('input[type=radio][name=rbtn_initial_value]').change(function() {
            var the_edit_arr_index_val = $('#hval-editing-item').val();
            var initial_value_val = $.get_checked_value_rbtns_name('rbtn_initial_value');
            if(initial_value_val == 'yes') {
                var items_arr = $.rbtn_get_live_items_arr();
                for (var i = 0; i < items_arr.length; i++) {
                    if (i != the_edit_arr_index_val) {
                        var item_arr = items_arr[i];    
                        if(item_arr.initial_value == 'yes') {
                            $('#div-rbtn-options-item-set-form-msg').html('');
                            $.clear_active_form_msg_time_out('#div-rbtn-options-item-set-form-msg');
                            $.change_form_msg_with_change_to_msg_time_out('#div-rbtn-options-item-set-form-msg', WPAnyFormAdminJSO.rbtn_only_one_checked_error_msg, '', 5000);
                            $('#rbtn_initial_value_yes').prop('checked', false);
                        }
                    }
                };    
            }
        });
    }
    $.get_rbtn_items_html =
    function get_rbtn_items_html(items_arr) {
        $.ajax({ 
            type : 'POST', 
            url : WPAnyFormAdminJSO.ajaxurl,  
            dataType : 'json', 
            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-rbtn-items-html', items_arr: items_arr }, 
            success : function(data) {
                if (!data.error) {
                    $('#div-rbtn-items-container').html(data.html_str);
                    $.init_rbtn_sortable_ul_admin_items_arr(items_arr);
                } else {
                    $('#div-rbtn-options-item-set-form-msg').html(server_error_msg_val);
                }
            } 
        });
    }
    $.init_rbtn_sortable_ul_admin_items_arr =
    function init_rbtn_sortable_ul_admin_items_arr(items_arr) {
        $('#ul-admin-items-arr').sortable({ 
            update: function(event, ui) {
                var the_order_val = $(this).sortable('toArray').toString();
                $.change_order_rbtn_item_set_arr(the_order_val, items_arr);
            }
        }).disableSelection();
    }
    $.reset_rbtn_items_admin_form = 
    function reset_rbtn_items_admin_form() {
        $('#hval-editing-item').val('-1');
        $('#txt_item_description').val('');
        $('#txt_item_value').val('');
        $('input[name=rbtn_initial_value]').prop('checked', false);
        $('#div-rbtn-item-set-form-heading').html(WPAnyFormAdminJSO.rbtn_item_form_heading_add);
        $('#btn_submit_item_form').val(WPAnyFormAdminJSO.rbtn_item_form_btn_text_add);
        $('#btn_cancel_edit_item_form').fadeOut();    
    }
    $.change_order_rbtn_item_set_arr =
    function change_order_rbtn_item_set_arr(the_order_val) {
        var items_arr = $.rbtn_get_live_items_arr();
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
        $.rbtn_update_items_arr(items_arr);
        $.get_rbtn_items_html(items_arr);   
        $.reset_rbtn_items_admin_form();  
        return false;
    }
    $.rbtn_get_live_items_arr =
    function rbtn_get_live_items_arr() {
        return rbtn_live_items_arr;
    }
    $.rbtn_update_items_arr =
    function rbtn_update_items_arr(new_items_arr) {
        rbtn_live_items_arr = new_items_arr;
        temp_rbtn_items_arr = rbtn_live_items_arr;
        return false;
    }
    /* Functions End */
});