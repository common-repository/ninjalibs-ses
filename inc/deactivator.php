<?php
// no direct calls, please.
if (!defined('WPINC')) {
    die;
}
//flush cache etc.
if (defined('NINJALIBS_DEBUG') && NINJALIBS_DEBUG) {
    include_once dirname(__FILE__).'/uninstaller.php';
    ninjalibs_ses_uninstall();
}
