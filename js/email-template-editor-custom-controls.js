jQuery(function ($) {
    $.show_dialog_email_templates_form_fields =
    function show_dialog_email_templates_form_fields(ed, the_dialog_buttons_obj, the_dialog_html_str) {
        ed.windowManager.open({
            title: WPAnyFormAdminJSO.email_templates_form_fields_dialog_title,
            body: [{
                type: 'container',
                html: the_dialog_html_str
            }],
            buttons: the_dialog_buttons_obj
        });
    }
});

jQuery(document).ready(function($) {

    tinymce.create('tinymce.plugins.email_templates_mce_custom_tinymce_plugin', {
        init : function(ed, url) {
                // Register command for when button is clicked
                ed.addCommand('email_templates_show_form_fields_dialog_cmd', function() {
                    var the_email_template_custom_field_form_post_id_val = $('#ddl_email_templates_options_form_posts').val();
                    var the_dialog_buttons_obj = false; 
                    var the_dialog_html_str = '';
                    if(the_email_template_custom_field_form_post_id_val == '') {
                        the_dialog_buttons_obj = [{
                            text: WPAnyFormAdminJSO.email_templates_form_fields_dialog_no_form_selected_btn_txt,
                            id: 'email_templates_mce_custom_tinymce_plugin-dialog-button-ok',
                            onclick: 'close'
                        }];    
                        the_dialog_html_str = WPAnyFormAdminJSO.email_templates_form_fields_dialog_no_form_selected_error_msg;
                        $.show_dialog_email_templates_form_fields(ed, the_dialog_buttons_obj, the_dialog_html_str);
                    } else {
                        the_dialog_buttons_obj = [{
                            text: WPAnyFormAdminJSO.email_templates_form_fields_dialog_btn_txt_insert,
                            id: 'email_templates_mce_custom_tinymce_plugin-dialog-button-insert',
                            class: 'insert',
                            onclick: function( e ) {
                                var the_selected_form_field_val = $('#ddl_form_fields').val();
                                var content = '[wp_any_form_e f="' + the_selected_form_field_val + '"]';
                                tinymce.execCommand('mceInsertContent', false, content);
                                top.tinymce.activeEditor.windowManager.close();
                            },
                        },
                        {
                            text: WPAnyFormAdminJSO.email_templates_form_fields_dialog_btn_txt_cancel,
                            id: 'email_templates_mce_custom_tinymce_plugin-dialog-button-cancel',
                            onclick: 'close'
                        }];    
                        $.ajax({ 
                            type : 'POST', 
                            url : WPAnyFormAdminJSO.ajaxurl,  
                            dataType : 'json', 
                            data: { action : 'wp_any_form_admin-ajax-submit', cmd: 'get-email-templates-form-fields', the_selected_form_post_id: the_email_template_custom_field_form_post_id_val }, 
                            success : function(data) {
                                if (!data.error) {
                                    the_dialog_html_str = data.html_str;            
                                    $.show_dialog_email_templates_form_fields(ed, the_dialog_buttons_obj, the_dialog_html_str);
                                }
                            } 
                        });           
                    }
                });

            // Register buttons - trigger above command when clicked
            ed.addButton('email_templates_mce_custom_add_tinymce_button', {title : WPAnyFormAdminJSO.email_templates_field_shortcode_insert_title, cmd : 'email_templates_show_form_fields_dialog_cmd', image: url + '/database_add.png'/*, text: 'Insert WP Any Form field shortcode', icon: false*/ });
        },   
    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('email_templates_mce_custom_add_tinymce_button', tinymce.plugins.email_templates_mce_custom_tinymce_plugin);
});