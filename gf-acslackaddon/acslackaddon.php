<?php
define( 'GF_AC_SLACK_ADDON_VERSION', '2.0' );
 
add_action( 'gform_loaded', array( 'GF_AC_Slack_AddOn_Bootstrap', 'load' ), 5 );
 
class GF_AC_Slack_AddOn_Bootstrap {
 
    public static function load() {
 
        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }
 
        require_once( 'class-gfacslackaddon.php' );
 
        GFAddOn::register( 'GFACSlackddOn' );
    }
 
}
 
function gf_simple_addon() {
    return GFACSlackAddOn::get_instance();
}