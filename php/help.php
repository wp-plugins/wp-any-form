<?php

function biz_logic_wp_custom_get_help_message_html($for_what) {
    $html_str = "";
    switch($for_what) {
        case "formfieldname":
            $html_str .= _x("<p>The name of the field, to be used with validation messages for required fields as well as saved form data, data grids and CSV and also email templates.</p><p>Each field name must be unique for form and can not contain any of the following characters: & \" ' < >.</p>", "Help / More Info text (Form field name)", "wp-any-form");
            break;
        case "formdefaultlayout":
            $html_str .= _x("<p>Select form layout from:</p><p>Auto layout - The width of each cell will be determined by its contents.</p><p>Grid layout - Each row will have specified amount of cells, the width of each being total (form) width / number of cells.</p><p>Flexi grid layout - Each cell will be the length of the widest cell for that column found in all rows.</p>", "Help / More Info text (Form default layout)", "wp-any-form");
            break;
        case "formlayoutcellsperrow":
            $html_str .= "<p>" . _x("Grid layout will only be active if this value is set.", "Help / More Info text (Form layout cells per row)", "wp-any-form") . "</p>";
            break;
        case "formlayoutcellspacing":
            $html_str .= "<p>" . _x("The horizontal space between cells.", "Help / More Info text (Form layout cell spacing)", "wp-any-form") . "</p>";
            break;
        case "formlayoutrowspacing":
            $html_str .= "<p>" . _x("The vertical space between rows.", "Help / More Info text (Form layout row spacing)", "wp-any-form") . "</p>";
            break;
        case "formlayoutcellpadding":
            $html_str .= "<p>" . _x("The padding inside cells.", "Help / More Info text (Form layout cell padding)", "wp-any-form") . "</p>";
            break;
        case "formlayoutverticalalign":
            $html_str .= "<p>" . _x("The vertical alignment of cell content.", "Help / More Info text (Form layout vertical align)", "wp-any-form") . "</p>";
            break;
        case "ddlhasselectedvalueindicator":
            $html_str .= _x("<p>If checked this item, if selected from drop down list and form is submitted, will indicate that the field has no (selected) value.</p><p>If the field is set to be a required field the form validation will fail with appropiate error message e.g. \"Field required\".</p><p>Example of the item values:<br /><table><tr><td class='class-bold' >Item description:</td><td>Select value...</td></tr><tr><td class='class-bold' >Item value:</td><td>-1</td></tr></table></p>", "Help / More Info text (Drop down list control item option)", "wp-any-form");
            break;
        case "ddlselectedvalue":
        	$html_str .= _x("<p>The initial selected value when drop down list is rendered.</p><p>Possible values are from the initial item set, configurable on \"Item sets\" tab.</p>", "Help / More Info text (Drop down list control general option, selected value)", "wp-any-form");
        	break;
        case "isinitialitemset":
        	$html_str .= _x("<p>The initial item set which will be loaded when drop down list is rendered.</p><p>If this field is not set for any item sets for this drop down list control the first item set will be the initial item set.</p>", "Help / More Info text (Drop down list control item set option)", "wp-any-form");
        	break;
        case "cbxrequired":
            $html_str .= _x("<p>Validation will fail if not at least one checkbox in this checkbox set is checked when form is submitted.</p>", "Help / More Info text (Checkbox control general option, validation)", "wp-any-form");
            break;
        case "cbxmustbechecked":
            $html_str .= _x("<p>Validation will fail if this checkbox is not checked when form is submitted.</p><p>Useful for where the user is required to agree to a requirement e.g. Terms & Conditions.</p>", "Help / More Info text (Checkbox control item option)", "wp-any-form");
            break;
        case "cbxmustbecheckederrormsg":
            $html_str .= _x("<p>The error message that will be displayed when this checkbox is set to must be checked and it is not checked and form validation subsequently fails.</p>", "Help / More Info text (Checkbox control item option)", "wp-any-form");
            break;            
        case "cbxsperrow":
            $html_str .= _x("<p>The number of checkboxes that will be displayed in a row.</p><p>If no value is entered the checboxes will be in one line, container width permitting.</p>", "Help / More Info text (Checkbox control style option)", "wp-any-form");
            break;
        case "cbxuseflexiwidth":
            $html_str .= _x("<p>Apply flexi width algorithm to all checkboxes in set i.e. </p><p>Each cell / checkbox will be the length of the widest cell / checkbox for that column found in all rows.</p><p>This is only applicable when a value is specified for \"Number per row\".</p>", "Help / More Info text (Checkbox control style option)", "wp-any-form");
            break;
        case "cbxdatavaluesseparator":
            $html_str .= _x("<p>When multiple values are checked they will be separated using the character specified e.g.</p><p>Example, checked, values or</p><p>Example; checked; values etc.</p>", "Help / More Info text (Checkbox control general option)", "wp-any-form");
            break;
        case "rbtnsperrow":
            $html_str .= _x("<p>The number of radio buttons that will be displayed in a row.</p><p>If no value is entered the radio buttons will be in one line, container width permitting.</p>", "Help / More Info text (Radio button style option)", "wp-any-form");
            break;
        case "rbtnuseflexiwidth":
            $html_str .= _x("<p>Apply flexi width algorithm to all radio buttons in set i.e. </p><p>Each cell / radio button will be the length of the widest cell / radio button for that column found in all rows.</p><p>This is only applicable when a value is specified for \"Number per row\".</p>", "Help / More Info text (Radio button style option)", "wp-any-form");
            break;
        case "specifycustomuitheme":
            $html_str .= _x("<p>To use a custom jQuery UI theme follow the following steps:</p><ol><li>Upload the custom jQuery UI theme folder containing neccesary files to the following subfolder of the plugin folder: <br />\"" . BIZLOGIC_PLUGIN_FOLDER . "/css/jquery-ui-themes/\"</li><li>Type in the folder that contains the jQuery UI theme files with the name of the main jQuery UI CSS file e.g.<br />\"smoothness/jquery-ui.min.css\"</li><li>You can download and create your own jQuery UI Themes at <a href='http://jqueryui.com/themeroller/' target='_blank' >jQuery UI Themeroller</a></li><li>Save configuration</li></ol></p>", "Help / More Info text (Plugin configuration Specify custom UI Theme)", "wp-any-form");
            break;
        case "excludeuitheme":
            $html_str .= _x("<p>This option is for excluding jQuery UI theme from this plugin for WordPress admin.</p><p>This option would be applicable when another active plugin includes the jQuery UI theme files.</p>", "Help / More Info text (Plugin configuration exclude UI Theme)", "wp-any-form");
            break;
        case "recaptchaconfig":
            $html_str .= _x("<p>ReCaptcha protects your website from spam by presenting the user with a challenge to complete in order to submit the form.</p><p>This prevents automated scripts i.e. bots from completing and submitting a form as it can't complete the ReCaptcha challenge successfully.</p><p>You can sign up for your Site and Secret keys at <a href='https://www.google.com/recaptcha/intro/index.html' target='_blank' >Google ReCaptcha</a>.</p><p>Please ensure the keys entered are correct as the ReCaptcha controls will only load if keys are correct.</p>", "Help / More Info text (Plugin configuration ReCaptcha)", "wp-any-form");
            break;
        case "customcssclass":
            $html_str .= _x("<p>Custom CSS Class or Classes to assign to control for use with custom styling.</p><p>Separate multiple class names with a space e.g. customcssclassa customcssclassb.</p>", "Help / More Info text (Control style option, Custom CSS Class)", "wp-any-form");
            break;
        case "autoreplyaddress":
            $html_str .= _x("<p>Select form field (email address) to send the auto reply email to.</p><p>Only form fields (controls) of type \"Text Box\" with option \"Email\" ticked will be listed for the selected form.</p><p>If none is listed you would need to add a \"Text Box\" form control with option \"Email\" ticked to the form and save the form first, then reload this page.</p><p>It is advisable to also tick the \"Required\" option for this form field (control) to ensure there exists a value for it when form is submitted.</p>", "Help / More Info text (Email template option, Send to email address form field)", "wp-any-form");
            break;
        case "txttype":
            $html_str .= _x("<p>Numeric: Text Box control is limited to numeric characters only.</p><p>Password: Text Box control characters are masked.</p>", "Help / More Info text (Text box control general options, Validation)", "wp-any-form");
            break;
        case "txtvalidation":
            $html_str .= _x("<p>Required: Validation will fail if no value is entered.</p><p>Email: Validation will fail if value is not a valid email address.</p><p>Confirm: Validation will fail if the selected Text Box control to confirm has a value that is different from the value of this Text Box control.</p>", "Help / More Info text (Text box control general options, Validation)", "wp-any-form");
            break;
    }
    return $html_str;    
}

?>