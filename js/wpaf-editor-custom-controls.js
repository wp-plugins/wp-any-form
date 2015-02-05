jQuery(document).ready(function($) {
    tinymce.create('tinymce.plugins.wpaf_mce_custom_tinymce_plugin', {
        init : function(ed, url) {
            ed.addButton( 'wpaf_mce_custom_add_tinymce_forms_ddl', {
                type: 'listbox',
                text: WPAnyFormAdminJSO.mce_forms_ddl_text,
                icon: false,
                onselect: function(e) {},
                values: the_form_posts_drop_down_tiny_mce_values_obj,
                onPostRender: function() {
                    this.addClass('ddl_wpaf_form_shortcode');
                }
            });
        },   
    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('wpaf_mce_custom_add_tinymce_forms_ddl', tinymce.plugins.wpaf_mce_custom_tinymce_plugin);
});