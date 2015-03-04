<?php

function biz_logic_wp_custom_get_ui_theme_dirs_ddl($selected_ui_theme) {
	$ddl_ui_theme_dirs_options_arr = array(
		"smoothness" => "Smoothness",
		"start" => "Start",
		"custom" => _x("Specify custom theme", "UI Theme drop down list option", "wp-any-form")
	);
    return biz_logic_wp_custom_get_ddl_html("", "ddl_ui_theme_dirs", "", "", $ddl_ui_theme_dirs_options_arr, $selected_ui_theme); 
}

function biz_logic_wp_custom_get_the_ui_theme_file_str() {
	$the_ui_theme_file_str = "smoothness/jquery-ui.min.css";
	$selected_ui_theme = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_selected_ui_theme", "smoothness"));
	switch ($selected_ui_theme) {
		case "custom":
			$path_to_custom_ui_theme = esc_textarea(biz_logic_wp_custom_get_site_option(BIZLOGIC_UNIQUE_PLUGIN_NAME . "_path_to_custom_ui_theme", ""));
	        $the_custom_theme_path = BIZLOGIC_PLUGIN_DIR_PATH . "css/jquery-ui-themes/" . $path_to_custom_ui_theme;
	        if (file_exists($the_custom_theme_path)) {
	            $the_ui_theme_file_str = $path_to_custom_ui_theme;
	        }
			break;
		default:
			$the_ui_theme_file_str = $selected_ui_theme . "/jquery-ui.min.css";	
			break;
	}
    return biz_logic_wp_custom_file_url($the_ui_theme_file_str);
}

function biz_logic_wp_custom_file_url($url) {
	$parts = parse_url($url);
  	$path_parts = array_map('rawurldecode', explode('/', $parts['path']));
	return
    	$parts['scheme'] . '' .
    	$parts['host'] .
    	implode('/', array_map('rawurlencode', $path_parts));
}

function biz_logic_wp_custom_get_recaptcha_language_ddl($selected_val) {
	$ddl_language_options_arr = array(
        "auto" => _x("Auto Detect", "ReCaptcha configuration language option (Auto Detect)", "wp-any-form"),
        "en" => _x("English (US)", "ReCaptcha configuration language option (English, US)", "wp-any-form"),
        "ar" => _x("Arabic", "ReCaptcha configuration language option (Arabic)", "wp-any-form"),
        "bg" => _x("Bulgarian", "ReCaptcha configuration language option (Bulgarian)", "wp-any-form"),
        "ca" => _x("Catalan", "ReCaptcha configuration language option (Catalan)", "wp-any-form"),
        "zh-CN" => _x("Chinese (Simplified)", "ReCaptcha configuration language option (Chinese, Simplified)", "wp-any-form"),
        "zh-TW" => _x("Chinese (Traditional)", "ReCaptcha configuration language option (Chinese, Traditional)", "wp-any-form"),
        "hr" => _x("Croatian", "ReCaptcha configuration language option (Croatian)", "wp-any-form"),
        "cs" => _x("Czech", "ReCaptcha configuration language option (Czech)", "wp-any-form"),
        "da" => _x("Danish", "ReCaptcha configuration language option (Danish)", "wp-any-form"),
        "nl" => _x("Dutch", "ReCaptcha configuration language option (Dutch)", "wp-any-form"),
        "en-GB" => _x("English (UK)", "ReCaptcha configuration language option (English, UK)", "wp-any-form"),
        "fil" => _x("Filipino", "ReCaptcha configuration language option (Filipino)", "wp-any-form"),
        "fi" => _x("Finnish", "ReCaptcha configuration language option (Finnish)", "wp-any-form"),
        "fr" => _x("French", "ReCaptcha configuration language option (French)", "wp-any-form"),
        "fr-CA" => _x("French (Canadian)", "ReCaptcha configuration language option (French, Canadian)", "wp-any-form"),
        "de" => _x("German", "ReCaptcha configuration language option (German)", "wp-any-form"),
        "de-AT" => _x("German (Austria)", "ReCaptcha configuration language option (German, Austria)", "wp-any-form"),
        "de-CH" => _x("German (Switzerland)", "ReCaptcha configuration language option (German, Switzerland)", "wp-any-form"),
        "el" => _x("Greek", "ReCaptcha configuration language option (Greek)", "wp-any-form"),
        "iw" => _x("Hebrew", "ReCaptcha configuration language option (Hebrew)", "wp-any-form"),
        "hi" => _x("Hindi", "ReCaptcha configuration language option (Hindi)", "wp-any-form"),
        "hu" => _x("Hungarain", "ReCaptcha configuration language option (Hungarain)", "wp-any-form"),
        "id" => _x("Indonesian", "ReCaptcha configuration language option (Indonesian)", "wp-any-form"),
        "it" => _x("Italian", "ReCaptcha configuration language option (Italian)", "wp-any-form"),
        "ja" => _x("Japanese", "ReCaptcha configuration language option (Japanese)", "wp-any-form"),
        "ko" => _x("Korean", "ReCaptcha configuration language option (Korean)", "wp-any-form"),
        "lv" => _x("Latvian", "ReCaptcha configuration language option (Latvian)", "wp-any-form"),
        "lt" => _x("Lithuanian", "ReCaptcha configuration language option (Lithuanian)", "wp-any-form"),
        "no" => _x("Norwegian", "ReCaptcha configuration language option (Norwegian)", "wp-any-form"),
        "fa" => _x("Persian", "ReCaptcha configuration language option (Persian)", "wp-any-form"),
        "pl" => _x("Polish", "ReCaptcha configuration language option (Polish)", "wp-any-form"),
        "pt" => _x("Portuguese", "ReCaptcha configuration language option (Portuguese)", "wp-any-form"),
        "pt-BR" => _x("Portuguese (Brazil)", "ReCaptcha configuration language option (Portuguese, Brazil)", "wp-any-form"),
        "pt-PT" => _x("Portuguese (Portugal)", "ReCaptcha configuration language option (Portuguese, Portugal)", "wp-any-form"),
        "ro" => _x("Romanian", "ReCaptcha configuration language option (Romanian)", "wp-any-form"),
        "ru" => _x("Russian", "ReCaptcha configuration language option (Russian)", "wp-any-form"),
        "sr" => _x("Serbian", "ReCaptcha configuration language option (Serbian)", "wp-any-form"),
        "sk" => _x("Slovak", "ReCaptcha configuration language option (Slovak)", "wp-any-form"),
        "sl" => _x("Slovenian", "ReCaptcha configuration language option (Slovenian)", "wp-any-form"),
        "es" => _x("Spanish", "ReCaptcha configuration language option (Spanish)", "wp-any-form"),
        "es-419" => _x("Spanish (Latin America)", "ReCaptcha configuration language option (Spanish, Latin America)", "wp-any-form"),
        "sv" => _x("Swedish", "ReCaptcha configuration language option (Swedish)", "wp-any-form"),
        "th" => _x("Thai", "ReCaptcha configuration language option (Thai)", "wp-any-form"),
        "tr" => _x("Turkish", "ReCaptcha configuration language option (Turkish)", "wp-any-form"),
        "uk" => _x("Ukrainian", "ReCaptcha configuration language option (Ukrainian)", "wp-any-form"),
        "vi" => _x("Vietnamese", "ReCaptcha configuration language option (Vietnamese)", "wp-any-form")
    );
    return biz_logic_wp_custom_get_ddl_html("", "ddl_recaptcha_language", "", "", $ddl_language_options_arr, $selected_val); 
}

?>