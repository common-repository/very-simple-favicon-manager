<?php
/*
 * Plugin Name: Very Simple Favicon Manager
 * Description: This is a very simple plugin to set a favicon and a home screen icon for your website. For more info please check readme file.
 * Version: 4.0
 * Author: Guido
 * Author URI: http://www.guido.site
 * License: GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: very-simple-favicon-manager
 * Domain Path: /translation
 */

// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// load plugin text domain
function vsfm_init() { 
	load_plugin_textdomain( 'very-simple-favicon-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/translation' );
}
add_action('plugins_loaded', 'vsfm_init');

// enqueue colorpicker script
function vsfm_enqueue_color_picker() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'vsfm_colorpicker_script', plugins_url('/js/vsfm-colorpicker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'vsfm_enqueue_color_picker' );

// add settings link
function vsfm_action_links ( $links ) { 
	$settingslink = array( '<a href="'. admin_url( 'options-general.php?page=vsfm' ) .'">'. __('Settings', 'very-simple-favicon-manager') .'</a>', ); 
	return array_merge( $links, $settingslink ); 
} 
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'vsfm_action_links' ); 
 
// add admin options page
function vsfm_menu_page() {
	add_options_page( __( 'Favicon Manager', 'very-simple-favicon-manager' ), __( 'Favicon Manager', 'very-simple-favicon-manager' ), 'manage_options', 'vsfm', 'vsfm_options_page' );
}
add_action( 'admin_menu', 'vsfm_menu_page' );

// add admin settings and such 
function vsfm_admin_init() {
	add_settings_section( 'vsfm-section', __( 'Favicon (16x16px or multi-size)', 'very-simple-favicon-manager' ), 'vsfm_section_callback', 'vsfm' );
	add_settings_field( 'vsfm-field', __( 'Favicon', 'very-simple-favicon-manager' ), 'vsfm_field_callback', 'vsfm', 'vsfm-section' );
	register_setting( 'vsfm-options', 'vsfm-setting', 'esc_url_raw' );

	add_settings_section( 'vsfm-section-ios', __( 'Home screen icon for iOS (180x180px)', 'very-simple-favicon-manager' ), 'vsfm_section_callback_ios', 'vsfm' );
	add_settings_field( 'vsfm-field-ios', __( 'Icon', 'very-simple-favicon-manager' ), 'vsfm_field_callback_ios', 'vsfm', 'vsfm-section-ios' );
	register_setting( 'vsfm-options', 'vsfm-setting-ios', 'esc_url_raw' );

	add_settings_section( 'vsfm-section-ms-image', __( 'Home screen icon for Windows (144x144px)', 'very-simple-favicon-manager' ), 'vsfm_section_callback_ms_image', 'vsfm' );
	add_settings_field( 'vsfm-field-ms-image', __( 'Icon', 'very-simple-favicon-manager' ), 'vsfm_field_callback_ms_image', 'vsfm', 'vsfm-section-ms-image' );
	register_setting( 'vsfm-options', 'vsfm-setting-ms-image', 'esc_url_raw' );

	add_settings_section( 'vsfm-section-ms-color', __( 'Background for Windows home screen icon', 'very-simple-favicon-manager' ), 'vsfm_section_callback_ms_color', 'vsfm' );
	add_settings_field( 'vsfm-field-ms-color', __( 'Color', 'very-simple-favicon-manager' ), 'vsfm_field_callback_ms_color', 'vsfm', 'vsfm-section-ms-color' );
	register_setting( 'vsfm-options', 'vsfm-setting-ms-color', 'sanitize_text_field' );
}
add_action( 'admin_init', 'vsfm_admin_init' );

function vsfm_section_callback() {
    echo __( 'Upload your favicon (.ico file) in the media library and copy-paste link here.', 'very-simple-favicon-manager' ); 
}

function vsfm_section_callback_ios() {
    echo __( 'Upload your home screen icon (.png file) in the media library and copy-paste link here.', 'very-simple-favicon-manager' ); 
}

function vsfm_section_callback_ms_image() {
    echo __( 'Upload your home screen icon (.png file) in the media library and copy-paste link here.', 'very-simple-favicon-manager' ); 
}

function vsfm_section_callback_ms_color() {
    echo __( 'This color will be used as background for the home screen icon.', 'very-simple-favicon-manager' ); 
}

// add input fields
function vsfm_field_callback() {
	$vsfm_setting = esc_url( get_option( 'vsfm-setting' ) );
	echo "<input type='text' size='60' maxlength='150' name='vsfm-setting' value='$vsfm_setting' />";
}

function vsfm_field_callback_ios() {
	$vsfm_setting_ios = esc_url( get_option( 'vsfm-setting-ios' ) );
	echo "<input type='text' size='60' maxlength='150' name='vsfm-setting-ios' value='$vsfm_setting_ios' />";
}

function vsfm_field_callback_ms_image() {
	$vsfm_setting_ms_image = esc_url( get_option( 'vsfm-setting-ms-image' ) );
	echo "<input type='text' size='60' maxlength='150' name='vsfm-setting-ms-image' value='$vsfm_setting_ms_image' />";
}

function vsfm_field_callback_ms_color() {
	$vsfm_setting_ms_color = esc_attr( get_option( 'vsfm-setting-ms-color' ) );
	echo "<input type='text' maxlength='10' id='vsfm-setting-ms-color' name='vsfm-setting-ms-color' data-default-color='#21759b' value='$vsfm_setting_ms_color' />";
}

// display admin options page
function vsfm_options_page() {
?>
<div class="wrap"> 
	<div id="icon-plugins" class="icon32"></div> 
	<h1><?php _e( 'Very Simple Favicon Manager', 'very-simple-favicon-manager' ); ?></h1> 
	<form action="options.php" method="POST">
	<?php settings_fields( 'vsfm-options' ); ?>
	<?php do_settings_sections( 'vsfm' ); ?>
	<?php submit_button(); ?>
	</form>
	<p><?php _e( 'Android device often uses the iOS home screen icon.', 'very-simple-favicon-manager' ); ?></p>
	<p><?php _e( 'More info', 'very-simple-favicon-manager' ); ?>: <a href="https://wordpress.org/plugins/very-simple-favicon-manager" target="_blank"><?php _e( 'click here', 'very-simple-favicon-manager' ); ?></a></p>
</div>
<?php
}

// include site icon and favicon in header 
function vsfm_display_favicon() {
	$vsfm_custom_favicon = esc_url( get_option( 'vsfm-setting' ) );
	$vsfm_custom_icon_ios = esc_url( get_option( 'vsfm-setting-ios' ) );
	$vsfm_custom_icon_ms = esc_url( get_option( 'vsfm-setting-ms-image' ) );
	$vsfm_custom_color_ms = esc_attr( get_option( 'vsfm-setting-ms-color' ) );

	if (!empty( $vsfm_custom_favicon )) {
		echo '<link rel="shortcut icon" href="'.esc_url($vsfm_custom_favicon).'" />'."\n";
	}
	if (!empty( $vsfm_custom_icon_ios )) {
		echo '<link rel="apple-touch-icon" href="'.esc_url($vsfm_custom_icon_ios).'" />'."\n";
	}
	if (!empty( $vsfm_custom_icon_ms )) {
		echo '<meta name="msapplication-TileImage" content="'.esc_url($vsfm_custom_icon_ms).'" />'."\n";
	}
	if (!empty( $vsfm_custom_color_ms )) {
		echo '<meta name="msapplication-TileColor" content="'.esc_attr($vsfm_custom_color_ms).'" />'."\n";
	}
}
add_action( 'wp_head', 'vsfm_display_favicon' );
