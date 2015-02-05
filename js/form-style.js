var the_row_container_class_id_str = '.div-wp-any-form-row';
var the_cell_container_class_id_str = '.div-wp-any-form-cell';

jQuery(function ($) {
    /* Functions Start */
    $.apply_individual_elements_style_form_builder_public = 
    function apply_individual_elements_style_form_builder_public(the_form_post_id_val, the_form_arr) {
        var the_form_class_id_str = '#div-wp-any-form_' + the_form_post_id_val;
        for (var i = 0; i < the_form_arr.length; i++) {
            var the_control_fields_arr = the_form_arr[i];
            var the_control_type_val = the_control_fields_arr.the_control_type;
            var the_row_no_val = the_control_fields_arr.the_row_no_val;
            var the_cell_no_val = the_control_fields_arr.the_cell_no_val;
            switch(the_control_type_val) {
                case 'lbl':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-lbl-control').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-lbl-control').css('color', the_font_colour_val);
                    }
                    break;
                case 'txt':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' input').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' input').css('color', the_font_colour_val);
                    }
                    break;
                case 'txta':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' textarea').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' textarea').css('color', the_font_colour_val);
                    }
                    break;
                case 'ddl':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' select').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' select').css('color', the_font_colour_val);
                    }
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' select').css('height', 'auto');
                    break;
                case 'cbx':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    var use_flexi_width_val = the_control_fields_arr.use_flexi_width;
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-cbx-set-container-cell').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-cbx-set-container-cell').css('color', the_font_colour_val);
                    }
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-cbx-set-container-cell').css('height', 'auto');
                    if(use_flexi_width_val == 'yes') {
                        $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-cbx-set-container-cell').css('width', 'auto');
                        $.apply_style_flexi_width_generic(the_form_class_id_str, '#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-cbx-set-container', '.div-cbx-set-container-row', '.div-cbx-set-container-cell');    
                    }
                    break;
                case 'rbtn':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    var use_flexi_width_val = the_control_fields_arr.use_flexi_width;
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-rbtn-set-container-cell').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-rbtn-set-container-cell').css('color', the_font_colour_val);
                    }
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-rbtn-set-container-cell').css('height', 'auto');
                    if(use_flexi_width_val == 'yes') {
                        $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-rbtn-set-container-cell').css('width', 'auto');
                        $.apply_style_flexi_width_generic(the_form_class_id_str, '#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' .div-rbtn-set-container', '.div-rbtn-set-container-row', '.div-rbtn-set-container-cell');    
                    }
                    break;    
                case 'recaptcha':
                    break;
                case 'btnsubmit':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' :submit.div-wp-any-form-btnsubmit').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' :submit.div-wp-any-form-btnsubmit').css('color', the_font_colour_val);
                    }
                    var the_control_align_val = the_control_fields_arr.the_control_align;
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val).find('.div-vertical-spacing-tbl').css('float', the_control_align_val);
                    switch(the_control_align_val) {
                        case 'right':
                            if($('#div-wp-any-form-container_' + the_form_post_id_val).find('.span-required-field-custom').length > 0) {
                                $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val).find('.div-vertical-spacing-tbl').css('margin-right', '12px');
                            }
                            break;
                    }
                    break;
                case 'btnreset':
                    var the_font_size_val = the_control_fields_arr.the_font_size;
                    var the_font_weight_val = the_control_fields_arr.the_font_weight;
                    var the_font_colour_val = the_control_fields_arr.the_font_colour;
                    var the_font_colour_use_control_defined_val = the_control_fields_arr.the_font_colour_use_control_defined;
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' :submit.div-wp-any-form-btnreset').css('font-size', the_font_size_val + 'px').css('font-weight', the_font_weight_val);
                    if(the_font_colour_use_control_defined_val == 'yes') {
                        $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val + ' :submit.div-wp-any-form-btnreset').css('color', the_font_colour_val);
                    }
                    var the_control_align_val = the_control_fields_arr.the_control_align;
                    $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val).find('.div-vertical-spacing-tbl').css('float', the_control_align_val);
                    switch(the_control_align_val) {
                        case 'right':
                            if($('#div-wp-any-form-container_' + the_form_post_id_val).find('.span-required-field-custom').length > 0) {
                                $(the_form_class_id_str).find('#div-wp-any-form-cell_' + the_row_no_val + '_' + the_cell_no_val).find('.div-vertical-spacing-tbl').css('margin-right', '12px');
                            }
                            break;
                    }
                    break;
            }    
        }
    }
    $.apply_style_form_public =
    function apply_style_form_public(the_form_post_id_val, the_form_arr, the_form_vars_arr) {
        var apply_responsive = false;
        var the_form_container_width_val = $.get_the_form_container_width_val(the_form_post_id_val, the_form_vars_arr, true);
        var form_default_form_width = the_form_vars_arr.form_default_form_width;
        var pop_up_form_is_pop_up = the_form_vars_arr.pop_up_form_is_pop_up;
        if(the_form_container_width_val < 244) {
            the_form_container_width_val = 244;
        }
    	var the_form_class_id_str = '#div-wp-any-form_' + the_form_post_id_val;
        var the_form_default_layout = the_form_vars_arr.form_default_layout;
        var form_default_cells_per_row = the_form_vars_arr.form_default_cells_per_row;
        if(the_form_default_layout == 'grid' && form_default_cells_per_row == '') {
            the_form_default_layout = 'auto';
        }
        var form_default_cell_spacing = the_form_vars_arr.form_default_cell_spacing;
        var form_default_row_spacing = the_form_vars_arr.form_default_row_spacing;
        var form_default_cell_padding = the_form_vars_arr.form_default_cell_padding;
        var form_default_font_size = the_form_vars_arr.form_default_font_size;
        var form_default_font_weight = the_form_vars_arr.form_default_font_weight;
        var form_default_font_colour = the_form_vars_arr.form_default_font_colour;
        var form_default_font_colour_use_defined = the_form_vars_arr.form_default_font_colour_use_defined;
        var form_default_message_font_colour = the_form_vars_arr.form_default_message_font_colour;
        var form_default_required_field_font_colour = the_form_vars_arr.form_default_required_field_font_colour;
        var form_cell_vertical_align = the_form_vars_arr.form_cell_vertical_align;
        if(pop_up_form_is_pop_up == 'yes') {
            var pop_up_form_bg_colour = the_form_vars_arr.pop_up_form_bg_colour;
            if(pop_up_form_bg_colour == '') {
                pop_up_form_bg_colour = '#ffffff';
            }
            $('#div-popup-form_' + the_form_post_id_val).css('background-color', pop_up_form_bg_colour);
        }
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
            $('#div-wp-any-form-msg_' + the_form_post_id_val).css('color', form_default_message_font_colour);
        }
        if(form_default_required_field_font_colour != '') {
            $(the_form_class_id_str).find('.span-required-field-custom').css('color', form_default_required_field_font_colour);
        }
        $.apply_individual_elements_style_form_builder_public(the_form_post_id_val, the_form_arr);
        if(form_cell_vertical_align != '') {
            $(the_form_class_id_str).find('.div-vertical-spacing-cell').css('vertical-align', form_cell_vertical_align);
        }
        if(form_default_cell_spacing != '') {
            $(the_form_class_id_str).find(the_cell_container_class_id_str).css('margin-left', form_default_cell_spacing + 'px');
            $(the_form_class_id_str).find(the_row_container_class_id_str).find(the_cell_container_class_id_str + ':first').css('margin-left', '0px');            
        }
        if(form_default_row_spacing != '') {
            $(the_form_class_id_str).find(the_row_container_class_id_str).css('margin-top', form_default_row_spacing + 'px');
        }
        if(form_default_cell_padding != '') {
            $(the_form_class_id_str).find(the_cell_container_class_id_str).css('padding', form_default_cell_padding + 'px');
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
                $(the_form_class_id_str).find(the_cell_container_class_id_str).css('width', 'auto');
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
                if(pop_up_form_is_pop_up == 'yes') {
                    $('#div-popup-form_' + the_form_post_id_val).css('max-width', form_max_width_val + 'px');
                }
                var the_max_control_width_val = +form_max_width_val;
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
                    if(pop_up_form_is_pop_up == 'yes') {
                        $('#div-popup-form_' + the_form_post_id_val).css('max-width', the_form_container_width_val + 'px');
                    }
                    apply_responsive = true;
                } else {
                    $(the_form_class_id_str).css('width', form_default_form_width + 'px');
                    the_row_width_val = form_default_form_width;
                    if(pop_up_form_is_pop_up == 'yes') {
                        $('#div-popup-form_' + the_form_post_id_val).css('max-width', form_default_form_width + 'px');
                    }
                }
                /* spacing */
                if(form_default_cell_spacing != '') {
                    the_row_width_val = +the_row_width_val - (form_default_cell_spacing * (+form_default_cells_per_row-1));    
                }
                /* padding */
                if(form_default_cell_padding != '') {
                    the_row_width_val = +the_row_width_val - (form_default_cell_padding * form_default_cells_per_row * 2);    
                }
                var the_cell_width_val;
                the_cell_width_val = the_row_width_val / form_default_cells_per_row;
                if(the_cell_width_val < 244) {
                    the_cell_width_val = 244;
                    the_row_width_val = the_cell_width_val * form_default_cells_per_row;   
                }
                $(the_form_class_id_str).find(the_cell_container_class_id_str).css('width', the_cell_width_val + 'px');
                /* lbl */
                var the_lbl_control_width_val = the_cell_width_val;
                $(the_form_class_id_str).find('.div-lbl-control').css('width', the_lbl_control_width_val + 'px');
                /* txt */
                var the_max_control_width_val = +the_cell_width_val;
                $(the_form_class_id_str).find('.txt_control').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_cell_width_required_span_public(the_form_class_id_str, '.txt_control', the_max_control_width_val);
                /* txta */
                $(the_form_class_id_str).find('.txta_control').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_cell_width_required_span_public(the_form_class_id_str, '.txta_control', the_max_control_width_val);
                /* ddl */
                $(the_form_class_id_str).find('.ddl_control').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_cell_width_required_span_public(the_form_class_id_str, '.ddl_control', the_max_control_width_val);
                /* cbx */
                $(the_form_class_id_str).find('.div-cbx-set-container').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_cell_width_required_span_public(the_form_class_id_str, '.div-cbx-set-container', the_max_control_width_val);
                /* rbtn */
                $(the_form_class_id_str).find('.div-rbtn-set-container').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_cell_width_required_span_public(the_form_class_id_str, '.div-rbtn-set-container', the_max_control_width_val);
                /* recaptcha */
                var the_max_control_width_val = +the_cell_width_val;
                $(the_form_class_id_str).find('.div-recaptcha-container').css('max-width', the_max_control_width_val + 'px');
                $.apply_style_form_cell_width_required_span_public(the_form_class_id_str, '.div-recaptcha-container', the_max_control_width_val);
                /* btnsubmit */
                var the_max_btnsubmit_control_width_val = +the_cell_width_val;
                $(the_form_class_id_str).find(':submit.div-wp-any-form-btnsubmit').css('max-width', the_max_btnsubmit_control_width_val + 'px');
                /* btnreset */
                var the_max_btnreset_control_width_val = +the_cell_width_val;
                $(the_form_class_id_str).find(':submit.div-wp-any-form-btnreset').css('max-width', the_max_btnreset_control_width_val + 'px');
                break;
            case 'flexi':
                if(form_default_form_width == '') {
                    apply_responsive = true;
                } else {
                    if(the_form_container_width_val < form_default_form_width) {
                        apply_responsive = true;
                    }
                }
                $(the_form_class_id_str).find(the_cell_container_class_id_str).css('width', 'auto');
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
                if(pop_up_form_is_pop_up == 'yes') {
                    $('#div-popup-form_' + the_form_post_id_val).css('max-width', form_max_width_val + 'px');
                }
                var the_max_control_width_val = +form_max_width_val;
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
                apply_style_form_flexi_grid_width_public(the_form_post_id_val);
                break;
        }
        if(apply_responsive) {
           $.apply_style_form_responsive_actions(the_form_post_id_val, the_form_container_width_val, form_default_cell_spacing);
        }
        $.apply_style_form_row_height_public(the_form_post_id_val, the_form_arr);
    }
    $.apply_style_form_responsive_actions = 
    function apply_style_form_responsive_actions(the_form_post_id_val, the_form_container_width_val, form_default_cell_spacing) {
        var the_form_class_id_str = '#div-wp-any-form_' + the_form_post_id_val;
        $(the_form_class_id_str).find(the_row_container_class_id_str).each(function() {
            var the_row_id_str = $(this).attr('id');
            if($.apply_style_form_responsive_check_if_row_cells_wider_than_screen(the_form_post_id_val, the_form_container_width_val, the_row_id_str, form_default_cell_spacing)) {
                $(the_form_class_id_str + ' #' + the_row_id_str).find(the_cell_container_class_id_str).css('margin-left', '0px');
                $(the_form_class_id_str + ' .div-clear-row_has_cells_wider_than_screen').css('display', 'block');
            } else {
                $(the_form_class_id_str + ' .div-clear-row_has_cells_wider_than_screen').css('display', 'none');
            }
        });
    }
    $.apply_style_form_responsive_check_if_row_cells_wider_than_screen = 
    function apply_style_form_responsive_check_if_row_cells_wider_than_screen(the_form_post_id_val, the_form_container_width_val, the_row_id_str, form_default_cell_spacing) {
        var the_form_class_id_str = '#div-wp-any-form_' + the_form_post_id_val;
        var the_row_cells_total_width = 0;
        var the_row_cell_counteri = 0;
        $(the_form_class_id_str + ' #' + the_row_id_str).find(the_cell_container_class_id_str).each(function() {
            the_row_cell_counteri += 1;
            var the_current_cell_width = $(this).width();
            the_row_cells_total_width += the_current_cell_width;
        });
        the_row_cells_total_width = +the_row_cells_total_width + ((+the_row_cell_counteri - 1) * form_default_cell_spacing);
        if(the_form_container_width_val < the_row_cells_total_width) {
            return true;
        } else {
            return false;
        }
    }
    function apply_style_form_flexi_grid_width_public(the_form_post_id_val) {
    	var the_form_class_id_str = '#div-wp-any-form_' + the_form_post_id_val;
        var the_flexi_grid_cell_widths_arr = {};
        $(the_form_class_id_str).find(the_row_container_class_id_str).each(function() {
            var the_row_obj = $(this);
            var the_flexi_grid_cell_counteri = 0;
            $(the_row_obj).find(the_cell_container_class_id_str).each(function() {
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
        $(the_form_class_id_str).find(the_row_container_class_id_str).each(function() {
            var the_row_obj = $(this);
            var the_flexi_grid_cell_counteri = 0;
            $(the_row_obj).find(the_cell_container_class_id_str).each(function() {
                var the_current_cell_width = the_flexi_grid_cell_widths_arr[the_flexi_grid_cell_counteri];
                $(this).css('width', the_current_cell_width + 'px');
                /* lbl */
                var the_lbl_control_width_val = the_current_cell_width; /* div-form-builder-img-cmd-container-row options */
                $(this).find('.div-lbl-control').css('width', the_lbl_control_width_val + 'px');
                the_flexi_grid_cell_counteri += 1;
            });
        });
	}
    $.apply_style_form_builder_row_height_get_max_height_public = 
    function apply_style_form_builder_row_height_get_max_height_public(the_max_height_for_row_val, the_cell_obj, the_control_class_id_str) {
        var the_new_cell_height_val = 1; 
        var the_cell_control_height_val = the_cell_obj.find(the_control_class_id_str).height();
        if(the_cell_control_height_val > 1) {
            the_new_cell_height_val = +the_cell_control_height_val + 5; 
            if(the_new_cell_height_val > the_max_height_for_row_val) {
                the_max_height_for_row_val = the_new_cell_height_val;
            }
        }
        switch(the_control_class_id_str) {
            case '.div-cbx-set-container': case '.div-rbtn-set-container':
                var the_div_vertical_spacing_cell_height_val = the_cell_obj.find('.div-vertical-spacing-cell').height();
                if(the_div_vertical_spacing_cell_height_val > the_max_height_for_row_val) {
                    the_max_height_for_row_val = the_div_vertical_spacing_cell_height_val;
                }
                break;
            case '.div-recaptcha-container':
                if(the_max_height_for_row_val == '-1') {
                    the_max_height_for_row_val = 1;
                }
                break;
        }
        return the_max_height_for_row_val;
    }
    $.apply_style_form_row_height_public =
	function apply_style_form_row_height_public(the_form_post_id_val, the_form_arr) {
        var the_form_container_height_val = 0;
        var the_form_rows_counter_val = 0;
        var the_form_class_id_str = '#div-wp-any-form_' + the_form_post_id_val;
        var the_form_container_arr = $.get_the_form_container_arr_the_form_post_id(the_form_post_id_val);
        var the_form_vars_arr = the_form_container_arr.the_form_vars_arr;
        var form_default_cell_spacing = the_form_vars_arr.form_default_cell_spacing;
        var the_form_container_width_val = $.get_the_form_container_width_val(the_form_post_id_val, the_form_vars_arr, false);
        $(the_form_class_id_str).find(the_row_container_class_id_str).each(function() {
            the_form_rows_counter_val += 1;
            var the_row_obj = $(this);
            var the_row_id_str = $(this).attr('id');
            var the_row_has_cells_wider_than_screen = $.apply_style_form_responsive_check_if_row_cells_wider_than_screen(the_form_post_id_val, the_form_container_width_val, the_row_id_str, form_default_cell_spacing)
            var the_max_height_for_row_val = '-1';
            the_row_obj.find(the_cell_container_class_id_str).each(function() {
                var the_cell_obj = $(this);
                if(the_cell_obj.find('.div-vertical-spacing-tbl').is(':visible')) {
                    the_cell_obj.css('height', 'auto');
                    var the_cell_id_str = the_cell_obj.attr('id');
                    $(the_row_obj).find('#' + the_cell_id_str).find('.div-control-container').css('height', '15px');
                    var str_arr_cell = the_cell_id_str.split('_');
                    var the_row_no_val = str_arr_cell[1];
                    var the_cell_no_val = str_arr_cell[2];
                    var the_control_fields_arr = $.get_control_fields_arr_from_form_arr(the_form_arr, the_row_no_val, the_cell_no_val);
                    var the_control_type_val = the_control_fields_arr.the_control_type;
                    switch(the_control_type_val) {
                        case 'lbl':
                            the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height_public(the_max_height_for_row_val, the_cell_obj, '.div-lbl-control');
                            break;
                        case 'txt':
                            the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height_public(the_max_height_for_row_val, the_cell_obj, '.txt_control');
                            break;
                        case 'txta':
                            the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height_public(the_max_height_for_row_val, the_cell_obj, '.txta_control');
                            break;
                        case 'ddl':
                            the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height_public(the_max_height_for_row_val, the_cell_obj, '.ddl_control');
                            break;
                        case 'cbx':
                            the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height_public(the_max_height_for_row_val, the_cell_obj, '.div-cbx-set-container');
                            break;
                        case 'rbtn':
                            the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height_public(the_max_height_for_row_val, the_cell_obj, '.div-rbtn-set-container');
                            break;
                        case 'recaptcha':
                            the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height_public(the_max_height_for_row_val, the_cell_obj, '.div-recaptcha-container');
                            break;
                        case 'btnsubmit':
                            the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height_public(the_max_height_for_row_val, the_cell_obj, '.div-wp-any-form-btnsubmit');
                            break;
                        case 'btnreset':
                            the_max_height_for_row_val = $.apply_style_form_builder_row_height_get_max_height_public(the_max_height_for_row_val, the_cell_obj, '.div-wp-any-form-btnreset');
                            break;
                        case 'emptycell':
                            the_max_height_for_row_val = 5;
                            break;
                    }
                    if(the_row_has_cells_wider_than_screen) {
                        $(the_row_obj).find('#' + the_cell_id_str).find('.div-control-container').css('height', 'auto');
                        $(the_row_obj).css('height', 'auto');
                        $(the_row_obj).find('#' + the_cell_id_str).css('height', 'auto');
                    }    
                }
            });
            if(the_max_height_for_row_val != '-1') {
                if(!the_row_has_cells_wider_than_screen) {
                    $(the_row_obj).find(the_cell_container_class_id_str).find('.div-control-container').css('height', the_max_height_for_row_val + 'px');
                    $(the_row_obj).find(the_cell_container_class_id_str).css('height', the_max_height_for_row_val + 'px');
                    $(the_row_obj).css('height', the_max_height_for_row_val + 'px');
                }
                the_form_container_height_val += the_max_height_for_row_val;
                the_max_height_for_row_val = '-1';
            }  
        });
    }
    $.apply_style_form_cell_width_required_span_public = 
    function apply_style_form_cell_width_required_span_public(the_form_class_id_str, the_control_class_id_str, the_max_control_width_val) {
        $(the_form_class_id_str).find(the_cell_container_class_id_str).has('.span-required-field-custom').each(function() {
            var the_cell_obj = $(this);
            var the_required_field_asterisk_span_width_val = the_cell_obj.find('.span-required-field-custom').width() + 5;
            var the_new_max_control_width_val = +the_max_control_width_val - the_required_field_asterisk_span_width_val;
            the_cell_obj.find(the_control_class_id_str).css('max-width', the_new_max_control_width_val + 'px');
        });
    }
    /* Functions End */
});