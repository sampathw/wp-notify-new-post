<?php

/**
 * Handles all the functions related to the plugin
 *
 * This class has been designed according to the adapter pattern which
 * communicates with various 3rd party APIS used by the plugin. This class makes
 * sure that it is adaptive according to the changes happening in the APIs
 * without affecting the functionality of the plugin.
 *
 * Each function is commented with the last updated date to make sure that
 * the plugin is updated with the latest version of the relevant third party API.
 *
 * @author Sampath Wijeratne
 *
 * @since 1.0.0
 *
 * @return multiple data types according to the function.
 */
class sdwNotifyAdapter {

    /**
     * Notify new post through SMS using Twillio API
     *
     * The numbers list must be passed to this function which will send the
     * message to the passed list using Twillio API. It is assumed that
     * the fields in the list should be as "Email Address", "First Name"and
     * "Phone" in the same order.
     *
     * Last Updated: Aug 28, 2015
     *
     * :param string $sid: Twilio SID
     * :param string $authToken: Twilio Auth Token
     * :param string $twilio_number: Twilio phone number to be used in the account
     * :param string $message: Message to be sent
     * :param array $number_list: The numbers list array with 'number'=> 'name' format
     * :param string $new_post_link:
     *
     * :return: The result message of the execution of this function
     * :returntype: string
     */
    public function sendSMS($sid, $authToken, $twilio_number, $message, $numbers_list, $new_post_link) {

        require 'libraries/twilio/Twilio.php';
        // instantiate a new Twilio Rest Client
        $client = new Services_Twilio($sid, $authToken);
        $status = FALSE;
        foreach ($numbers_list as $number => $name) {

            $sms = $client->account->messages->sendMessage(
                    // Change the 'From' number below to be a valid Twilio number
                    // that you've purchased, or the (deprecated) Sandbox number
                    $twilio_number,
                    // the number we are sending to - Any phone number
                    $number,
                    // the sms body
                    'Hello ' . $name . ', ' . $message . 'Visit: ' . $new_post_link
            );

            $response = json_decode($sms, true);
            if (!($response[0]['error_code'] == NULL || is_null($response[0]['error_code']))) {
                $status = TRUE;
            } else {
                $status = FALSE;
            }
        }

        if ($status) {
            return "SMSs sent successfully";
        } else {
            return "SMSs were not sent. Please check with your Twilio credentials.";
        }
    }


    /**
     * Notify new post through Mandril+MailChimp APIs
     *
     * The numbers list must be passed to this function which will send the
     * message to the passed list using Twillio API. It is assumed that
     * the fields in the list should be as "Email Address", "First Name"and
     * "Phone" in the same order.
     *
     * Last Updated: Aug 28, 2015
     *
     * :param string $sid: Twilio SID
     * :param string $authToken: Twilio Auth Token
     * :param string $twilio_number: Twilio phone number to be used in the account
     * :param string $message: Message to be sent
     * :param array $number_list: The numbers list array with 'number'=> 'name' format
     * :param string $new_post_link:
     *
     * :return: The result message of the execution of this function
     * :returntype: string
     */
    public function sendEmails($params) {

        // load Mandrill library
        require 'libraries/mandrill/Mandrill.php';
        $toarray = array();
        $mergevars = array();
        $i = 0;

        // iterate through each list and setup the messge array to be processed
        foreach ($params['emails_list'] as $key => $value) {
            // setup sending email addresses
            $toarray[$i]['email'] = $value;
            $toarray[$i]['name'] = $key;
            $toarray[$i]['type'] = 'bcc';

            $mergevars[$i]['rcpt'] = $value;
            $mergevars[$i]['vars'] = array(
                array(
                    'name' => 'FIRSTNAME',
                    'content' => $key
                )
            );

            $i++;
        }

        try {
            $mandrill = new Mandrill($params['mandril_api_key']);
            $message = array(
                'html' => '<p>Hello,<br/> We have added our latest post. Please check ' . $params['new_post_link'] . '.</p>',
                'text' => 'Hello, We have added our latest post. Please check. ' . $params['new_post_link'],
                'subject' => $params['email_subject'],
                'from_email' => $params['from_email'],
                'from_name' => $params['from_name'],
                'to' => $toarray,
                'headers' => array('Reply-To' => $params['from_email']),
                'important' => true,
                'track_opens' => null,
                'track_clicks' => null,
                'merge' => true,
                'merge_language' => 'mailchimp',
                'global_merge_vars' => '',
                'merge_vars' => $mergevars
            );
            $template_name = $params['mandril_template_name'];
            $template_content = array(
                array(
                    'name' => $params['mandril_template_merge_tag_greeting'],
                    'content' => '<strong>Hey *|FIRSTNAME|*, </strong><br><br>We have published a new post for you!'),
                array(
                    'name' => $params['mandril_template_merge_tag_link'],
                    'content' => 'Please visit<br><a href="' . $params['new_post_link'] . '" style="color:#f1ac52;"><strong>' . $params['new_post_link'] . '</strong></a>'),
                array(
                    'name' => $params['mandril_template_merge_tag_thankyoutext'],
                    'content' => 'Thank you,<br><strong>yoursitename.com</strong>')
            );
            $async = false;
            $ip_pool = 'Main Pool';
            $result = $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async, $ip_pool);
            $val = serialize($result);
            return 'Success!<br/>Details: ' . $val;
        } catch (Mandrill_Error $e) {
            // Mandrill errors are thrown as exceptions
            return 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
        }
    }

}
