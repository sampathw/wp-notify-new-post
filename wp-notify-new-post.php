<?php

/* Plugin Name: WP Notify New Post
  Plugin URI: http://sampathwijeratne.name/wp-notify-new-post/
  Description: This plugin notifies the subscribed users through <strong>email and SMS</strong> when a new post is added. You need to have a <strong><a href="http://www.mailchimp.com/">MailChimp</a>+<a href="http://www.mandrill.com/">Mandrill</a> account with a subscriber list and a <a href="https://www.twilio.com/">Twillio.com</a> account for SMS sending</strong>.
  Version: 1.0.0
  Author: Sampath Wijeratne
  Author URI: http://sampathwijeratne.name
  License: GPLv2 or later
 */

include 'sdwNotifyAdapter.php';

// adding plugin's menu to the Wordpress admin menu
add_action('admin_menu', 'sdw_notify_new_post_plugin_menu');

// function setting up the plugin's page in admin menu
function sdw_notify_new_post_plugin_menu() {
    $page = add_menu_page('WP Notify New Post settings', 'WP Notify New Post', 'administrator', 'sdw_notify_new_post-settings', 'sdw_notify_new_post_plugin_settings_page', plugin_dir_url(__FILE__) . 'images/notify');
    add_action('admin_print_styles-' . $page, 'sdw_notify_new_post_plugin_admin_styles');
}

// setting up plugin data
add_action('admin_init', 'sdw_notify_new_post_plugin_settings');

// function to setup data fields of the options page
function sdw_notify_new_post_plugin_settings() {
    wp_register_style('sdw_notify_new_post_PluginStylesheet', plugins_url('css/sw_styles.css', __FILE__));
    register_setting('sdw_notify_new_post_plugin-settings-group', 'sms_notify');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'sms_twilio_sid');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'sms_twilio_auth_token');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'sms_twilio_number');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'sms_message');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'email_notify');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'mailchimp_api_key_tw');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'mailchimp_list_id_tw');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'mailchimp_api_key');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'mailchimp_list_id');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'mandril_api_key');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'mandril_template_name');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'mandril_template_merge_tag_greeting');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'mandril_template_merge_tag_link');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'mandril_template_merge_tag_thankyoutext');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'email_subject');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'from_email');
    register_setting('sdw_notify_new_post_plugin-settings-group', 'from_name');
}

// enque the style
function sdw_notify_new_post_plugin_admin_styles() {
    wp_enqueue_style('sdw_notify_new_post_PluginStylesheet');
}

// adding JavaScripts
add_action('admin_enqueue_scripts', 'my_enqueue');

function my_enqueue($hook) {

    wp_enqueue_script('ajax-script', plugins_url('js/scripts.js', __FILE__), array('jquery'));

    // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
    wp_localize_script('ajax-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'we_value' => 1234));
}

// function setting up the plugin settings page 
function sdw_notify_new_post_plugin_settings_page() {
    echo'<div class = "wrap">
    <h2><img src="' . plugin_dir_url(__FILE__) . 'images/notify"/>   WP Notify New Post Settings</h2>
    <hr><br>
    <form method = "post" action = "options.php">';
    settings_fields('sdw_notify_new_post_plugin-settings-group');
    do_settings_sections('sdw_notify_new_post_plugin-settings-group');


    // SMS setting section
    $checked_sms = '';
    if (get_option('sms_notify')) {
        $checked_sms = "checked=\"checked\"";
    }
    echo'<div class="options_set">
                        
            <div class="sw_plugin_subtitle">Setup SMS Notification Settings</div><hr/>
            <div><strong>Your site must be hosted with encrypted HTTPS</strong>.<br/> If you don\'t have a Twillio account, please visit <a href="http://www.twilio.com" target="_new">Twilio.com</a> 
            and then navigate to <strong>Account > Dev Tools > TWML Apps</strong> and setup the SID and Auth Token. <br/><br/>
            <strong>For the current version of this plugin, it is required to store the phone numbers and names in a MailChimp List.</strong><br/></div>

            <table class="form-table">
                
                <tr valign="top">
                    <th scope="row">Enable SMS Notification</th>
                    <td><input class="check-field" type="checkbox"  name="sms_notify" id="sms_notify" ' . $checked_sms . '/></td>
                    <td></td>
                </tr>
                
                <tr valign="top" id="mailchimp_api_key_tw_row">
                    <th scope="row">MailChimp API Key</th>
                    <td><input type="text" name="mailchimp_api_key_tw" id="mailchimp_api_key_tw" value="' . esc_attr(get_option('mailchimp_api_key_tw')) . '" /></td>
                    <td>MailChimp API key of the MailChimp account to retrieve name and phone numbers to send the SMS</td>
                </tr>

                <tr valign="top" id="mailchimp_list_id_tw_row">
                    <th scope="row">MailChimp List ID</th>
                    <td><input type="text" name="mailchimp_list_id_tw" id="mailchimp_list_id_tw" value="' . esc_attr(get_option('mailchimp_list_id_tw')) . '" /></td>
                    <td>The ID of the MailChimp list to be used to retrieve Phone numbers and names.</td>
                </tr>

                <tr valign="top" id="sms_twilio_sid_row">
                    <th scope="row">Twilio SID</th>
                    <td><input type="text" name="sms_twilio_sid" id="sms_twilio_sid" value="' . esc_attr(get_option('sms_twilio_sid')) . '" /></td>
                    <td></td>
                </tr>

                <tr valign="top" id="sms_twilio_auth_token_row">
                    <th scope="row">Twilio Auth Token</th>
                    <td><input type="text" name="sms_twilio_auth_token" id="sms_twilio_auth_token" value="' . esc_attr(get_option('sms_twilio_auth_token')) . '" /></td>
                    <td></td>
                </tr>

                <tr valign="top" id="sms_twilio_number_row">
                    <th scope="row">Twilio Phone Number</th>
                    <td><input type="text" name="sms_twilio_number" id="sms_twilio_number" value="' . esc_attr(get_option('sms_twilio_number')) . '" /></td>
                    <td></td>
                </tr>
                
                <tr valign="top" id="sms_message_row">
                    <th scope="row">SMS message without the post URL</th>
                    <td valign="top"><textarea name="sms_message" id="sms_message">' . esc_attr(get_option('sms_message')) . '</textarea></td>
                    <td>(limit 130 characters for best results for the Non US and Canadian receivers. <a href="https://www.twilio.com/help/faq/sms/does-twilio-support-concatenated-sms-messages-or-messages-over-160-characters" target="_new">Details</a>)</td>
                </tr>
                
            </table>
        </div>';

    // MailChimp and Mandrill settings section
    $checked_email = '';
    if (get_option('email_notify')) {
        $checked_email = "checked=\"checked\"";
    }
    echo'<div class="options_set">
            <div class="sw_plugin_subtitle">Setup Email Notification Settings</div><hr/>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Email Notification</th>
                    <td><input class="check-field" type="checkbox"  name="email_notify" id="email_notify" ' . $checked_email . '/></td>
                    <td></td>
                </tr>

                <tr valign="top" id="mailchimp_api_key_row">
                    <th scope="row">MailChimp API Key</th>
                    <td><input type="text" name="mailchimp_api_key" id="mailchimp_api_key" value="' . esc_attr(get_option('mailchimp_api_key')) . '" /></td>
                    <td></td>
                </tr>

                <tr valign="top" id="mailchimp_list_id_row">
                    <th scope="row">MailChimp List ID</th>
                    <td><input type="text" name="mailchimp_list_id" id="mailchimp_list_id" value="' . esc_attr(get_option('mailchimp_list_id')) . '" /></td>
                    <td>The ID of the MailChimp list to be used to send the email.</td>
                </tr>

                <tr valign="top" id="mandril_api_key_row">
                    <th scope="row">Mandrill API Key</th>
                    <td><input type="text" name="mandril_api_key" id="mandril_api_key" value="' . esc_attr(get_option('mandril_api_key')) . '" /></td>
                    <td></td>
                </tr>
                
                <tr valign="top" id="mandril_template_name_row">
                    <th scope="row">Mandrill Template Name Slug</th>
                    <td><input type="text" name="mandril_template_name" id="mandril_template_name" value="' . esc_attr(get_option('mandril_template_name')) . '" /></td>
                    <td>The template name slug of the Mandril Template that you need to send the email. ex. notification-final-version.</td>
                </tr>
                
                <tr valign="top" id="mandril_template_merge_tag_greeting_row">
                    <th scope="row">Mandrill Template Merge Tag for Greeting</th>
                    <td><input type="text" name="mandril_template_merge_tag_greeting" id="mandril_template_merge_tag_greeting" value="' . esc_attr(get_option('mandril_template_merge_tag_greeting')) . '" /></td>
                    <td>The merge tag of the Mandril Template that you need to insert the greeting.</td>
                </tr>
                
                <tr valign="top" id="mandril_template_merge_tag_link_row">
                    <th scope="row">Mandrill Template Merge Tag for link</th>
                    <td><input type="text" name="mandril_template_merge_tag_link" id="mandril_template_merge_tag_link" value="' . esc_attr(get_option('mandril_template_merge_tag_link')) . '" /></td>
                    <td>The merge tag of the Mandril Template that you need to insert the post link.</td>
                </tr>
                
                <tr valign="top" id="mandril_template_merge_tag_thankyoutext_row">
                    <th scope="row">Mandrill Template Merge Tag for Thank You Text</th>
                    <td><input type="text" name="mandril_template_merge_tag_thankyoutext" id="mandril_template_merge_tag_thankyoutext" value="' . esc_attr(get_option('mandril_template_merge_tag_thankyoutext')) . '" /></td>
                    <td>The merge tag of the Mandril Template that you need to insert the Thank You text.</td>
                </tr>
                
                <tr valign="top" id="email_subject_row">
                    <th scope="row">Email Subject</th>
                    <td><input type="text" name="email_subject" id="email_subject" value="' . esc_attr(get_option('email_subject')) . '" /></td>
                    <td>Subject of the email. Make sure you use non spammy words.</td>
                </tr>
                
                <tr valign="top" id="from_email_row">
                    <th scope="row">From Email</th>
                    <td><input type="text" name="from_email" id="from_email" value="' . esc_attr(get_option('from_email')) . '" /></td>
                    <td>Your email address to be used to send out emails and receive responses for them.</td>
                </tr>
                
                <tr valign="top" id="from_name_row">
                    <th scope="row">From Name</th>
                    <td><input type="text" name="from_name"  id="from_name" value="' . esc_attr(get_option('from_name')) . '" /></td>
                    <td>The name that your email should be sent as.</td>
                </tr>
                
            </table>
        </div>';

    submit_button();

    echo'</form>
</div>';
}

// add action to send notifications
add_action('transition_post_status', 'sdw_notify_new_post', 10, 3);

/**
 * Notify the new post publish
 *
 * This function handles all the notifications when a new post of any category is added. Hooked to
 * "transition_post_status" action. An email is sent to the site admin email address
 * with a success report once all notification methods are invoked.
 * 
 * @uses sdwNotifyAdapter class This class contains all the logic to notify the users
 *
 */
function sdw_notify_new_post($new_status, $old_status, $post) {

    // the notification should be done only when a new post is added
    if (($old_status == 'draft' && $new_status == 'publish')) {

        $response_msg = '';
        $at_least_one_option_selected = FALSE;
        // create instance of the sdwNotifyAdapter class
        $notifier = new sdwNotifyAdapter();

        /**
         * process SMS notification
         */
        // check whether the "Enable SMS Notification" check box is checked
        if (get_option('sms_notify')) {
            $at_least_one_option_selected = TRUE;
            $mailchimp_api_key_tw = esc_attr(get_option('mailchimp_api_key_tw'));
            $mailchimp_list_id_tw = esc_attr(get_option('mailchimp_list_id_tw'));
            $sid = esc_attr(get_option('sms_twilio_sid'));
            $authToken = esc_attr(get_option('sms_twilio_auth_token'));
            $twilio_number = esc_attr(get_option('sms_twilio_number'));
            $message = esc_attr(get_option('sms_message'));
            $numbers_list = array();
            $wp_installation_url = get_site_url();
            $new_post_link = $wp_installation_url . '/?p=' . $post->ID;
            $chunk_size = 4096; //in bytes
            // querying the MailChimp API for the list to retrieve phone numbers. 
            // The fields in the list should be as "Email Address", "First Name"and
            // "Phone" in the same order.
            $url = 'http://us9.api.mailchimp.com/export/1.0/list?apikey=' . urlencode($mailchimp_api_key_tw) . '&id=' . $mailchimp_list_id_tw;
            $handle = @fopen($url, 'r');
            if (!$handle) {
                $response_msg .= "<br/>SMS Notification: failed to access MailChimp url<br/>";
            } else {
                $i = 0;
                $header = array();
                while (!feof($handle)) {
                    $buffer = fgets($handle, $chunk_size);
                    if (trim($buffer) != '') {
                        $obj = json_decode($buffer);
                        if ($i == 0) {
                            //store the header row
                            $header = $obj;
                        } else {
                            // phone => name format
                            $numbers_list[$obj[2]] = $obj[1];
                        }
                        $i++;
                    }
                }
                fclose($handle);
            }
            // call the sendSMS function of the sdwNotifyAdapter class object
            $response_msg .='<br/>SMS Notification: ';
            $response_msg .= $notifier->sendSMS($sid, $authToken, $twilio_number, $message, $numbers_list, $new_post_link);
        }

        /**
         * process Email notification
         */
        // check whether the "Enable Email Notification" check box is checked
        if (get_option('email_notify')) {
            $at_least_one_option_selected = TRUE;
            $notifier = new sdwNotifyAdapter();
            $mailchimp_api_key = esc_attr(get_option('mailchimp_api_key'));
            $mailchimp_list_id = esc_attr(get_option('mailchimp_list_id'));
            $mandril_api_key = esc_attr(get_option('mandril_api_key'));
            $mandril_template_name = esc_attr(get_option('mandril_template_name'));
            $mandril_template_merge_tag_greeting = esc_attr(get_option('mandril_template_merge_tag_greeting'));
            $mandril_template_merge_tag_link = esc_attr(get_option('mandril_template_merge_tag_link'));
            $mandril_template_merge_tag_thankyoutext = esc_attr(get_option('mandril_template_merge_tag_thankyoutext'));
            $email_subject = esc_attr(get_option('email_subject'));
            $from_name = esc_attr(get_option('from_name'));
            $from_email = esc_attr(get_option('from_email'));
            $emails_list = array();
            $wp_installation_url = get_site_url();
            $new_post_link = $wp_installation_url . '/?p=' . $post->ID;
            $chunk_size = 4096; //in bytes
            // querying the MailChimp API for the list to retrieve phone numbers. 
            // The fields in the list should be as "Email Address", "First Name"and
            // "Phone" in the same order.
            $url = 'http://us9.api.mailchimp.com/export/1.0/list?apikey=' . urlencode($mailchimp_api_key) . '&id=' . $mailchimp_list_id;
            $handle = @fopen($url, 'r');
            if (!$handle) {
                $response_msg .= "Email Notification: failed to access mailchimp url<br/>";
            } else {
                $i = 0;
                $header = array();
                while (!feof($handle)) {
                    $buffer = fgets($handle, $chunk_size);
                    if (trim($buffer) != '') {
                        $obj = json_decode($buffer);
                        if ($i == 0) {
                            //store the header row
                            $header = $obj;
                        } else {
                            // name => email format
                            $emails_list[$obj[1]] = $obj[0];
                        }
                        $i++;
                    }
                }
                fclose($handle);
            }

            // add parameters to the array
            $params = array(
                "mandril_api_key" => $mandril_api_key,
                "mandril_template_name" => $mandril_template_name,
                "mandril_template_merge_tag_greeting" => $mandril_template_merge_tag_greeting,
                "mandril_template_merge_tag_link" => $mandril_template_merge_tag_link,
                "mandril_template_merge_tag_thankyoutext" => $mandril_template_merge_tag_thankyoutext,
                "email_subject" => $email_subject,
                "from_name" => $from_name,
                "from_email" => $from_email,
                "emails_list" => $emails_list,
                "new_post_link" => $new_post_link
            );
            // call the sendEmails function of the sdwNotifyAdapter class object
            $response_msg .='<br/>Email Notification: ';
            $response_msg .= $notifier->sendEmails($params);
            //$notifier->sendSMSTest($response_msg);
        }

        // send site admin an email with the notification results if atleast one of the option is used.
        if ($at_least_one_option_selected) {
            $admin_email = get_option('admin_email');
            $new_post_link = $wp_installation_url . '/?p=' . $post->ID;
            $message = '<h2>Notification Results for the latest post</h2>'
                    . '<br/>Following are the results for the post ' . $new_post_link . '<br/><br/>'
                    . $response_msg;
            wp_mail($admin_email, 'Notification Results for the latest post', $message);
        }
    }

    return true;
}
