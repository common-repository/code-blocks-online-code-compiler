<?php 
/*
Plugin Name: Code Blocks- online code compiler
Plugin URI : webtoptemplate.com
Description:  code blocks- online code compiler, Coding Blocks Online IDE | Run and compile and check your code.
Version:1.0
Author: kardi
Author URI : https://github.com/ikardi420
License : GPL v or later
Text Domain: lmscode
Domain Path : /languages/
*/



if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */
 
/**
 * custom option and settings
 */
function wttlmscode_settings_init() {
    // Register a new setting for "lmscode" page.
    register_setting( 'lmscode', 'lmscode_options1' );
    register_setting( 'lmscode', 'lmscode_options2' );
 
    // Register a new section in the "lmscode" page.
    add_settings_section(
        'lmscode_section_developers',
        __( 'Here set your settings', 'lmscode' ), 'lmscode_section_developers_callback',
        'lmscode'
    );
 
    // Register a new field in the "lmscode_section_developers" section, inside the "lmscode" page.
    add_settings_field(
        'lmscode_field_clid', // As of WP 4.6 this value is used only internally.
                                // Use $args' label_for to populate the id inside the callback.
            __( 'Client ID', 'lmscode' ),
        'lmscode_field_clid_cb',
        'lmscode',
        'lmscode_section_developers');
    add_settings_field( 'lmscode_secretkey',  __( 'Secret key', 'lmscode' ), 'lmscode_field_secrt_cb', 'lmscode', 'lmscode_section_developers' );
}
 
/**
 * Register our lmscode_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'wttlmscode_settings_init' );
 
 
/**
 * Custom option and settings:
 *  - callback functions
 */
 
 
/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function lmscode_section_developers_callback( $args ) {
    ?>
    <h5 id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Fill fields to setup comiler. To get the API key you can follow the link here: https://www.jdoodle.com/compiler-api/', 'lmscode' ); ?></h5>
    <h5><?php esc_html_e( 'Also, for more details about the API documentation and terms & condition you can follow this: https://docs.jdoodle.com/compiler-api/compiler-api', 'lmscode' ); ?></h5>
    <?php
}
 
/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function lmscode_field_clid_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'lmscode_options1' );
    ?>
   
  
    <input id='lmscode_field_clid' name='lmscode_options1' type='text' value="<?php echo esc_html($options);?>" />
    <p class="description">
        <?php esc_html_e( 'here set your client id.', 'lmscode' ); ?>
    </p>
  
    <?php
}
function lmscode_field_secrt_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'lmscode_options2' );
    ?>
    <input id='lmscode_secretkey' name='lmscode_options2' type='text' value="<?php echo esc_html($options);?>" />
    
    <p class="description">
        <?php esc_html_e( 'here set your client secret id.', 'lmscode' ); ?>
    </p>
  
    <?php
}
 
/**
 * Add the top level menu page.
 */


function lmscode_options_page() {
    add_menu_page(
        'Lmscode compiler',
        'Lmscode settings',
        'manage_options',
        'lmscode',
        'lmscode_options_page_html'
    );
}
 
 
/**
 * Register our lmscode_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'lmscode_options_page' );
 
 
/**
 * Top level menu callback function
 */
function lmscode_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
 
    // add error/update messages
 
    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'lmscode_messages', 'lmscode_message', __( 'Settings Saved', 'lmscode' ), 'updated' );
    }
 
    // show error/update messages
    settings_errors( 'lmscode_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "lmscode"
            settings_fields( 'lmscode' );
            // output setting sections and their fields
            // (sections are registered for "lmscode", each field is registered to a specific section)
            do_settings_sections( 'lmscode' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}

function wttlmscode_enqueue_scripts() {
    global $post;
    if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'lms_code') ) {
    wp_register_style( 'lmscode-stylesheet',  plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
        wp_enqueue_style( 'lmscode-stylesheet' );

        wp_enqueue_style( 'style-prism', plugin_dir_url( __FILE__ ) . 'assets/css/prism.css' );
        wp_enqueue_style( 'style-prism-live', plugin_dir_url( __FILE__ ) . 'assets/css/prism-live.css' );
        wp_enqueue_style( 'style-prism-line', 'https://prismjs.com/plugins/line-numbers/prism-line-numbers.css' );


        wp_enqueue_script( 'codescript-main', plugin_dir_url( __FILE__ ) . 'assets/js/main.js', array(), '1.0.0', true );
        //wp_enqueue_script( 'codescript-prisim-live-css', plugin_dir_url( __FILE__ ) . 'assets/js/prism-live-css.js', array(), '1.0.0', true );
        wp_enqueue_script( 'codescript-prisim-live', plugin_dir_url( __FILE__ ) . 'assets/js/prism-live.js', array(), '1.0.0', true );
        // wp_enqueue_script( 'codescript-prisim-live-js', plugin_dir_url( __FILE__ ) . 'assets/js/prism-live-javascript.js', array(), '1.0.0', true );
        // wp_enqueue_script( 'codescript-prisim-live-mr', plugin_dir_url( __FILE__ ) . 'assets/js/prism-live-markup.js', array(), '1.0.0', true );
        // wp_enqueue_script( 'codescript-index', plugin_dir_url( __FILE__ ) . 'assets/js/index.js', array(), '1.0.0', true );
        wp_register_script('lmscodescript', plugin_dir_url( __FILE__ ) .'assets/js/prism.js',array ('jquery'),false, false);
        wp_enqueue_script('lmscodescript');
        wp_localize_script('lmscodescript', 'lms_code',
            [
                'ajaxurl' => admin_url('admin-ajax.php')
            ]
        );
      
        wp_enqueue_script( 'script-prism-line', 'https://prismjs.com/plugins/line-numbers/prism-line-numbers.js', array(), '1.0.0', true );
       
    }

   }
   add_action( 'wp_enqueue_scripts', 'wttlmscode_enqueue_scripts');

   
require_once('code-api.php');
require_once('lmscode-shortcode.php');