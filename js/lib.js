var the_active_time_outs_container_arr = new Array();

jQuery(function ($) {
    /* Functions Start */
    $.setwatermark =
    function setwatermark(idorclassstr, watermarkstr) {
        if ($(idorclassstr).val() == "") {
            $(idorclassstr).val(watermarkstr);
        }
        $(idorclassstr).focus(function () {
            if (this.value == watermarkstr) {
                this.value = "";
            }
        }).blur(function () {
            if (this.value == "") {
                this.value = watermarkstr;
            }
        });
    }
    $.webovalidEmail =
    function webovalidEmail(email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      if(emailReg.test(email)) {
        return true;
      } else {
        return false;
      }
    }
    $.webovalidNumber =
    function webovalidNumber(number) {
    	return (!(isNaN(number)))
    }
    $.change_form_msg_with_change_to_msg_time_out =
    function change_form_msg_with_change_to_msg_time_out(the_msg_container_id_class_str, the_msg_html_str, the_change_to_msg_html_str, the_change_to_msg_time) {
        $(the_msg_container_id_class_str).html(the_msg_html_str);
        var the_active_time_out = setTimeout(function() { 
            $(the_msg_container_id_class_str).html(the_change_to_msg_html_str);
        }, the_change_to_msg_time);   
        var the_active_time_outs_arr = {};
        the_active_time_outs_arr.the_msg_container_id_class_str = the_msg_container_id_class_str;
        the_active_time_outs_arr.the_active_time_out = the_active_time_out;
        the_active_time_outs_container_arr.push(the_active_time_outs_arr);
    }
    $.clear_active_form_msg_time_out =
    function clear_active_form_msg_time_out(the_msg_container_id_class_str) {
        var the_arr_index_to_splice_val = '-1';
        for (var i = 0; i < the_active_time_outs_container_arr.length; i++) {
            var the_active_time_outs_arr = the_active_time_outs_container_arr[i];
            if(the_active_time_outs_arr.the_msg_container_id_class_str == the_msg_container_id_class_str) {
                if (the_active_time_outs_arr.the_active_time_out) clearTimeout(the_active_time_outs_arr.the_active_time_out);
                the_arr_index_to_splice_val = i;
            }
        };
        if(the_arr_index_to_splice_val != '-1') {
            the_active_time_outs_container_arr.splice(the_arr_index_to_splice_val, 1);
        }
    }
    $.webodialogmsg =
    function webodialogmsg(dialogidstr, dialogmsgidstr, titlestr, msgstr, heightstr, widthstr) {
    	$('#' + dialogmsgidstr).html(msgstr);
    	$('#' + dialogidstr).dialog({ title: titlestr, height: heightstr, width: widthstr, modal: true, dialogClass: 'wp-dialog',
    		buttons: { 
    			'Close' : function () { 
    				$(this).dialog('close');
    			}
    		}
    	});
    }
    $.webogetquerystrbyname =
    function webogetquerystrbyname(name) {
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regexS = "[\\?&]" + name + "=([^&#]*)";
        var regex = new RegExp(regexS);
        var results = regex.exec(window.location.search);
        if (results == null)
            return "-1";
        else
            return decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    $.scroll_to_element =
    function scroll_to_element(element_id_or_class, offset_top_minus_val) {
      $('html, body').animate({
            scrollTop: $(element_id_or_class).offset().top - offset_top_minus_val
        }, 1500);
    }
    $.init_numbers_only = 
    function init_numbers_only(the_class_or_id_str) {
        $(the_class_or_id_str).unbind('keyup');
        $(the_class_or_id_str).keyup(function () { 
            this.value = this.value.replace(/[^0-9\.]/g,'');
        }); 
    }
    $.init_css_class_txt = 
    function init_css_class_txt(the_class_or_id_str) {
        $(the_class_or_id_str).unbind('keyup');
        $(the_class_or_id_str).keyup(function () { 
            this.value = this.value.replace(/[^A-Za-z0-9-_ ]/g, '');
        }); 
    }
    $.get_yes_no_value_from_cbx =
    function get_yes_no_value_from_cbx(the_cbx_id_class_str) {
        var the_yes_no_val = 'no';
        if($.is_cbx_checked(the_cbx_id_class_str)) {
            the_yes_no_val = 'yes';
        }
        return the_yes_no_val;
    }
    $.is_cbx_checked =
    function is_cbx_checked(the_cbx_id_class_str) {
        if($(the_cbx_id_class_str).prop('checked')) {
            return true;
        } else {
            return false;
        }
    }
    $.get_checked_value_rbtns_name =
    function get_checked_value_rbtns_name(the_rbtn_name_str) {
        var the_checked_val = $('input[name=' + the_rbtn_name_str + ']:checked').val();
        if (the_checked_val === undefined) {
            the_checked_val = '';
        }
        return the_checked_val;
    }
    $.show_msg_overlay_help =
    function show_msg_overlay_help(html_str) {
        $('.div-msg-overlay').remove();                         
        $("body").append("<div class='div-msg-overlay' >" + html_str + "<div class='div-msg-overlay-close-btn' ><a class='acmd-msg-overlay-close' href='javascript:void(0);' >" + WPAnyFormAdminJSO.form_builder_help_msg_close_text + "</a></div></div>");
        var the_window_width = $(window).width();
        var the_div_msg_overlay_w = $('.div-msg-overlay').width();
        var the_div_msg_overlay_left = (+the_window_width - +the_div_msg_overlay_w) - 30;
        $.position_element_center_screen('.div-msg-overlay', true, false);
        $('.div-msg-overlay').css("left", the_div_msg_overlay_left + "px").fadeIn("fast");
    }
    $.show_msg_overlay_center =
    function show_msg_overlay_center(html_str, left_btn_html_str, close_cmd_txt) {
        $('.div-msg-overlay').remove();                         
        var html_str_to_append = "<div class='div-msg-overlay' >" + html_str;
        if(left_btn_html_str != '') {
            html_str_to_append += "<div class='div-msg-overlay-left-btn' >" + left_btn_html_str + "</div>";
        }
        html_str_to_append += "<div class='div-msg-overlay-close-btn' ><a class='acmd-msg-overlay-close' href='javascript:void(0);' >" + close_cmd_txt + "</a></div></div>";
        $("body").append(html_str_to_append);
        if(left_btn_html_str != '') {
            $('.div-msg-overlay').on('click', '.acmd-admin-item-set', function() {
                var the_a_id_str = $(this).attr('id');
                var str_arr_a = the_a_id_str.split('_');
                var the_cmd_str = str_arr_a[1];
                switch(the_cmd_str) {
                    case 'confirmdelete':
                        $.confirm_delete_admin_ddl_item_set();  
                        break;
                }
            });
        }
        $.position_element_center_screen('.div-msg-overlay', true, true);
        $('.div-msg-overlay').fadeIn("fast");
    }
    $.init_msg_overlay_close_btn =
    function init_msg_overlay_close_btn() {
        $(document).on('click', '.acmd-msg-overlay-close', function() {
            $('.div-msg-overlay').fadeOut();
            $('.div-msg-overlay').remove();
        });
    }
    $.position_element_center_screen =
    function position_element_center_screen(the_element_id_class_str, position_top, position_left) {
        $(the_element_id_class_str).css("position","absolute");
        if(position_top) {
            $(the_element_id_class_str).css("top", Math.max(0, (($(window).height() - $(the_element_id_class_str).outerHeight()) / 2) + $(window).scrollTop()) + "px");    
        }
        if(position_left) {
            $(the_element_id_class_str).css("left", Math.max(0, (($(window).width() - $(the_element_id_class_str).outerWidth()) / 2) + $(window).scrollLeft()) + "px");    
        }
    }
    $.replace_ddl_options = 
    function replace_ddl_options(the_ddl_id_val, ddl_options_arr) {
        var $option;
        $('#' + the_ddl_id_val).empty();
        $.each(ddl_options_arr, function(index, option) {
            $option = $("<option></option>")
                .attr("value", option.value)
                .text($.webo_html_unescape(option.text));
            $('#' + the_ddl_id_val).append($option);
        });
        if(!$('#' + the_ddl_id_val).is(":visible")) {
            $('#' + the_ddl_id_val).css('display', 'block');  
            if ($('#' + the_ddl_id_val).closest('.div-wp-any-form-cell').find('.span-required-field-custom').length > 0) { 
                $('#' + the_ddl_id_val).closest('.div-wp-any-form-cell').find('.span-required-field-custom').css('display', 'block');    
            }  
        }
    }
    $.webo_url_decode =
    function webo_url_decode(str) {
       return decodeURIComponent((str+'').replace(/\+/g, '%20'));
    }
    $.check_if_str_value_empty = 
    function check_if_str_value_empty(the_str) {
        if($.webo_remove_whitespace_trim(the_str) == '') {
            return true;
        } else {
            return false;
        }
    }
    $.webo_get_time_stamp = 
    function webo_get_time_stamp() {
        return Number(new Date());
    }
    $.webo_html_escape =
    function webo_html_escape(the_str) {
        return String(the_str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
    }
    $.webo_html_unescape =
    function webo_html_unescape(the_str) {
        return String(the_str)
                .replace(/&amp;/g, '&')
                .replace(/&quot;/g, '"')
                .replace(/&#39;/g, '\'')
                .replace(/&lt;/g, '<')
                .replace(/&gt;/g, '>');
    }
    $.webo_sort_user_input_string =
    function webo_sort_user_input_string(the_str) {
        return $.webo_html_escape($.webo_remove_whitespace_trim(the_str));
    }
    $.init_no_special_chars = 
    function init_no_special_chars(the_class_or_id_str) {
        $(the_class_or_id_str).unbind('keyup');
        $(the_class_or_id_str).keyup(function () { 
            var the_str = this.value;
            this.value = $.webo_html_remove_special_chars(the_str);
        }); 
    }
    $.webo_html_remove_special_chars =
    function webo_html_remove_special_chars(the_str) {
        return String(the_str)
                .replace(/&/g, '')
                .replace(/"/g, '')
                .replace(/'/g, '')
                .replace(/</g, '')
                .replace(/>/g, '');
    }
    $.webo_remove_whitespace_trim =
    function webo_remove_whitespace_trim(the_str) {
        return $.trim(String(the_str).replace(/\s+/g, ' '));
    }
    /* Functions End */
});