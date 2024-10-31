=== Ninja Libs Amazon SES ===
Contributors: ninjalibs, freemius
Tags: amazon,ses,email
Requires at least: 4+
Tested up to: 6.0.1
Requires PHP: 7+
Stable tag: trunk
License: GPL-3

This plugin helps you to fully integrate your wordpress to Amazon SES as the mail delivery system.

== Description ==
Ninja Libs SES E-mail plugin helps you to fully integrate your wordpress to Amazon SES as the mail delivery system.
To have better integration with SES & SNS like handling Complaings & Bounces Try our Premium version.

Ninja Libs SES is easy to configure and start using immediatelly. For better security, Ninja Libs SES uses wp-config.php to store your secret keys.
Once you defined those mandatory constants, your ses-lib will be enabled and you can test sending e-mail.

In `wp-config.php` file, define those 3 constants


    <?php
    define('NINJALIBS_SES_AWS_ACCESS_KEY_ID','YOUR AWS ACCESS KEY ID');
    define('NINJALIBS_SES_AWS_ACCESS_KEY_SECRET', 'YOUR AWS ACCESS KEY SERCERT');
    define('NINJALIBS_SES_AWS_REGION', 'us-east-1'); // region
    
SES Config:

If you use same Aws account for multiple websites/domains. You can add a REGEX filter to your wp-config file to prevent domain leak to the admins.

    define('NINJALIBS_SES_DOMAIN_MATCH','/ninjalibs.com$/'); // default '*'

this will hide any e-mail or domain from the admin panel that does not end with ninjalibs.com


SNS Configuration:

Once you properly set your Access Key and Secret Key, NinjaLibs SES will start sending emails.
In SNS Config tab, you will see secret endpoint for your SNS subscription. 
Create topic for Bounces & Complaints, add urls as subscription and NinjaLibs will help you to confirm your subscription and will handle the rest!


== Installation ==
Ninja Libs SES is easy to configure and start using immediatelly. For better security, Ninja Libs SES uses wp-config.php to store your secret keys.
Once you defined those mandatory constants, your ses-lib will be enabled and you can test sending e-mail.

In `wp-config.php` file, define those 3 constants


    <?php
    define('NINJALIBS_SES_AWS_ACCESS_KEY_ID','YOUR AWS ACCESS KEY ID');
    define('NINJALIBS_SES_AWS_ACCESS_KEY_SECRET', 'YOUR AWS ACCESS KEY SERCERT');
    define('NINJALIBS_SES_AWS_REGION', 'us-east-1'); // region

SES Config:

If you use same Aws account for multiple websites/domains. You can add a REGEX filter to your wp-config file to prevent domain leak to the admins.

    define('NINJALIBS_SES_DOMAIN_MATCH','/ninjalibs.com$/'); // default '*'

this will hide any e-mail or domain from the admin panel that does not end with ninjalibs.com


SNS Configuration:

Once you properly set your Access Key and Secret Key, NinjaLibs SES will start sending emails.
In SNS Config tab, you will see secret endpoint for your SNS subscription. 
Create topic for Bounces & Complaints, add urls as subscription and NinjaLibs will help you to confirm your subscription and will handle the rest!

== Changelog ==
Inital version

== Upgrade Notice ==
Upgrade and start using it immediately.