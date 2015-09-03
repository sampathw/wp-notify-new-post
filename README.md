# WP Notify New Post

This plugin notifies the subscribed users through **email and SMS** when a new post is added. You need to have a **[MailChimp](http://www.mailchimp.com/) + [Mandrill](http://www.mandrill.com/) account with a subscriber list and a [Twillio.com](https://www.twilio.com/) account for SMS sending**.

## Installation

1. Unzip and upload the **"wp-notify-new-post"** folder to *<YOUR WORDPRESS INSTALLATION>/wp-content/plugins* folder.
2. Login to Wordpress admin panel, then navigate to Plugins -> Installed Plugins.
3. Look for the plugin name **"WP Notify New Post"** and activate.
4. Once activated, a separate menu will be available in main left menu named as **"WP Notify New Post"**.
5. Click on it and configure the plugin as mentioned in the **"Plugin Configuration"** section below.

## Configuration

#### Setup SMS Notification Settings

1. Create a new [MailChimp](http://www.mailchimp.com/) account if you don't have one. 
2. Once you created an account or if you already have an account, create a new list or modify an existing list with following columns in **exact order**.
⋅⋅* Column 1 - Email address
⋅⋅* Column 2 - First Name
⋅⋅* Column 3 - Phone
3. Setup the list to receive data or import.
4. Obtain the **MailChimp API Key** as described in [this page](http://kb.mailchimp.com/accounts/management/about-api-keys).
5. Obtain the **MailChimp List ID** as described in [this page](http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id).
6. Create a new account in [Twilio]() and setup a **Twilio SID** as described in [this page](https://www.twilio.com/help/faq/twilio-basics/what-is-an-application-sid).
7. Obtain the **Twilio Auth Token** as described in [this page](https://www.twilio.com/help/faq/twilio-basics/what-is-the-auth-token-and-how-can-i-change-it).
8. Buy a Twilio phone number and insert it in the **Twilio Phone Number** field.
9. Setup the SMS message.


#### Setup Email Notification Settings
1. Create a new [MailChimp](http://www.mailchimp.com/) account if you don't have one. 
2. Once you created an account or if you already have an account, create a new list or modify an existing list with following columns in **exact order**. You can use the same list created for SMS notification.
- Column 1 - Email address
- Column 2 - First Name
- Column 3 - Phone 
3. Setup the list to receive data or import.
4. Obtain the **MailChimp API Key** as described in [this page](http://kb.mailchimp.com/accounts/management/about-api-keys).
5. Obtain the **MailChimp List ID** as described in [this page](http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id).
6. Create a new [Mandrill](http://www.mandrill.com/) account and create a new template as described in [this page](https://mandrill.zendesk.com/hc/en-us/articles/205582507-Getting-Started-with-Templates).
7. Use the following template as a start and keep all the **"merge tags"** as it is since the plugin uses the specific merge tag names to insert the dynamic content.
```html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Email template</title>

<style type="text/css">
		body{
			background:#666;
			font-family:Arial, Helvetica, sans-serif;
		}
</style></head>

<body>
<table style="width:600px; background:#FFF; margin-top:20px; font-size:14px;  margin-bottom:20px;" align="center" cellpadding="0" cellspacing="0">
    <tr><td style="padding:10px; text-align:center;"><h2>Your Company Name or Logo here</h2></td></tr>
    <tr>
        <td style="background:#e7e7e9; padding:20px; font-size:18px;" mc:edit="greeting">
        	hk
        </td>
    </tr>
    
	
    <tr><td style="padding:20px; background:#2c64b0; color:#FFF;" mc:edit="link"></td></tr>
	<tr>
        <td style="padding:20px; color:#434141;" mc:edit="thankyoutext">
        	
Thank you,<br>
<a href="http://birminghamwholesalehomes.com" target="_blank" style="color:#f1ac52; text-decoration:none;"><strong>birminghamwholesalehomes.com</strong></a>
        </td>
    </tr>
   
</table>
</body>
</html>
```
8. Go to [Settings page](https://mandrillapp.com/settings) to create a new **Mandrill API Key**.
9. Add the **Mandrill Template Name Slug**.
10. Add **Mandril Template Merge Tags for Greeting, Link and Thank You Text**. In the default template they are setup as "greeting", "link", "thankyoutext" respectively. 
11. Add **Email Subject**, **From Email** and **From Name**.

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History

1.0.0 - Initial Version - Sep 01, 2015

## Credits

To the amazing contributors of Twilio and Mandrill API libraries.

## License
 
GPLv2 or later
