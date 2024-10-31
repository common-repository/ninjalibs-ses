<?php
// no direct calls, please.

use NinjaLibs\Ses\Settings as NinjaLibsSesSettings;
use NinjaLibs\Ses\Utils as SesUtils;

if (!defined('WPINC')) {
    die;
}

if (isset($_POST['ninjalibs-general-save'])) {
    $verify = wp_verify_nonce($_POST['ninjalibs-general-save'], 'ninjalibs-general-save');
    
    if (!$verify) {
        wp_die("Sorry, we can not process your request", "Malformed Request");
    }

    $from_email =  sanitize_email($_POST['from_email']);
    $reply_email =  sanitize_email($_POST['reply_email']);
    $override_from = $_POST['override_from'] === "YES" ? "YES" : "NO";
    $from_name = sanitize_text_field($_POST['from_name']);
   

    NinjaLibsSesSettings::set_option('from_name', $from_name, false);
    NinjaLibsSesSettings::set_option('from_email', $from_email, false);
    NinjaLibsSesSettings::set_option('override_from', $override_from, false);
    NinjaLibsSesSettings::set_option('reply_email', $reply_email, false);
    NinjaLibsSesSettings::save_all_options();
    //show someting like, all saved.?
    add_settings_error('general', 'settings_updated', __('Settings saved.'), 'updated');
    settings_errors();
}

?>
<form method="post" class="disabled"  action="<?php echo esc_html(admin_url('admin.php?page=ninjalibs-ses&tab=general')); ?>">
        <div id="universal-message-container">
            <h2>General</h2>

         <table class="form-table" role="presentation">
        <tbody>
        <tr>
            <th><label for="from_name">From Name</label></th>
            <td> <input name="from_name" id="from_name" type="text" value="<?php echo NinjaLibsSesSettings::getFromName(); ?>" class="regular-text code"></td>
        </tr>

        <tr>
            <th><label for="from_email">From E-mail</label></th>
            <td> 
             <?php
             $verified_emails = SesUtils::getVerifiedEmails();
             if ($verified_emails): ?>
                    <select name="from_email" id="from_email">
                        <?php foreach ($verified_emails as $email):?>
                        <option <?php if (NinjaLibsSesSettings::getFromEmail() == $email): ?>selected<?php endif;?>><?php echo $email; ?></option>
                        <?php endforeach; ?>
                    </select>
                        <?php else:?>
                        <span style="color: red;">Please verify emails to test.</span> Help LINK HERE
                        <?php endif;?>    
            </td>
        </tr>
        
       <tr>
           <th scope="row">Override from address for all e-mails</th><td>		
           <div class="classic-editor-options">
           <?php
            $override =  NinjaLibsSesSettings::getOverrideFromEmail();
           ?>
			<p>
             <input type="radio" name="override_from" id="classic-editor-allow" value="YES" <?php if ($override):?>checked<?php endif;?>>
				<label for="classic-editor-allow">Yes</label>
			</p>
			<p>
				<input type="radio" name="override_from" id="classic-editor-disallow" value="NO" <?php if (!$override):?>checked<?php endif;?>>
				<label for="classic-editor-disallow">No</label>
			</p>
		    </div>
            </td>
        </tr>
        <tr>
            <th><label for="reply_email">Reply-To E-mail</label></th>
            <td> <input name="reply_email" id="reply_email" type="text" value="<?php echo NinjaLibsSesSettings::getReplyToEmail();?>" class="regular-text code"></td>
        </tr>
    
        </tbody>
        </table>
            </div>
        <?php
            wp_nonce_field('ninjalibs-general-save', 'ninjalibs-general-save');
            submit_button();
        ?>
</form>