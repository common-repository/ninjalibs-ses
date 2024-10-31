<?php

// no direct calls, please.
if ( !defined( 'WPINC' ) ) {
    die;
}
function ninjalibs_ses_sanitize_tab_name( $tab )
{
    $valid_tabs = [
        'test',
        'ses',
        'sns',
        'wpmail',
        'statistics',
        'emails',
        'general'
    ];
    if ( in_array( $tab, $valid_tabs ) ) {
        return $tab;
    }
    return 'general';
}

$tab = ( isset( $_GET['tab'] ) ? ninjalibs_ses_sanitize_tab_name( $_GET['tab'] ) : 'general' );
if ( !ninjalibs_ses_allset() ) {
    
    if ( defined( 'NINJALIBS_SES_WP_MAIL_ERORR' ) ) {
        $tab = "wpmail";
    } else {
        $tab = 'ses';
    }

}
?>
<div class="wrap ninjalibs-ses">
    <div class="header">
        <div id="header-logo">
              <img style="float: left;" width="64px" src="<?php 
echo  plugins_url( 'ninjalibs-ses/public/img/ninjalibs-logo.png' ) ;
?>" />
        </div>
        <div id="header-text" style="padding-left: 72px;">
            <h1><?php 
echo  esc_html( get_admin_page_title() ) ;
?></h1>
            <div class="about-text">
                <?php 

if ( ninjalibs_ses_allset() ) {
    ?>
                Congratulations! You have all set!
                <?php 
} else {
    ?>
                <span style="color: red;">You have some <b>issues</b>!</span>
                <?php 
}

?>
            </div>
        </div>
                </div>
                <?php 
settings_errors();
?>
    <hr/>
     <nav class="nav-tab-wrapper">
      <a href="?page=ninjalibs-ses" class="nav-tab <?php 
if ( $tab === null || $tab === 'general' ) {
    ?>nav-tab-active<?php 
}
?>"><?php 
_e( "General", "ninjalibs-ses" );
?></a>
      <a href="?page=ninjalibs-ses&tab=test" class="nav-tab <?php 
echo  ninjalibs_ses_classfor( ninjalibs_ses_test_done() ) ;
?> <?php 
if ( $tab === 'test' ) {
    ?>nav-tab-active<?php 
}
?>""><?php 
_e( "Test e-mail", "ninjalibs-ses" );
?></a>
      <a href="?page=ninjalibs-ses&tab=ses" class="nav-tab <?php 
echo  ninjalibs_ses_classfor( ninjalibs_ses_ses_done() ) ;
?> <?php 
if ( $tab === 'ses' ) {
    ?>nav-tab-active<?php 
}
?>">
      <img src="<?php 
echo  plugins_url( 'ninjalibs-ses/public/img/ses-icon.png' ) ;
?>" />  <?php 
_e( "SES Settings", "ninjalibs-ses" );
?>
    </a>
     <a href="?page=ninjalibs-ses&tab=statistics" class="nav-tab <?php 
echo  ninjalibs_ses_classfor( ninjalibs_ses_sns_done() ) ;
?> <?php 
if ( $tab === 'statistics' ) {
    ?>nav-tab-active<?php 
}
?>">
        <?php 
_e( "Sending Statistics", "ninjalibs-ses" );
?>
    </a>
    <?php 
?>
    <?php 
?>
    
    </nav>

    <div class="tab-content">
    <?php 
switch ( $tab ) {
    case 'test':
        include NINJALIBS_SES_VIEW_DIR . '/admin_options_test.php';
        break;
    case 'ses':
        include NINJALIBS_SES_VIEW_DIR . '/admin_options_ses.php';
        break;
    case 'sns':
        include NINJALIBS_SES_VIEW_DIR . '/admin_options_sns.php';
        break;
    case 'wpmail':
        include NINJALIBS_SES_VIEW_DIR . '/admin_options_wpmail.php';
        break;
    case 'statistics':
        include NINJALIBS_SES_VIEW_DIR . '/admin_options_statistics.php';
        break;
    case 'emails':
        include NINJALIBS_SES_VIEW_DIR . '/admin_options_emails.php';
        break;
    default:
        include NINJALIBS_SES_VIEW_DIR . '/admin_options_general.php';
        break;
}
?>
    </div>
 
</div>