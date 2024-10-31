<?php

namespace NinjaLibs\Ses;

class Settings
{
    private static $options = null;

    public static function get_option($option_name, $default = false)
    {
        self::get_all_options();
       
        if (isset(self::$options[$option_name])) {
            return self::$options[$option_name];
        }

        return $default;
    }

    public static function get_or_create_option($option_name, $inital_value)
    {
        self::get_all_options();
        if (!isset(self::$options[$option_name])) {
            self::set_option($option_name, $inital_value, true);
        }

        return self::$options[$option_name];
    }

    public static function get_all_options()
    {
        if (self::$options == null) {
            self::$options = get_option('ninjalibs_ses_all_settings', []);
        }

        return self::$options;
    }

    public static function set_option($option_name, $value, $save_immediatelly = true)
    {
        if (self::$options == null) {
            //hmm sth wrong here, let's reload settings to be safe
            self::get_all_options();
        }
        
        self::$options[$option_name] = $value;
        if ($save_immediatelly) {
            self::save_all_options();
        }

        return true;
    }

    public static function save_all_options()
    {
        if (self::$options == null) {
            self::$options = [];
        }

        return update_option('ninjalibs_ses_all_settings', self::$options, true);
    }

    public static function getFromSource()
    {
        return sprintf('"%s" <%s>', self::getFromName(), self::getFromEmail());
    }

    public static function getFromName()
    {
        return self::get_option('from_name', get_option('blogname'));
    }

    public static function getFromEmail()
    {
        if (is_admin() && isset($_POST['test_from'])) {
            return sanitize_email($_POST['test_from']);
        }

        return self::get_option('from_email', get_option('admin_email'));
    }

    public static function getOverrideFromEmail()
    {
        return self::get_option('override_from', 'YES') == 'YES';
    }

    public static function getReplyToEmail()
    {
        return self::get_option('reply_email', get_option('admin_email'));
    }

    public static function getSubscriptionKey()
    {
        return self::get_or_create_option('subscription_key', self::generateRandomString());
    }

    //todo: implement a button to admin page.
    public static function renewSubscriptionKey()
    {
        return self::set_option('subscription_key', self::generateRandomString(), true);
    }

    public static function generateRandomString()
    {
        $str =  base64_encode(openssl_random_pseudo_bytes(24));
        $str = str_replace('+', '', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace('=', '', $str);
        return $str;
    }
}
