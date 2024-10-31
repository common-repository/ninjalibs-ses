<?php
// no direct calls, please.
if (!defined('WPINC')) {
    die;
}
 require_once plugin_dir_path(__FILE__) . 'upgrade.php';
ninjalibs_ses_upgrade();