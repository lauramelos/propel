<?php
/* 
 * Plugin Name: WP Mail Configurator
 * Description: This plugins allows user to modify default WP Mail settings in seconds. For any problems or questions please send mail to hrvoje.krbavac@gmail.com.
 * Author: Hrvoje Krbavac <hrvoje.krbavac@gmail.com>
 * Version: 1.0.0.
 * Plugin URI: http://www.wpbrickr.com
 * Author URI: http://www.wpbrickr.com
 * © 2012. Hrvoje Krbavac
 */

 
 define('WPB_MC_CORE_VERSION', '1.0');
 define('WPB_MC_OPTIONS_KEY', 'wpb_mc_options');
 require_once 'wp-mail-configurator-gui.php';
 
 class wpb_mc {
   
   
   /* Init
    * @author Hrvoje Krbavac <hrvoje.krbavac@gmail.com>
    * @since 1.0.0
    */
    function init() {
      if (is_admin()) {
        add_action('admin_menu', array(__CLASS__, 'admin_menu'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
      } 
      
      add_filter('wp_mail', array(__CLASS__, 'handle_wp_mail'));
    } // init
    
    
   /* Activation
    * @author Hrvoje Krbavac <hrvoje.krbavac@gmail.com>
    * @since 1.0.0
    */
    function activation() {
      update_option(WPB_MC_OPTIONS_KEY, array('enabled' => '1'));
    } // activation
    
    
   /* Handle WP Mail
    * @author Hrvoje Krbavac <hrvoje.krbavac@gmail.com>
    * @since 1.0.0
    */
    function handle_wp_mail() {
      $options = get_option(WPB_MC_OPTIONS_KEY);
      
      if ($options['enabled'] == '1' && isset($options) && $options['name'] != '' && $options['email'] != '') {
        $headers = 'From: ' . $options['name'] . ' <' . $options['email'] . '>';
        $atts['headers'] = $headers;
      }
      
      return $atts;
    } // handle_wp_mail
    
    
    /* Adding menu items to admin menu
     * @author Hrvoje Krbavac <hrvoje.krbavac@gmail.com>
     * @since 1.0.0
     */
     function admin_menu() {
       add_menu_page('Mail Configurator', 'Mail Configurator', 'manage_options', 'wpb_mc', array('wpb_mc_gui', 'options_page'));
     } // admin_menu
     
    
    /* Register settings
     * @author Hrvoje Krbavac <hrvoje.krbavac@gmail.com>
     * @since 1.0.0
     */
     function register_settings() {
       register_setting(WPB_MC_OPTIONS_KEY, WPB_MC_OPTIONS_KEY);
     } // register_mysettings
     

     
   
 } // wpb_mc
 
 add_action('init', array('wpb_mc', 'init'));
 register_activation_hook(__FILE__, array('wpb_mc', 'activation'));
 
?>