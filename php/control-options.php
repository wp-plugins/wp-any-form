<?php

function biz_logic_wp_custom_get_control_options_arr($the_control_type, $the_action_cmd, $the_control_fields_arr) {
    $return_arr = false;
    switch ($the_control_type) {
    	case "lbl":
            $return_arr = biz_logic_wp_custom_get_lbl_control_options_arr($the_action_cmd, $the_control_fields_arr);
    		break;
    	case "txt":
            $return_arr = biz_logic_wp_custom_get_txt_control_options_arr($the_action_cmd, $the_control_fields_arr);
    		break;
    	case "txta":
            $return_arr = biz_logic_wp_custom_get_txta_control_options_arr($the_action_cmd, $the_control_fields_arr);
    		break;
        case "ddl":
            $return_arr = biz_logic_wp_custom_get_ddl_control_options_arr($the_action_cmd, $the_control_fields_arr);
            break;
        case "cbx":
            $return_arr = biz_logic_wp_custom_get_cbx_control_options_arr($the_action_cmd, $the_control_fields_arr);
            break;
        case "rbtn":
            $return_arr = biz_logic_wp_custom_get_rbtn_control_options_arr($the_action_cmd, $the_control_fields_arr);
            break;
        case "recaptcha":
            $return_arr = biz_logic_wp_custom_get_recaptcha_control_options_arr($the_action_cmd, $the_control_fields_arr);
            break;   
        case "btnsubmit":
            $return_arr = biz_logic_wp_custom_get_btnsubmit_control_options_arr($the_action_cmd, $the_control_fields_arr);
            break;
        case "btnreset":
            $return_arr = biz_logic_wp_custom_get_btnreset_control_options_arr($the_action_cmd, $the_control_fields_arr);
            break;
        case "emptycell":
            $return_arr = biz_logic_wp_custom_get_emptycell_control_options_arr($the_action_cmd, $the_control_fields_arr);
            break;
    }
    return $return_arr;
}

?>