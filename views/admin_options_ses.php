<?php
// no direct calls, please.

use NinjaLibs\Ses\Utils;

if (!defined('WPINC')) {
    die;
}
$ses_regions = [
    ["region"=>"us-east-1","name"=>"us-east-1 - US East (N. Virginia)"],
    ["region"=>"us-east-2","name"=>"us-east-2 - US East (Ohio)"],
    ["region"=>"us-west-2","name"=>"us-west-2 - US West (Oregon)"],
    ["region"=>"ap-south-1","name"=>"ap-south-1 - Asia Pacific (Mumbai)"],
    ["region"=>"ap-northeast-2","name"=>"ap-northeast-2 - Asia Pacific (Seoul)"],
    ["region"=>"ap-southeast-1","name"=>"ap-southeast-1 - Asia Pacific (Singapore)"],
    ["region"=>"ap-southeast-2","name"=>"ap-southeast-2 - Asia Pacific (Sydney)"],
    ["region"=>"ap-northeast-1","name"=>"ap-northeast-1 - Asia Pacific (Tokyo"],
    ["region"=>"ca-central-1","name"=>"ca-central-1 - Canada (Central"],
    ["region"=>"eu-central-1","name"=>"eu-central-1 - Europe (Frankfurt)"],
    ["region"=>"eu-west-1","name"=>"eu-west-1 - Europe (Ireland)"],
    ["region"=>"eu-west-2","name"=>"eu-west-2 - Europe (London)"],
    ["region"=>"sa-east-1","name"=>"sa-east-1 - South America (São Paulo)"],
    ["region"=>"us-gov-west-1","name"=>"us-gov-west-1 - AWS GovCloud (US)"],
];

?>
<div id="universal-message-container">
    <h2>AWS Settings</h2>
    <div class="options">
        <?php
        if (ninjalibs_ses_allset()): // means ses conf set
             $domains = Utils::getIdentityVerificationAttributesDomains();
            ?>
            <?php if ($domains):?>
            <p>
                <b>Domain verifications: </b><br />
                    <?php foreach ($domains as $domain=>$attr):?>
                <div><span style="<?php echo $attr['VerificationStatus'] == "Success" ? "color:green;" : "color:orange;" ?>font-weight:700"><?php echo $attr['VerificationStatus'];?></span> <?php echo $domain ;?></div> 
                    <?php endforeach; ?>
                </p>
                <?php else:?>
                <p><span style="color: red;">Verify domains to properly send e-mails using SES</span>. Help Link here.</p>
                <?php endif;?>

        <?php
            $identities = Utils::getIdentityVerificationAttributes();
            ?>
            <?php if ($identities):?>
            <p>
                <b>E-mail verifications: </b><br />
                <?php foreach ($identities as $identity=>$attr):?>
               <div><span style="<?php echo $attr['VerificationStatus'] == "Success" ? "color:green;" : "color:orange;" ?>font-weight:700"><?php echo $attr['VerificationStatus'];?></span> <?php echo $identity ;?></div> 
                <?php endforeach; ?>
            </p>
            <?php else:?>
            <p><span style="color: red;">Verify e-mails to properly send e-mails using SES</span>. Help Link here.</p>
        <?php endif;?>
        <?php endif;?>
        <p>
            <label>Access Key ID</label>
            <br />
            <?php if (defined('NINJALIBS_SES_AWS_ACCESS_KEY_ID')) :?>
                 <input type="text" class="regular-text"  name="ninjalibs-aws-key" id="ninjalibs-aws-key" value="<?php echo NINJALIBS_SES_AWS_ACCESS_KEY_ID;?>" disabled/> 
            <?php else:?>
                  <input type="text" class="regular-text"  name="ninjalibs-aws-key" id="ninjalibs-aws-key" /> <span style="color: red;">* Please define <kbd>NINJALIBS_SES_AWS_ACCESS_KEY_ID</kbd>  wp-config.php</span>
            <?php endif; ?>
            </p>
          <p>
            <label>Access Key Secret</label>
            <br />
             <?php if (defined('NINJALIBS_SES_AWS_ACCESS_KEY_SECRET')) :?>
                 <input type="text" class="regular-text"  name="ninjalibs-aws-secret" id="ninjalibs-aws-secret"  value="<?php echo str_repeat('*', strlen(NINJALIBS_SES_AWS_ACCESS_KEY_SECRET));?>" disabled/> 
            <?php else:?>
                  <input type="text" class="regular-text"  name="ninjalibs-aws-secret" id="ninjalibs-aws-secret" /> <span style="color: red;">* Please define <kbd>NINJALIBS_SES_AWS_ACCESS_KEY_SECRET</kbd>  wp-config.php</span>
            <?php endif; ?>
        </p>
        <p>
            <label>Region</label>
            <br />
            <select name="region"<?php if (defined('NINJALIBS_SES_AWS_REGION')):?> disabled<?php endif;?>  id="ninjalibs-aws-region" >
                <?php  foreach ($ses_regions as $region): ?>
                <option value="<?php echo $region['region']; ?>" <?php if (defined('NINJALIBS_SES_AWS_REGION') && $region['region'] == NINJALIBS_SES_AWS_REGION):?> selected<?php endif;?>><?php echo $region['name'];?></option>
                <?php endforeach; ?>
            </select> <?php if (!defined('NINJALIBS_SES_AWS_REGION')):?><span style="color: red;">* Please define <kbd>NINJALIBS_SES_AWS_REGION</kbd>  in wp-config.php <?php endif;?></span>
        </p>
</div>
<?php if (ninjalibs_ses_allset()):?>
    <div class="notice inline notice-warning notice-alt"><p>
        <b>*Help: </b> You can change SES config in your <kbd>`wp-config.php`</kbd>  file.
    </p>
</div>
<?php else:?>
<div class="notice inline notice-warning notice-alt"><p>
        <b>*Tip: </b> set <kbd>NINJALIBS_SES_AWS_ACCESS_KEY_ID</kbd> and <kbd>NINJALIBS_SES_AWS_ACCESS_SECRET</kbd> in your <kbd>`wp-config.php`</kbd> file right before the  <i>That's all, stop editing! Happy publishing line</i> like in example below
    </p>
</div>
<?php
$snippet = <<< WP_CONFIG_EXAMLE
<?php

define('NINJALIBS_SES_AWS_ACCESS_KEY_ID','*****SES_ACCESS_KEY*****');
define('NINJALIBS_SES_AWS_ACCESS_SECRET','*****SES_SECRET_KEY*****');
define('NINJALIBS_SES_AWS_REGION','us-east-1');

/* That's all, stop editing! Happy publishing. */
WP_CONFIG_EXAMLE;
?>
<script type="text/javascript">
function updateConf(){
   var thecode = <?php echo json_encode($snippet);?>;
   keydone = thecode.replace('*****SES_ACCESS_KEY*****',jQuery('#ninjalibs-aws-key').val());
   secretdone = keydone.replace('*****SES_SECRET_KEY*****',jQuery('#ninjalibs-aws-secret').val());
   regiondone = secretdone.replace("us-east-1",jQuery('#ninjalibs-aws-region').val());

   jQuery('#code-example').val(regiondone)
   
}
jQuery(document).ready(function(){
    jQuery('#ninjalibs-aws-key').keyup(updateConf);
    jQuery('#ninjalibs-aws-secret').keyup(updateConf);
    jQuery('#ninjalibs-aws-region').change(updateConf);
});

</script>
<textarea id="code-example" rows="15" cols="100"><?php echo esc_html($snippet); ?> </textarea>
<?php endif;?>
