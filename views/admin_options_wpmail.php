<?php

use NinjaLibs\Ses\Utils;

if (!defined('WPINC')) {
    die;
}

include_once ABSPATH.'wp-admin/includes/template.php';
$details = new ReflectionFunction('wp_mail');
$file = str_replace(ABSPATH.'wp-content/plugins', "", plugin_dir_path($details->getFileName())) ;
$plugin_name = trim($file, "/");

/**
 * TODO: find guzzle conflict and suggest editing this file in a proper vay.
 *  $ref = new ReflectionClass(GuzzleHttp\ClientInterface::class);
    if (!$ref->hasConstant('MAJOR_VERSION') && $ref->hasConstant('VERSION')) {
    }
 */
?>
<div id="universal-message-container">
    <h2>Mail Plugin Conflict</h2>
    <div class="notice inline notice-warning notice-alt">
        <p>
            <b>Error:</b><br />
            Another plugin is already handling mails by defining custom <kbd>wp_mail</kbd> function. You must disable or edit it to be able to use Ninja Libs SES properly.
            <br />
            Plugin name is : <b><?php echo $plugin_name ?></b><br />
            wp_mail is defined in : <b><?php echo $details->getFileName(); ?></b> on line <b><?php echo $details->getStartLine(); ?></b>
        </p>
    </div>
</div>