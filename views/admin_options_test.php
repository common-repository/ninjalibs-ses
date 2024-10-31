<?php
// no direct calls, please.

use NinjaLibs\Ses\Utils;

if (!defined('WPINC')) {
    die;
}
$current_user = wp_get_current_user();

if (isset($_POST['submit'])) {
    if (!wp_verify_nonce($_POST['ninjalibs-test-email'], 'ninjalibs-test-email')) {
        wp_die('Malformed Request', 'Malrformed Request');
    }

    $test_to = sanitize_email($_POST['test_to']);
    $test_content = sanitize_post($_POST['test_content']);
    
    wp_mail($test_to, 'Ninja Libs Test E-Mail', ninjalibs_ses_create_email($test_content));
    add_settings_error('testemail', 'email_sent', __('E-mail sent, please check your inbox.'), 'updated');
    settings_errors();
}
 $verified_emails = Utils::getVerifiedEmails();
?>
<form method="post" action="<?php echo esc_html(admin_url('admin.php?page=ninjalibs-ses&tab=test')); ?>">
        <div id="universal-message-container">
            <h2>Test</h2>
            <div class="options">
                <p>
                    <label><?php _e("From Email", "ninjalibs-ses")?></label>
                    <br />
                    <?php if ($verified_emails): ?>
                    <select name="test_from">
                        <?php foreach ($verified_emails as $email):?>
                        <option <?php if (isset($_POST['test_from']) && $_POST['test_from'] == $email):?>selected<?php endif;?>><?php echo $email; ?></option>
                        <?php endforeach; ?>
                    </select>
                        <?php else:?>
                        <span style="color: red;">Please verify emails to test.</span> Help LINK HERE
                        <?php endif;?>
                </p>
                <p>
                    <label><?php _e("To Email", "ninjalibs-ses")?></label>
                    <br />
                    <input type="text" name="test_to" value="<?php echo $current_user->user_email; ?>" class="regular-text"  />
                </p>

                <p>
                    <label><?php _e("Message", "ninjalibs-ses");?></label>
                    <br />
                   <?php
                    $settings = array(
                        'teeny' => false,
                        'textarea_rows' => 15,
                        'tabindex' => 1
                    );
                    wp_editor("<b>Congratulations {$current_user->display_name}!</b><br /><br /> You managed to get e-mail using Amazon SES. <br /><br /> ~ Ninja Libs", 'test_content', $settings);
                   ?>
                </p>
        </div>
        <?php
        
            wp_nonce_field('ninjalibs-test-email', 'ninjalibs-test-email');
            if ($verified_emails) {
                submit_button(__("Send e-mail", "ninjalibs-ses"));
            }
        ?>
    </form>