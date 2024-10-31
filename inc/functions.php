<?php
// no direct calls, please.
if (!defined('WPINC')) {
    die;
}

function ninjalibs_ses_ses_done()
{
    return ninjalibs_ses_allset();
}

function ninjalibs_ses_sns_done()
{
    //todo: find a way to detect if we confirmed urls
    return ninjalibs_ses_allset();
}

function ninjalibs_ses_test_done()
{
    return true;//find here if an e-mail has been sent
}
function ninjalibs_ses_classfor($is_done)
{
    return $is_done ? '' : 'notice-warning notice-alt';
}

function ninjalibs_ses_create_simple_email($content, $template = "default-email")
{
    return ninjalibs_ses_create_email(['content'=>$content], $template);
}

function ninjalibs_ses_create_email($variables, $template = "default-email")
{
    if (!is_array($variables)) {
        $variables = ['content'=>$variables];
    }
    
    $template = file_get_contents(NINJALIBS_SES_TEMPLATES_DIR.'/'.$template.'.html');
    foreach ($variables as $varName => $varValue) {
        if (strpos($template, '%'.$varName.'%') !== false) {
            $template =  str_replace('%'.$varName.'%', $varValue, $template);
        }
    }

    return $template;
}

/**
 * Checks if all constants are set
 * Means, ready to go!
 */
function ninjalibs_ses_allset()
{
    return
    defined('NINJALIBS_SES_AWS_ACCESS_KEY_ID') &&
    defined('NINJALIBS_SES_AWS_ACCESS_KEY_SECRET') &&
    defined('NINJALIBS_SES_AWS_REGION') &&
    !defined('NINJALIBS_SES_WP_MAIL_ERORR');
}
