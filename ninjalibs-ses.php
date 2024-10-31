<?php

/*
Plugin Name: Ninja Libs SES
Plugin URI: https://ninjalibs.com/ses
Description: Fully integrated SES plugin
Author: Ninja Libs
Text Domain: ninjalibs
Domain Path: /ninjalibs
Version: 0.1.1
Author URI: https://ninjalibs.com
*/
// no direct calls, please.
if ( !defined( 'WPINC' ) ) {
    die;
}
use  NinjaLibs\Ses\Settings as SesSettings ;
use  NinjaLibs\Ses\Utils as SesUtils ;

if ( function_exists( 'ninja_fs' ) ) {
    ninja_fs()->set_basename( false, __FILE__ );
} else {
    define( 'NINJALIBS_SES_VERSION', '0.0.9' );
    define( 'NINJALIBS_SES_PLUGIN_DIR', __DIR__ );
    define( 'NINJALIBS_SES_INC_DIR', NINJALIBS_SES_PLUGIN_DIR . '/inc' );
    define( 'NINJALIBS_SES_VIEW_DIR', NINJALIBS_SES_PLUGIN_DIR . '/views' );
    define( 'NINJALIBS_SES_TEMPLATES_DIR', NINJALIBS_SES_PLUGIN_DIR . '/templates' );
    require_once __DIR__ . '/vendor/autoload.php';
    
    if ( !function_exists( 'ninja_fs' ) ) {
        // Create a helper function for easy SDK access.
        function ninja_fs()
        {
            global  $ninja_fs ;
            
            if ( !isset( $ninja_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $ninja_fs = fs_dynamic_init( array(
                    'id'             => '6999',
                    'slug'           => 'ninjalibs-ses',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_032fe4d22a230f30f8b525ba54d90',
                    'is_premium'     => false,
                    'premium_suffix' => 'Premium',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => false,
                ),
                    'menu'           => array(
                    'slug'    => 'ninjalibs-ses',
                    'support' => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $ninja_fs;
        }
        
        // Init Freemius.
        ninja_fs();
        // Signal that SDK was initiated.
        do_action( 'ninja_fs_loaded' );
    }
    
    function ninja_fs_custom_connect_message_on_update(
        $message,
        $user_first_name,
        $plugin_title,
        $user_login,
        $site_link,
        $freemius_link
    )
    {
        return sprintf(
            __( 'Hey %1$s' ) . ',<br>' . __( 'Please help us improve %2$s! If you opt-in, some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'ninjalibs-ses' ),
            $user_first_name,
            '<b>' . $plugin_title . '</b>',
            '<b>' . $user_login . '</b>',
            $site_link,
            $freemius_link
        );
    }
    
    ninja_fs()->add_filter(
        'connect_message_on_update',
        'ninja_fs_custom_connect_message_on_update',
        10,
        6
    );
    include __DIR__ . '/inc/functions.php';
    /**
     * Utils activation.
     */
    function ninjalibs_ses_activate()
    {
        require_once __DIR__ . '/inc/activator.php';
    }
    
    /**
     * Utils Deactivation
     */
    function ninjalibs_ses_deactivate()
    {
        require_once __DIR__ . '/inc/deactivator.php';
    }
    
    register_activation_hook( __FILE__, 'ninjalibs_ses_activate' );
    register_deactivation_hook( __FILE__, 'ninjalibs_ses_deactivate' );
    add_action( 'plugins_loaded', 'ninjalibs_ses_activate' );
    if ( is_admin() ) {
        require_once __DIR__ . '/inc/admin.php';
    }
    if ( !function_exists( 'ninjalibs_ses_mail' ) ) {
        function ninjalibs_ses_mail(
            $to,
            $subject,
            $message,
            $headers = '',
            $attachments = array()
        )
        {
            $SESClient = SesUtils::getSesClient();
            $mail_data = array(
                'Source'           => SesSettings::getFromSource(),
                'Destination'      => array(
                'ToAddresses' => array( $to ),
            ),
                'Message'          => array(
                'Subject' => array(
                'Data'    => $subject,
                'Charset' => 'UTF-8',
            ),
                'Body'    => array(
                'Text' => array(
                'Data'    => strip_tags( $message ),
                'Charset' => 'UTF-8',
            ),
                'Html' => array(
                'Data'    => $message,
                'Charset' => 'UTF-8',
            ),
            ),
            ),
                'ReplyToAddresses' => array( SesSettings::getReplyToEmail() ),
            );
            $SESClient->sendEmail( $mail_data );
            return true;
        }
    
    }
    
    if ( !function_exists( 'wp_mail' ) ) {
        function wp_mail(
            $to,
            $subject,
            $message,
            $headers = '',
            $attachments = array()
        )
        {
            try {
                ninjalibs_ses_mail(
                    $to,
                    $subject,
                    $message,
                    $headers,
                    $attachments
                );
            } catch ( \Exception $e ) {
                //TODO: log mail errors here.
                //yeah we will have an error log table
            }
        }
    
    } else {
        if ( !defined( 'NINJALIBS_SES_WP_MAIL_ERORR' ) ) {
            define( 'NINJALIBS_SES_WP_MAIL_ERORR', true );
        }
    }
    
    function ninja_fs_uninstall_cleanup()
    {
        include_once __DIR__ . '/inc/uninstaller.php';
        ninjalibs_ses_uninstall();
    }
    
    ninja_fs()->add_action( 'after_uninstall', 'ninja_fs_uninstall_cleanup' );
}
