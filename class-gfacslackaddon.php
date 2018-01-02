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
                        'label'   => esc_html__( 'Enable ', 'acslackaddon' ),
                        'type'    => 'checkbox',
                        'name'    => 'integration-settings',
                        'tooltip' => esc_html__( '', 'acslackaddon' ),
                        'choices' => array(
                            array(
                                'label' => esc_html__( 'Enable AC Integration', 'acslackaddon' ),
                                'name'  => 'enabled-ac',
                            ),
                            array(
                                'label' => esc_html__( 'Enable Slack Integration', 'acslackaddon' ),
                                'name'  => 'enable-slack',
                            ),
                        )
                    )
                )
            )
        );
    }
 
    public function form_settings_fields( $form ) {
        return array(
            array(
                'title'  => esc_html__( 'ActiveCollab Integration', 'acslackaddon' ),
                'fields' => array(
                    array(
                        'label'   => esc_html__( 'Append to Task Name', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'ac-task',
                        'tooltip' => esc_html__( 'You can add the data of GravityForms fields to the text by putting the field title inside a shortcode as follows: [Example Title]', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'Project', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'ac-project',
                        'tooltip' => esc_html__( 'Adds the task to the project name', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'Tasklist', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'ac-tasklist',
                        'tooltip' => esc_html__( 'Adds the task to the tasklist name', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'ActiveCollab URL', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'ac-url',
                        'tooltip' => esc_html__( 'Enter the URL of your ActiveCollab site', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'API Key', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'ac-key',
                        'tooltip' => esc_html__( 'See https://github.com/RobertImbrie/gf-acslackaddon on finding the API key', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                ),
            ),
            array(
                'title'  => esc_html__( 'Slack Integration', 'acslackaddon' ),
                'fields' => array(
                    array(
                        'label'   => esc_html__( 'Message', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'slack-message',
                        'tooltip' => esc_html__( 'Add a Slack message', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'Channel', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'slack-channel',
                        'tooltip' => esc_html__( 'Choose the Slack channel (no # required)', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'Username', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'slack-username',
                        'tooltip' => esc_html__( 'Choose the username to display as', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'User Emoji', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'slack-emoji',
                        'tooltip' => esc_html__( 'Choose an emoji to act a user icon', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'API Key', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'slack-key',
                        'tooltip' => esc_html__( 'See https://github.com/RobertImbrie/gf-acslackaddon on finding the API key', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                ),
            ),
        );
    }
 
    public function settings_my_custom_field_type( $field, $echo = true ) {
        echo '<div>' . esc_html__( 'My custom field contains a few settings:', 'acslackaddon' ) . '</div>';
 
        // get the text field settings from the main field and then render the text field
        $text_field = $field['args']['text'];
        $this->settings_text( $text_field );
 
        // get the checkbox field settings from the main field and then render the checkbox field
        $checkbox_field = $field['args']['checkbox'];
        $this->settings_checkbox( $checkbox_field );
    }
 
    public function is_valid_setting( $value ) {
        return strlen( $value ) < 10;
    }
 
}