<?php
/**
Plugin Name: OTW TinyMCE Widget
Plugin URI: http://OTWthemes.com
Description:  A TinyMCE Widget. Use the TinyMCE editor in a widget so you can insert it in any sidebar you like.  
Author: OTWthemes.com
Version: 1.7

Author URI: http://themeforest.net/user/OTWthemes
*/

load_plugin_textdomain('otw_mcsw',false,dirname(plugin_basename(__FILE__)) . '/languages/');

load_plugin_textdomain('otw-shortcode-widget',false,dirname(plugin_basename(__FILE__)) . '/languages/');

$wp_mcsw_tmc_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_mcsw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_mcsw' ) )
);

$wp_mcsw_agm_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_mcsw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_mcsw' ) )
);

$wp_mcsw_cs_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_mcsw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_mcsw' ) )
);

$otw_mcsw_plugin_id = 'ac958c1f4701d191c85884255effe593';
$otw_mcsw_plugin_url = plugin_dir_url( __FILE__);
$otw_mcsw_css_version = '1.8';
$otw_mcsw_js_version = '1.8';

$otw_mcsw_plugin_options = get_option( 'otw_mcsw_plugin_options' );

//include functons
require_once( plugin_dir_path( __FILE__ ).'/include/otw_mcsw_functions.php' );

//otw components
$otw_mcsw_shortcode_component = false;
$otw_mcsw_form_component = false;
$otw_mcsw_validator_component = false;

//load core component functions
@include_once( 'include/otw_components/otw_functions/otw_functions.php' );

if( !function_exists( 'otw_register_component' ) ){
	wp_die( 'Please include otw components' );
}

//register form component
otw_register_component( 'otw_form', dirname( __FILE__ ).'/include/otw_components/otw_form/', $otw_mcsw_plugin_url.'include/otw_components/otw_form/' );

//register validator component
otw_register_component( 'otw_validator', dirname( __FILE__ ).'/include/otw_components/otw_validator/', $otw_mcsw_plugin_url.'include/otw_components/otw_validator/' );

//register shortcode component
otw_register_component( 'otw_shortcode', dirname( __FILE__ ).'/include/otw_components/otw_shortcode/', $otw_mcsw_plugin_url.'include/otw_components/otw_shortcode/' );

/** 
 *call init plugin function
 */
add_action('init', 'otw_mcsw_init' );
add_action('widgets_init', 'otw_mcsw_widgets_init' );

?>