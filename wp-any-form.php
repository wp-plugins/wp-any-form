<?php
/*
Plugin Name: WP Any Form
Plugin URI: http://biz-logic.co.za/downloads/wp-any-form/
Description: Responsive Ajax Forms with Drag and Drop form builder, saved form submissions, auto email templates and more.
Version: 1.0.1
Author: biz-logic
Author URI: http://biz-logic.co.za
Text Domain: wp-any-form
License: GPLv2 or later
*/

class WPAnyForm {
    
function __construct() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    define('BIZLOGIC_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
    define('BIZLOGIC_PLUGIN_FOLDER', dirname(plugin_basename(__FILE__)));
    define('BIZLOGIC_UNIQUE_PLUGIN_NAME', "wp_any_form");
    add_action('init', array($this, 'wp_any_form_plugin_init'));
    define('ASTERISK_HTML_STR', "<span class='span-required-field' >*</span>");
    define('ASTERISK_HTML_STR_USER', "<span class='span-required-field-custom' >*</span>");
    require_once("php/i18-constants.php");
    add_action('init', array($this, 'forms_custom_post_type_init'));
    add_action('init', array($this, 'form_data_custom_post_type_init'));
    add_action('init', array($this, 'form_data_schema_custom_post_type_init'));
    add_action('init', array($this, 'form_email_templates_custom_post_type_init'));
    add_filter('the_content', array($this, 'form_single_post_add_shortcode'));
    require_once("php/lib.php");
    require_once("php/configuration.php");
    require_once("php/control-options.php");
    require_once("php/control-html.php");
    require_once("php/form-style.php");
    require_once("php/form-builder-admin.php");
    require_once("php/form-builder-public.php");
    require_once("php/lbl.php");
    require_once("php/txt.php");
    require_once("php/txta.php");
    require_once("php/ddl.php");
    require_once("php/cbx.php");
    require_once("php/rbtn.php");
    require_once("php/recaptcha.php");
    require_once("php/btnsubmit.php");
    require_once("php/btnreset.php");
    require_once("php/emptycell.php");
    require_once("php/submit-form.php");
    require_once("php/form-data.php");
    require_once("php/data-schema.php");
    require_once("php/data-grids.php");
    require_once("php/email-options.php");
    require_once("php/csv_export.php");
    require_once("php/help.php");
    require_once('classes/public.php');
    $this->publicO = new WPAnyFormPublic();
    require_once('classes/admin.php');
    $this->adminO = new WPAnyFormAdmin();
    require_once("classes/widgets/form_widget.php");
}

function wp_any_form_plugin_init() {
    $plugin_dir = dirname(plugin_basename(__FILE__)) . "/languages/";
    load_plugin_textdomain("wp-any-form", false, $plugin_dir);
}

function forms_custom_post_type_init() {
    $args = array(
        'label' => _x("Any Forms", "A plural descriptive name for the post type", "wp-any-form"),
        'labels' => array(
            'name' => _x("Any Forms", "General name for the post type", "wp-any-form"),
            'singular_name' => _x("Form", "Name for one object of this post type", "wp-any-form"),
            'add_new_item' => _x("Add new form", "The add new item text", "wp-any-form"),
            'edit_item' => _x("Edit form", "The edit item text", "wp-any-form"),
            'new_item' => _x("New form", "The new item text", "wp-any-form"),
            'view_item' => _x("View form", "The view item text", "wp-any-form"),
            'search_items' => _x("Search forms", "The search items text", "wp-any-form"),
            'not_found' => _x("No forms found", "The not found text", "wp-any-form"),
            'not_found_in_trash' => _x("No forms found in Trash", "The not found in trash text", "wp-any-form"),
        ),      
        'description' => _x("Form that can contain different input items", "A short descriptive summary of what the post type is", "wp-any-form"),
        'public' => true, 
        'rewrite' => array('slug' => 'any_form'),
        'supports' => array('title'),
        'has_archive'   => false
    );
    register_post_type('wp_any_form', $args);
}

function form_data_custom_post_type_init() {
    $args = array(
        'label' => _x("Form Data", "A plural descriptive name for the post type", "wp-any-form"),
        'labels' => array(
            'name' => _x("Form Data", "General name for the post type", "wp-any-form"),
            'singular_name' => _x("Form Data", "Name for one object of this post type", "wp-any-form"),
            'add_new_item' => _x("Add new form data", "The add new item text", "wp-any-form"),
            'edit_item' => _x("Form Data", "The edit item text (disabled, thus the page title when viewing Form Data)", "wp-any-form"),
            'new_item' => _x("New form data", "The new item text", "wp-any-form"),
            'view_item' => _x("View form data", "The view item text", "wp-any-form"),
            'search_items' => _x("Search form data", "The search items text", "wp-any-form"),
            'not_found' => _x("No form data found", "The not found text", "wp-any-form"),
            'not_found_in_trash' => _x("No form data found in Trash", "The not found in trash text", "wp-any-form"),
        ),      
        'description' => _x("Saved Form Data", "A short descriptive summary of what the post type is", "wp-any-form"),
        'map_meta_cap' => true,
        'capabilities' => array('create_posts' => false),
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_in_nav_menus' => false,
        'show_in_menu' => 'edit.php?post_type=wp_any_form',
        'show_in_admin_bar' => false,
        'rewrite' => false,
        'supports' => array('title'),
        'has_archive'   => false
    );
    register_post_type('wp_any_form_data', $args);
}

function form_data_schema_custom_post_type_init() {
    $args = array(
        'label' => 'Form Data Schema',
        'labels' => array(
            'name' => 'Form Data Schemas',
            'singular_name' => 'Form Data Schema',
            'add_new_item' => 'Add new form data schema',
            'edit_item' => 'Form Data Schema',
            'new_item' => 'New form data schema',
            'view_item' => 'View form data schema',
            'search_items' => 'Search form data schemas',
            'not_found' => 'No form data schemas found',
            'not_found_in_trash' => 'No form data schemas found in Trash',
        ),      
        'description' => 'Saved Form Data Schemas',
        'public' => false,
        'rewrite' => false,
        'supports' => array('title'),
        'has_archive'   => false
    );
    register_post_type('wpaf_data_schema', $args);
}

function form_email_templates_custom_post_type_init() {
    $args = array(
        'label' => _x("Email Templates", "A plural descriptive name for the post type", "wp-any-form"),
        'labels' => array(
            'name' => _x("Email Templates", "General name for the post type", "wp-any-form"),
            'singular_name' => _x("Email Template", "Name for one object of this post type", "wp-any-form"),
            'add_new_item' => _x("Add new email template", "The add new item text", "wp-any-form"),
            'edit_item' => _x("Edit email template", "The edit item text", "wp-any-form"),
            'new_item' => _x("New email template", "The new item text", "wp-any-form"),
            'view_item' => _x("View email template", "The view item text", "wp-any-form"),
            'search_items' => _x("Search email templates", "The search items text", "wp-any-form"),
            'not_found' => _x("No email templates found", "The not found text", "wp-any-form"),
            'not_found_in_trash' => _x("No email templates found in Trash", "The not found in trash text", "wp-any-form"),
        ),      
        'description' => _x("Saved Form Email Templates", "A short descriptive summary of what the post type is", "wp-any-form"),
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_in_nav_menus' => false,
        'show_in_menu' => 'edit.php?post_type=wp_any_form',
        'show_in_admin_bar' => false,
        'rewrite' => false,
        'supports' => array('title', 'editor'),
        'has_archive'   => false
    );
    register_post_type('wpaf_email_templates', $args);
}

function form_single_post_add_shortcode($content) {
    $custom_content_html = "";
    if (get_post_type() == 'wp_any_form') {
        if (is_single()) {
            global $post;
            $the_post_id = $post->ID;
            $custom_content_html .= "[wp_any_form display=\"form\" pid=\"" . $the_post_id . "\"]";
        }
    }
    $content .= $custom_content_html;   
    return $content;
}

} // end class

$wp_any_formO = new WPAnyForm();

?>