<?php
// no direct calls, please.
if (!defined('WPINC')) {
    die;
}

//TODO: delete any other data/options if exists
//TODO: maybe a callback to our server for statistic?

function ninjalibs_ses_uninstall()
{
    ninjalibs_ses_drop_tables();
    ninjalibs_ses_delete_options();

}

 
//TODO: drop tables that created by our plugin.
function ninjalibs_ses_drop_tables()
{
    global $wpdb;
    $table_complaints =  $wpdb->base_prefix.'ninjalibs_ses_blocked';
    $wpdb->query("DROP TABLE {$table_complaints}");

    ninjalibs_ses_delete_options();
}


function ninjalibs_ses_delete_options()
{
    delete_option('ninjalibs_ses_version');
}
