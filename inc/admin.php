<?php
// no direct calls, please.
if (!defined('WPINC')) {
    die;
}

function ninlalibs_ses_options_page()
{
    add_menu_page(
        'Ninja Libs SES Settings',
        'Ninja Libs SES',
        'read',
        'ninjalibs-ses',
        'ninjalibs_ses_view_option_page',
        'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAQCAYAAAAmlE46AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAhGVYSWZNTQAqAAAACAAFARIAAwAAAAEAAQAAARoABQAAAAEAAABKARsABQAAAAEAAABSASgAAwAAAAEAAgAAh2kABAAAAAEAAABaAAAAAAAAAEgAAAABAAAASAAAAAEAA6ABAAMAAAABAAEAAKACAAQAAAABAAAADqADAAQAAAABAAAAEAAAAACRGn0aAAAACXBIWXMAAAsTAAALEwEAmpwYAAACZmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS40LjAiPgogICA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgICAgICAgICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICAgICA8dGlmZjpSZXNvbHV0aW9uVW5pdD4yPC90aWZmOlJlc29sdXRpb25Vbml0PgogICAgICAgICA8ZXhpZjpDb2xvclNwYWNlPjE8L2V4aWY6Q29sb3JTcGFjZT4KICAgICAgICAgPGV4aWY6UGl4ZWxYRGltZW5zaW9uPjMwPC9leGlmOlBpeGVsWERpbWVuc2lvbj4KICAgICAgICAgPGV4aWY6UGl4ZWxZRGltZW5zaW9uPjM1PC9leGlmOlBpeGVsWURpbWVuc2lvbj4KICAgICAgPC9yZGY6RGVzY3JpcHRpb24+CiAgIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cv3ns1UAAAK2SURBVCgVTVNNSFRRFD73vv83z9GZbNTGEVPLbBAx25QQopuSqE22aFNQ0aICoU25iLcJIovIRT/gLmihuwQLjArBaJELSYWoDGKwGqdmcPT55r133+1cTfMbhnvuu+f7zvnOfY/ANow96TCPxxpK5PQou3255YjvweDh1gqlvTF6dyGTGz14adoX6SMjfRLZ5Nk2UPyHYm9faD6VrNQfBIzvMjUKna1xUBU6BTwcLn789Sxtz3mEAxBk86GrTVpvZ113ruhVj79furW31qxJJYzS5Mwf5VC6grY1RWHZCUCmdFYz4CIFG9arZvL02IobjFeWq48kClXZggdVcU1tTkXozJciyxV8j2JqyHl6/quTpATbu36mLhaNSHcYNsoYlyVCSMi4ANlfXwYra4H0Pesoqkr47MKyk8k6D6nwFItFbqKXPSWfuahIQw5EsEsehwpLhpZ6Cz5nHJLNewTXCIol6OCVdDcB0l90Ag4cNODCMgIXEfn4eF/KQm8Ext5lIZXQIRZVAtzyIYp1OQEmBoXRhmnOAQ+RGIJlSNBUa0LJD6ExaeJwgVJC4bmEGThe5Anaf4iY4JmPktVxDXrad0BElyHEWdBrQ/MDqPpBUyVFFKAbnW7xxTZE04pMsU1jXRVrYD1EwML+EGXwHBfuYq/it4EtCRTA9hHiJXEpxmTg8acp1+ODyZ26FrVkfZ3zr3WRibcjBoW3A67wUm4phiwOBBZz3r3Cil+lY8uo3KMoNIEUhgQJOxGvGI2VKbqPl726xl4JS6I+qT3wban93OTZ8akfNg7LE1VRwMOFlVuyauqSvOoGE2seO5lvKPRuVuQ1ix0YT4fxmM5//i5FdJWCoVLD1CRAwkuc//3dfa8nUHCba5RFEBu/EBGcP1HXOXyj7c3c0663+RdHu8SzTYgczm36F0/CI9wdZZMHAAAAAElFTkSuQmCC',
        plugin_dir_url(__FILE__) . 'images/icon_wporg.png',
        20
    );
}
add_action('admin_menu', 'ninlalibs_ses_options_page');





function ninjalib_ses_plugin_actions_settings_link($links, $file)
{
    if ($file == "ninjalibs-ses/ninjalibs-ses.php") {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url('admin.php?page=ninjalibs-ses'),
            _("Settings")
        );

        array_unshift($links, $settings_link);
    }
    return $links;
}

add_filter('plugin_action_links', 'ninjalib_ses_plugin_actions_settings_link', 10, 2);



function ninjalibs_ses_view_option_page()
{
    include NINJALIBS_SES_VIEW_DIR.'/admin_options.php';
}


 add_action('admin_enqueue_scripts', 'ninjalibs_ses_load_admin_style');

function ninjalibs_ses_load_admin_style()
{
    wp_enqueue_style('ninjalibs_ses_admin_css', plugin_dir_url(__DIR__).'public/css/ninjalibs-ses.css', false, NINJALIBS_SES_VERSION);
    wp_enqueue_script('ninjalibs_ses_lightweight_js', plugin_dir_url(__DIR__).'public/js/lightweight-charts.standalone.production.js', false, NINJALIBS_SES_VERSION);
}
