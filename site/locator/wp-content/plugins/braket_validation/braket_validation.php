<?php
/**
 * Plugin Name: Validation Plugin (for Braketmedia)
 * Plugin URI: http://www.telematica.com.ar
 * Description: Validate comments instantly with jQuery. Based on <a href="http://bassistance.de/jquery-plugins/jquery-plugin-validation/">jQuery Form Validation</a> by Jörn Zaefferer.
 * Version: 0.2
 * Author: Pro Blog Design
 * Author URI: http://www.problogdesign.com/
 * License: GPLv2
 */
 
 /**
 * Add jQuery Validation script on posts.
 */
function pbd_vc_scripts() {
	//if(is_single() ) {
		wp_enqueue_script(
			'jquery-validate',
			plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js',
			array('jquery'),
			'1.10.0',
			true
		);
 
		wp_enqueue_style(
			'jquery-validate',
			plugin_dir_url( __FILE__ ) . 'css/style.css',
			array(),
			'1.0'
		);
	//}
}
add_action('template_redirect', 'pbd_vc_scripts');

?>
