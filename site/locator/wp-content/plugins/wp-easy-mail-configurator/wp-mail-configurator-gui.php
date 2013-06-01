<?php
/*
 * GUI Class for WP Mail Configurator
 * © 2012. Hrvoje Krbavac
 */

class wpb_mc_gui extends wpb_mc {
  
    /* Options Page
     * @author Hrvoje Krbavac <hrvoje.krbavac@gmail.com>
     * @since 1.0.0
     */
     function options_page() {
       $options = get_option(WPB_MC_OPTIONS_KEY);
       
       if ($options['enabled'] == '1') {
         $checked = 'checked="checked"';
       } else {
         $checked = '';
       }

       echo '<div id="wpbody">';
         echo '<div id="wpbody-content">';
           echo '<div class="wrap">';
           
           echo '<div class="icon32" id="icon-options-general"><br></div>';
           echo '<h2>WP Mail Configurator</h2>';
       
       
           echo '<form action="options.php" method="post">';
           
           settings_fields(WPB_MC_OPTIONS_KEY);
           
           echo '<table class="form-table">
                   <tbody>
                     <tr valign="top">
                       <th scope="row"><label for="sender-name">Sender name:</label></th>
                       <td><input type="text" class="regular-text" value="' . $options['name'] . '" id="sender-name" name="wpb_mc_options[name]"></td>
                     </tr>
                     <tr valign="top">
                       <th scope="row"><label for="sender-email">Sender e-mail:</label></th>
                       <td><input type="text" class="regular-text" value="' . $options['email'] . '" id="sender-email" name="wpb_mc_options[email]"></td>
                     </tr>
                     <tr valign="top">
                       <th scope="row"><label for="sender-email"><strong>Enable WP Mail Configurator</strong>:</label></th>
                       <td><input type="checkbox" name="wpb_mc_options[enabled]" value="1" ' . $checked . ' /></td>
                     </tr>
                   </tbody>
                 </table>
           
                 <p class="submit">
                   <input type="submit" value="Save Changes" class="button-primary" id="submit" name="submit"></p>
                 </form>';
       
       
           echo '</div>';
         echo '</div>';
       echo '</div>';
     } // options_page
  
} // wpb_mc_gui

?>