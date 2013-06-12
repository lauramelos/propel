<?php 
/**
*
* Plugin Name: Users from no Results Form 
* Plugin URI: http://telematica.com.ar
* Description: Report Users from no Results Form, for PROPEL
* Version: 1.0
* Author: Florencia Maulucci for Telematica 
* Author URI: http://telematica.com.ar
**/

function wp_addreport_options() {

    if (!current_user_can('manage_options'))
    {
        wp_die( __('Access Denied') );
    }

 
 
 ?>
 <link rel='stylesheet' id='colors-css'  href='http://67.222.18.91/~propel/locator/wp-admin/css/colors-fresh.min.css?ver=3.5.1' type='text/css' media='all' />
 <p>&nbsp;</p>
 <H1>Email Notification Subscribers</H1>
 <form action="" method="post">
 <table><tr><td>Status Email:</td>
 <td><select name="filtra">
 <option value=>All</option>
 <option value="where enviado=0" <? If ($_POST['filtra']=='where enviado=0') echo 'selected';?>>Suscribed</option>
 <option value="where enviado=1" <? If ($_POST['filtra']=='where enviado=1') echo 'selected';?>>Unsuscribed</option>
 </select>
 </td><td><input type="submit" value="Go"></td></tr>
 </table>
 </form>
 <?
 
 
// conectamos
global $wpdb;

if ($_POST['filtra']) $filtra=$_POST['filtra'];
else $filtra='';
 
$query_link = "SELECT * FROM datauserword ".$filtra." order by id desc limit 100";
$res_emails = $wpdb->get_results( $query_link );

?>
<table width="100%" align="center" class="wp-list-table widefat fixed posts">
<tr style="color:#9c9c9c; font-size:14px; font-family:Arial, Helvetica, sans-serif; font-weight:bold; ">
<th id="cb" class="manage-column column-cb check-column" >Name</th>
<th id="cb" class="manage-column column-cb check-column" >Email</th>
<th id="cb" class="manage-column column-cb check-column" >Zip</th>
<th id="cb" class="manage-column column-cb check-column" >Distance</th>
<th id="cb" class="manage-column column-cb check-column" >Status</th>
<th id="cb" class="manage-column column-cb check-column" >Mails Sent</th>
</tr>
<?
 
foreach ($res_emails as $sigema){
?>
<tr  class="manage-column column-cb check-column"  style="color:#9c9c9c; font-size:14px; font-family:Arial, Helvetica, sans-serif; font-weight:600;">
<td><?=$sigema->Name?></td>
<td><?=$sigema->Email?></td>
<td><?=$sigema->Zip?></td>
<td><?=$sigema->Distance?></td>
<td><? if ($sigema->enviado == 0) echo '<span style="color:#990000">Suscribed</span>'; else echo '<span style="color:#006633">Unsuscribed</span>';?></td>
<td><?=$sigema->count?></td>
</tr>
<? } ?>
</table>
<?
}

function wp_addreport() {
  add_menu_page('Email Notification Subscribers', 'Email Notification Subscribers', 'manage_options', 'wp_addreport', 'wp_addreport_options','http://67.222.18.91/~propel/_img/content/doctor.png' );
  //add_options_page( 'Report Signed Emails', 'Report Signed Emails', 'manage_options', 'wp_addreport', 'wp_addreport_options' );
}
add_action( 'admin_menu', 'wp_addreport' );
?>
