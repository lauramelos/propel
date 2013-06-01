<br /><br /><br />
<h2><?php _e('Download Complete Data - Excel Format'); ?></h2>
<?php if($_REQUEST['msg']=='exist'){?>
<div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);" >
<p><?php _e('Updated successully.'); ?></p>
</div>
<?php }?>
<table width="50%" cellpadding="3" cellspacing="3" class="widefat post fixed" >
<tr><td colspan="2"><b><?php _e('You will download all posts information <u>Excel format</u> by clicking "Get Posts Backup Data". Get posts data excel and safe it as backup for future use in the case of any mistake or data loss.');?></b></td></tr>
<tr>
<tr>
  <td>&nbsp;</td>
<td>    </tr>
<tr>
  <td colspan="2"><a href="<?php echo site_url();?>/wp-admin/tools.php?page=export&backup=posts"><input type="submit" class="button-secondary action" value="<?php _e('Get Posts Backup Data - Excel'); ?>" name="submit">
 </a>    </td>
</tr>
</table>