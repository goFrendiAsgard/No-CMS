[Up](../tutorial.md)

Installation
============

* __I got this warning message on installation:__

    ```
        Rewrite Base is possibly not activated, this is needed when you choose to hide index.php.
If you are sure that your mod_rewrite is activated, you can continue at your own risk
    ```
    __I ignore it, and the installation was succeed, but now I cannot open any page. How to fix it?__

    - Delete your `/.htaccess` file
    - Open up `/application/config/routes.php`, change this line:

        ```php
            $config['index_page'] = '';
        ```

        into this:

        ```php
            $config['index_page'] = 'index.php';
        ```

* __I got this warning message on installation:__

    ```
        Rewrite Base is possibly not activated, this is needed when you choose to hide index.php.
If you are sure that your mod_rewrite is activated, you can continue at your own risk
    ```
    __I really want to hide index.php. I have a ssh access to the server since I own it by myself. How can I install mod_rewrite?__

    Do this:

    ```bash
        sudo a2enmod rewrite
sudo service apache2 restart
    ```

Deleting unnecessary files
==========================

Some files and directories can be deleted if you don't need some features.

* __/reset-installation.sh__
* __/.gitignore__
* __/assets/grocery_crud/themes/datatables/__
* __/assets/grocery_crud/themes/flexigrid/__
* __/assets/grocery_crud/themes/twitter-bootstrap/__
* __/tests/__

Some documentations can also be deleted safely:

* __/developer-note.md__
* __/doc/__
* __/template_user_guide/__
* __/ci_user_guide/__

If you don't use `multisite` feature, you can delete installation folders:

* __/application/config/first-time/__

    This folder contains default CodeIgniter's configuration files.

* __/modules/installer/__

    This folder contains No-CMS installation script

After deactivate any unused modules (`CMS Management | Modules`), you can also safely remove the modules in

* __/modules/__

    Be careful to not delete any modules in used. Also, never delete `main` module at any cost.

You can also delete any unused themes (except `neutral` theme) in

* __/themes/__

Third Party Authentication
==========================

* __I didn't enable third party authentication while installing No-CMS, since I thought I wouldn't need it. Now I change my mind. How could I re-enable third party authentication?__

    Go to `CMS Management | Setting`, click Third party authentication tab.

* __How could I get my `facebook_app_id` and `facebook_app_secret`?__

    Open up http://developer.facebook.com. Create new app if you don't have any. Fill the setting. Now you should get `facebook_app_id` and `facebook_app_secret`. The similar things also applied twitter, google, yahoo, live etc.

Google Analytic
===============

* __How could I enable google analytic in No-CMS?__

    Go to `CMS Management | Configuration Management`, look for `cms_google_analytic_property_id` (it is on page 2).
    Edit it, fill the Configuration value with your google analytic property id.

* __How could I know my google analytic property id?__

    Open up [https://www.google.com/analytics/web/?hl=en](https://www.google.com/analytics/web/?hl=en). Look for something like `UA-xxxxxxx-x`

Email sending
=============

* __I want a user to click on activation link before register. The activation link should be sent to their email. How could I do this?__

    First of all, set up your email configuration correctly, then go to `CMS Management | Configuration Management`, look for `cms_signup_activation`. Edit it, fill the Configuration value with `TRUE`.

* __The forgot password feature doesn't work. How could I enable this?__

    Set up your email configuration correctly.

* __How could I set up my email configuration correctly?__

    Simply go to `CMS Management | Setting`. or

    Configure these configuration values (`CMS Management | Configuration Management`):

    | Configuration Key         | Configuration Value                                                                                                                                                          |
    | :------------------------ | :--------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |   
    | cms_email_reply_address   | Your email address or something like `no-reply@your_website.com`                                                                                                             |
    | cms_email_reply_name      | Your name, or something like `admin of your_website`                                                                                                                         |
    | cms_email_forgot_subject  | Forgot-password email subject, something like `Reactivate your account at your_website.com`                                                                                  |
    | cms_email_forgot_message  | Forgot-password email message.                                                                                                                                               |
    | cms_email_signup_subject  | Activation email subject                                                                                                                                                     |
    | cms_email_signup_message  | Activation email message                                                                                                                                                     |
    | cms_email_useragent       | Codeigniter                                                                                                                                                                  |
    | cms_email_protocol        | Usually `smtp`. You can also use `mail` or `sendmail`, depend on your server configuration                                                                                   |
    | cms_email_mailpath        | If you choose `mail` or `sendmail` in previous configuration, this should be point to your sendmail file location. Typically it is `/usr/sbin/sendmail` on linux server      |
    | cms_email_smtp_host       | Your smtp host. If you use gmail, it should be `ssl://smtp.googlemail.com`                                                                                                   |
    | cms_email_smtp_user       | Your smtp user. If you use gmail, it should be `your_gmail_address@gmail.com`                                                                                                |
    | cms_email_smtp_pass       | Your smtp password. If you use gmail, it should be your gmail password.                                                                                                      |
    | cms_email_smtp_port       | Your smtp port. Gmail use `465`                                                                                                                                              |
    | cms_email_smtp_timeout    | smtp time out, let it be `30`                                                                                                                                                |
    | cms_email_wordwrap        | Set it `TRUE` if you want your email word-wrapped, or set it `FALSE` otherwise.                                                                                              |
    | cms_email_wrapchars       | How many character to activate word-wrap. Only usefull if you set `cms_email_wordwrap` into `TRUE`. By default, it is `76`                                                   |
    | cms_email_mailtype        | Let it be `html`                                                                                                                                                             |
    | cms_email_charset         | Let it be `utf-8`                                                                                                                                                            |
    | cms_email_validate        | `FALSE` if you don't want to validate email address, `TRUE` otherwise                                                                                                        |
    | cms_email_priority        | Let it be `3`                                                                                                                                                                |
    | cms_email_bcc_batch_mode  | Let it be `FALSE`                                                                                                                                                            |
    | cms_email_bcc_batch_size  | Let it be `200`                                                                                                                                                              |

How could I add a quicklink that run a javascript function?
===========================================================

Go to `CMS Management | Setting`, click `Page Partials` tab, paste this script into `custom script` textarea:

```javascript
$(document).ready(function(){
    var additional_quicklink = '<li class="dropdown"><a id="qlink" href="#">Test</a></li>';
    $('#_top_navigation>div.container>nav>ul.navbar-nav:first').append(additional_quicklink);
    $('#qlink').click(function(event){
        window.alert("Hi hi hi");
        event.preventDefault(); 
    });
});
```
