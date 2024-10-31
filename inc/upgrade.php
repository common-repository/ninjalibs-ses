<?php
// no direct calls, please.
if (!defined('WPINC')) {
    die;
}

function ninjalibs_ses_upgrade()
{
    $current_version = get_option('ninjalibs_ses_version', '0');
    if (version_compare($current_version, NINJALIBS_SES_VERSION, '=')) {
        //ok we don't need upgrate at all
        return;
    }
    
    if (version_compare($current_version, '0.0.1', '<')) {
        ninjalibs_ses_v001();
    }

    if (version_compare($current_version, '0.0.4', '<')) {
        ninjalibs_ses_v004();
    }

    update_option('ninjalibs_ses_version', NINJALIBS_SES_VERSION);
}

function ninjalibs_ses_v001()
{
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    $table_complaints =  $wpdb->base_prefix.'ninjalibs_ses_blocked';
    
    //TODO: create SES bounce table
    $sql = "CREATE TABLE {$table_complaints} (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				email varchar(255) NOT NULL,
				block_reason ENUM('bounce','complaint','unsubscribe','removed','unknown') DEFAULT 'unknown' NOT NULL,
                block_date timestamp not null default current_timestamp,
				PRIMARY KEY  (id)
				) $charset_collate;";
      
    $wpdb->query($sql);
}

function ninjalibs_ses_v004()
{
    global $wpdb;
    $table_complaints =  $wpdb->base_prefix.'ninjalibs_ses_blocked';
    $sql = "CREATE UNIQUE INDEX ndx_ninjalibs_blocked_email ON `$table_complaints`(`email`);";
    $wpdb->query($sql);
}
