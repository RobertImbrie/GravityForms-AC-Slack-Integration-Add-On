<?php
/*
Plugin Name: GravityForms AC-Slack Integration Add-On
Plugin URI: https://github.com/RobertImbrie/gf-acslackaddon
Description: Adds the ability to create ActiveCollab tickets and Slack posts after GravityForms fields submit.
Version: 0.1.0
Author: Robert Imbrie
Author URI: https://github.com/RobertImbrie/
Text Domain: gf-acslack
Domain Path: /languages
*/
define( 'GF_AC_SLACK_ADDON_VERSION', '2.0' );
 
add_action( 'gform_loaded', array( 'GF_AC_Slack_AddOn_Bootstrap', 'load' ), 5 );
 
class GF_AC_Slack_AddOn_Bootstrap {
 
    public static function load() {
 
        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }
 
        require_once( 'class-gfacslackaddon.php' );
        require_once( 'acslack-after-submission.php' );
 
        GFAddOn::register( 'GFACSlackAddOn' );
    }
 
}
 
function gf_simple_addon() {
    return GFACSlackAddOn::get_instance();
}