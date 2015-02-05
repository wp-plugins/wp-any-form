<?php

/* 
Public class
*/

class WPAnyFormPublic {
    
function __construct() {
    add_action('wp_enqueue_scripts', array($this, 'load_js'));
	add_action('wp_enqueue_scripts', array($this, 'load_css'));
    add_shortcode(BIZLOGIC_UNIQUE_PLUGIN_NAME, array($this, "handler"));        
    add_shortcode("wp_any_form_e", array($this, "email_template_handler"));        
    add_action('wp_ajax_nopriv_' . BIZLOGIC_UNIQUE_PLUGIN_NAME . '-ajax-submit', array($this, 'ajax_submit'));
    add_action('wp_ajax_' . BIZLOGIC_UNIQUE_PLUGIN_NAME . '-ajax-submit', array($this, 'ajax_submit'));  
    add_filter('query_vars', array($this, 'wp_any_form_query_vars'));
}

function wp_any_form_query_vars($vars) {
    $vars[] = 'wp_any_form';
    return $vars;
}

function load_js() {
    wp_enqueue_script('jquery');			
    wp_enqueue_script('jquery-ui-core');
    wp_register_script('biz_logic_wp_custom_js_lib', plugins_url('/js/lib.js', dirname(__FILE__ )));
    wp_enqueue_script('biz_logic_wp_custom_js_lib');
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_magnific_popup_js', plugins_url('/js/jquery.magnific-popup.min.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_magnific_popup_js');
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_lib_js', plugins_url('/js/form-lib.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_lib_js');   
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_style_js', plugins_url('/js/form-style.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_style_js');
    wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_js', plugins_url('/js/form-init.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_js');	
    $recaptcha_site_key = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_recaptcha_site_key", ""));
    wp_localize_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_js', 'WPAnyFormPublicJSO', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'plugin_url' => plugins_url(),
        'recaptcha_site_key' => $recaptcha_site_key
    ));
    /*wp_register_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_js', plugins_url('/js/init.js', dirname(__FILE__ )));
    wp_enqueue_script(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_js');*/
}

function load_css() {
    wp_register_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_magnific_popup_css', plugins_url('css/magnific-popup.css', dirname(__FILE__)));
    wp_enqueue_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_magnific_popup_css');
    wp_register_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_css', plugins_url('/css/style.css', dirname(__FILE__)));
    wp_enqueue_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_css');
	wp_register_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_style_css', plugins_url('/css/form-style.css', dirname(__FILE__)));
    wp_enqueue_style(BIZLOGIC_UNIQUE_PLUGIN_NAME . '_form_style_css');
}

function handler($atts) {
	extract(shortcode_atts(array('display' => '-1', 'pid' => '-1'), $atts));
	$html_str = $this->get_content($display, $pid);	
	return $html_str;
}   

function get_content($display, $pid) {
	$html_str = "";
	switch ($display) {
		case "form":
			$html_str = $this->get_form_html($pid);
			break;
        case "-1": default:
			$html_str = "<p>" . _x("No selection specified.", "Shortcode get content no valid selection message", "wp-any-form") . "</p>";
			break;
	}
	$html_str .= "<input type='hidden' class='" . BIZLOGIC_UNIQUE_PLUGIN_NAME . "_jslib' value='" . $display . "' />";
	return $html_str;
}

function email_template_handler($atts) {
    extract(shortcode_atts(array('f' => '-1'), $atts));
    return biz_logic_wp_custom_wp_any_form_get_saved_field_data_for_submit_email($f);
}   

function get_form_html($the_post_id) {
	$html_str = "";
    $the_post_status = get_post_status($the_post_id);
    $is_preview = biz_logic_wp_custom_get_url_string_val("preview", "false");
    if($the_post_status == "publish" || $is_preview == "true") {
        $hval_form_builder_arr = biz_logic_wp_custom_get_post_meta($the_post_id, "hval_form_builder_arr", "");
        $have_saved_arr_not_empty = false;
        if($hval_form_builder_arr != "") {
            $hval_form_builder_arr = json_decode($hval_form_builder_arr, true);
            if (!biz_logic_wp_custom_check_if_array_empty($hval_form_builder_arr)) {
                $have_saved_arr_not_empty = true;            
            }
        }
        if($have_saved_arr_not_empty) {
            $the_form_vars_arr = biz_logic_wp_custom_get_the_form_vars_arr($the_post_id);
            $html_str .= biz_logic_wp_custom_build_form_from_saved_data_public($the_post_id, $hval_form_builder_arr, $the_form_vars_arr);
        } else {
            $html_str .= WPAF_FORM_NOT_FOUND_MSG;    
        }
    } else {
        $html_str .= WPAF_FORM_NOT_FOUND_MSG;
    }
	return $html_str;
}

function ajax_submit() {
    $return['error'] = true;    
    $cmd = htmlspecialchars($_POST['cmd']);
    switch ($cmd) {
        case 'submit-form':
            $the_form_submit_arr = $_POST['the_form_submit_arr'];
            $the_form_post_id = $_POST['the_form_post_id'];
            $is_in_preview_mode = $_POST['is_in_preview_mode'];
            $the_form_vars_arr = $_POST['the_form_vars_arr'];
            $return_arr = biz_logic_wp_custom_submit_form_action($the_form_post_id, $the_form_submit_arr, $is_in_preview_mode, $the_form_vars_arr);
            $return['return_msg'] = $return_arr['return_msg'];
            $return['the_form_vars_arr'] = $return_arr['the_form_vars_arr'];    
            $return['error'] = false;
            break;
        case 'recaptcha-validate':
            $the_response = htmlspecialchars(trim($_POST['the_response']));
            $return['recaptcha_isvalid'] = biz_logic_wp_custom_recaptcha_check_answer($the_response);
            $return['error'] = false;
            break;
    }
    $response = json_encode($return);
    header("Content-Type: application/json");
    echo $response;
    exit;
}

} // end class

?>