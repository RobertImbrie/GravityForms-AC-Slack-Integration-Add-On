<?php
GFForms::include_addon_framework();
 
class GFacslackaddon extends GFAddOn {
 
    protected $_version = GF_AC_SLACK_ADDON_VERSION;
    protected $_min_gravityforms_version = '1.9';
    protected $_slug = 'acslackaddon';
    protected $_path = 'acslackaddon/acslackaddon.php';
    protected $_full_path = __FILE__;
    protected $_title = 'Gravity Forms AC-Slack Integration Add-On';
    protected $_short_title = 'AC-Slack Integration Add-On';
 
    private static $_instance = null;
 
    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new GFacslackaddon();
        }
 
        return self::$_instance;
    }
 
    public function init() {
        parent::init();
        add_filter( 'gform_submit_button', array( $this, 'form_submit_button' ), 10, 2 );
    }
 
    public function scripts() {
        $scripts = array(
            array(
                'handle'  => 'my_script_js',
                'src'     => $this->get_base_url() . '/js/my_script.js',
                'version' => $this->_version,
                'deps'    => array( 'jquery' ),
                'strings' => array(
                    'first'  => esc_html__( '', 'acslackaddon' ),
                ),
                'enqueue' => array(
                    array(
                        'admin_page' => array( 'form_settings' ),
                        'tab'        => 'acslackaddon'
                    )
                )
            ),
 
        );
 
        return array_merge( parent::scripts(), $scripts );
    }
 
    public function styles() {
        $styles = array(
            array(
                'handle'  => 'my_styles_css',
                'src'     => $this->get_base_url() . '/css/my_styles.css',
                'version' => $this->_version,
                'enqueue' => array(
                    array( 'field_types' => array( 'poll' ) )
                )
            )
        );
 
        return array_merge( parent::styles(), $styles );
    }
 
    function form_submit_button( $button, $form ) {
        $settings = $this->get_form_settings( $form );
        if ( isset( $settings['enabled'] ) && true == $settings['enabled'] ) {
            $text   = $this->get_plugin_setting( 'integration-settings' );
            $button = "<div>{$text}</div>" . $button;
        }
 
        return $button;
    }
 
    public function plugin_page() {
        echo 'This page appears in the Forms menu';
    }
 
    public function plugin_settings_fields() {
        return array(
            array(
                'title'  => esc_html__( 'AC-Slack Add-On Settings', 'acslackaddon' ),
                'fields' => array(
                    array(
                        'label'   => esc_html__( 'Enable Integration for ', 'acslackaddon' ),
                        'type'    => 'radio',
                        'name'    => 'integration-settings',
                        'tooltip' => esc_html__( 'This is the tooltip', 'acslackaddon' ),
                        'choices' => array(
                            array(
                                'label' => esc_html__( 'ActiveCollab', 'acslackaddon' ),
                            ),
                            array(
                                'label' => esc_html__( 'Slack', 'acslackaddon' ),
                            ),
                            array(
                                'label' => esc_html__( 'Both', 'acslackaddon' ),
                            ),
                        ),
                    )
                )
            )
        );
    }
 
    public function form_settings_fields( $form ) {
        $form_settings_array = array();
        $integrations_enabled = $this->get_plugin_setting( 'integration-settings');
        //if(  $integrations_enabled == 'ActiveCollab' || $integrations_enabled == 'Both'){
            array_push( $form_settings_array, array(
                'title'  => esc_html__( 'ActiveCollab Integration', 'acslackaddon' ),
                'fields' => array(
                    array(
                        'label'   => esc_html__( 'Append to Task Name', 'acslackaddon' ),
                        'type'    => 'text',
                        'name'    => 'ac-task',
                        'tooltip' => esc_html__( 'Append to task name', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'Project', 'acslackaddon' ),
                        'type'    => 'text',
                        'name'    => 'ac-project',
                        'tooltip' => esc_html__( 'Adds the task to the project name', 'acslackaddon' ),
                        'class'   => 'medium mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'Tasklist', 'acslackaddon' ),
                        'type'    => 'text',
                        'name'    => 'ac-tasklist',
                        'tooltip' => esc_html__( 'Adds the task to the tasklist name', 'acslackaddon' ),
                        'class'   => 'medium mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'ActiveCollab URL', 'acslackaddon' ),
                        'type'    => 'text',
                        'name'    => 'ac-url',
                        'tooltip' => esc_html__( 'Enter the URL of your ActiveCollab site', 'acslackaddon' ),
                        'class'   => 'medium mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'API Key', 'acslackaddon' ),
                        'type'    => 'text',
                        'name'    => 'ac-key',
                        'tooltip' => esc_html__( 'See https://github.com/RobertImbrie/gf-acslackaddon on finding the API key', 'acslackaddon' ),
                        'class'   => 'medium mt-position-right',
                    ),
                ),
            ));
        //}
        //if(  $integrations_enabled == 'Slack' || $integrations_enabled == 'Both'){
            array_push( $form_settings_array, array(
                'title'  => esc_html__( 'Slack Integration', 'acslackaddon' ),
                'fields' => array(
                    array(
                        'label'   => esc_html__( 'Message', 'acslackaddon' ),
                        'type'    => 'text',
                        'name'    => 'slack-message',
                        'tooltip' => esc_html__( 'Add a Slack message. Supports {ac_task_id} and {ac_task_url} merge tags.', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'Channel', 'acslackaddon' ),
                        'type'    => 'text',
                        'name'    => 'slack-channel',
                        'tooltip' => esc_html__( 'Choose the Slack channel (no # required)', 'acslackaddon' ),
                        'class'   => 'medium mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'Username', 'acslackaddon' ),
                        'type'    => 'text',
                        'name'    => 'slack-username',
                        'tooltip' => esc_html__( 'Choose the username to display as', 'acslackaddon' ),
                        'class'   => 'medium mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'User Emoji', 'acslackaddon' ),
                        'type'    => 'text',
                        'name'    => 'slack-emoji',
                        'tooltip' => esc_html__( 'Choose an emoji to act a user icon', 'acslackaddon' ),
                        'class'   => 'medium mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'API Key', 'acslackaddon' ),
                        'type'    => 'text',
                        'name'    => 'slack-key',
                        'tooltip' => esc_html__( 'See https://github.com/RobertImbrie/gf-acslackaddon on finding the API key', 'acslackaddon' ),
                        'class'   => 'medium mt-position-right',
                    ),
                ),
            ));
        //}

        return $form_settings_array;
    }
 
    public function is_valid_setting( $value ) {
        return strlen( $value ) < 10;
    }
 
}