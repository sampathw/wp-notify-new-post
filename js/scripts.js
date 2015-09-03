/**
 * Handles all the JavaScript functions related to the plugin
 * 
 * @author Sampath Wijeratne
 *
 * @since 1.0.0
 * 
 */

jQuery(document).ready(function ($) {

    // show hide sections on page load
    if ($('#sms_notify').prop("checked")) {
        show_sms_section();
    } else {
        hide_sms_section();
    }

    if ($('#email_notify').prop("checked")) {
        show_email_section();
    } else {
        hide_email_section();
    }

    function hide_sms_section() {
        $('#mailchimp_api_key_tw_row').hide(200);
        $('#mailchimp_list_id_tw_row').hide(200);
        $('#sms_twilio_sid_row').hide(200);
        $('#sms_twilio_auth_token_row').hide(200);
        $('#sms_twilio_number_row').hide(200);
        $('#sms_message_row').hide(200);
    }

    function show_sms_section() {
        $('#mailchimp_api_key_tw_row').show(200);
        $('#mailchimp_list_id_tw_row').show(200);
        $('#sms_twilio_sid_row').show(200);
        $('#sms_twilio_auth_token_row').show(200);
        $('#sms_twilio_number_row').show(200);
        $('#sms_message_row').show(200);
    }

    function hide_email_section() {
        $('#mailchimp_api_key_row').hide(200);
        $('#mailchimp_list_id_row').hide(200);
        $('#mandril_api_key_row').hide(200);
        $('#mandril_template_name_row').hide(200);
        $('#mandril_template_merge_tag_greeting_row').hide(200);
        $('#mandril_template_merge_tag_link_row').hide(200);
        $('#mandril_template_merge_tag_thankyoutext_row').hide(200);
        $('#email_subject_row').hide(200);
        $('#from_email_row').hide(200);
        $('#from_name_row').hide(200);
    }

    function show_email_section() {
        $('#mailchimp_api_key_row').show();
        $('#mailchimp_list_id_row').show();
        $('#mandril_api_key_row').show();
        $('#mandril_template_name_row').show();
        $('#mandril_template_merge_tag_greeting_row').show();
        $('#mandril_template_merge_tag_link_row').show();
        $('#mandril_template_merge_tag_thankyoutext_row').show();
        $('#email_subject_row').show();
        $('#from_email_row').show();
        $('#from_name_row').show();
    }

    // make relevant form components required when the check box is clicked
    $('input[type="checkbox"]').click(function () {
        if ($(this).attr('id') == 'sms_notify') {
            if ($('#sms_notify').prop("checked")) {
                show_sms_section();
                $("#mailchimp_api_key_tw").prop('required', true);
                $("#mailchimp_list_id_tw").prop('required', true);
                $("#sms_twilio_sid").prop('required', true);
                $("#sms_twilio_auth_token").prop('required', true);
                $("#sms_twilio_number").prop('required', true);
                $("#sms_message").prop('required', true);
            }
            else {
                hide_sms_section();
                $("#mailchimp_api_key_tw").prop('required', false);
                $("#mailchimp_list_id_tw").prop('required', false);
                $("#sms_twilio_sid").prop('required', false);
                $("#sms_twilio_auth_token").prop('required', false);
                $("#sms_twilio_number").prop('required', false);
                $("#sms_message").prop('required', false);
            }
        }

        if ($(this).attr('id') == 'email_notify') {
            if ($('#email_notify').prop("checked")) {
                show_email_section();
                $("#mailchimp_api_key").prop('required', true);
                $("#mailchimp_list_id").prop('required', true);
                $("#mandril_api_key").prop('required', true);
                $("#mandril_template_name").prop('required', true);
                $("#mandril_template_merge_tag_greeting").prop('required', true);
                $("#mandril_template_merge_tag_link").prop('required', true);
                $("#mandril_template_merge_tag_thankyoutext").prop('required', true);
                $("#email_subject").prop('required', true);
                $("#from_email").prop('required', true);
                $("#from_name").prop('required', true);
            }
            else {
                hide_email_section();
                $("#mailchimp_api_key").prop('required', false);
                $("#mailchimp_list_id").prop('required', false);
                $("#mandril_api_key").prop('required', false);
                $("#mandril_template_name").prop('required', false);
                $("#mandril_template_merge_tag_greeting").prop('required', false);
                $("#mandril_template_merge_tag_link").prop('required', false);
                $("#mandril_template_merge_tag_thankyoutext").prop('required', false);
                $("#email_subject").prop('required', false);
                $("#from_email").prop('required', false);
                $("#from_name").prop('required', false);
            }
        }

    });
});