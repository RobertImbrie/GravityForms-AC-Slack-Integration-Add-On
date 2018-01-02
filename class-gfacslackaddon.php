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
                    'first'  => esc_html__( 'First Choice', 'acslackaddon' ),
                    'second' => esc_html__( 'Second Choice', 'acslackaddon' ),
                    'third'  => esc_html__( 'Third Choice', 'acslackaddon' )
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
            $text   = $this->get_plugin_setting( 'mytextbox' );
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
                        'name'              => 'mytextbox',

                        'tooltip'           => esc_html__( 'This is the tooltip', 'acslackaddon' ),
                        'label'             => esc_html__( 'This is the label', 'acslackaddon' ),
                        'type'              => 'text',
                        'class'             => 'small',
                        'feedback_callback' => array( $this, 'is_valid_setting' ),
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
                        'label'   => esc_html__( 'Append to ActiveCollab Task Name', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'mytextarea',
                        'tooltip' => esc_html__( 'This is the tooltip', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'ActiveCollab Project Name', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'mytextarea',
                        'tooltip' => esc_html__( 'This is the tooltip', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'ActiveCollab Tasklist Name', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'mytextarea',
                        'tooltip' => esc_html__( 'This is the tooltip', 'acslackaddon' ),
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
                        'name'    => 'mytextarea',
                        'tooltip' => esc_html__( 'This is the tooltip', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'API Key', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'mytextarea',
                        'tooltip' => esc_html__( 'This is the tooltip', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'Channel', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'mytextarea',
                        'tooltip' => esc_html__( 'This is the tooltip', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'Username', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'mytextarea',
                        'tooltip' => esc_html__( 'This is the tooltip', 'acslackaddon' ),
                        'class'   => 'medium merge-tag-support mt-position-right',
                    ),
                    array(
                        'label'   => esc_html__( 'User Emoji', 'acslackaddon' ),
                        'type'    => 'textarea',
                        'name'    => 'mytextarea',
                        'tooltip' => esc_html__( 'This is the tooltip', 'acslackaddon' ),
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