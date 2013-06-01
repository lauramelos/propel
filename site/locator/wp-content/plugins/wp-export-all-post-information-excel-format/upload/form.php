<?php
if($_REQUEST['msg']=='success')
{
	$wp_upload_dir = wp_upload_dir();
	$path=$wp_upload_dir['path'];
	$row_update_counter = $_REQUEST['rowcount'];
	echo "<br><p class=\"updated below-h2\"><br>CSV uploaded successfully.<br><br>Total of <b>$row_update_counter</b> records inserted successfully.<br><br>You need to upload images to <b>$path </b>folder, if any.<br><br></p>";
}
?>
<form action="<?php echo get_option('siteurl')?>/wp-admin/admin.php?page=export" method="post" name="bukl_upload_frm" enctype="multipart/form-data">
<input type="hidden" name="act" value="upload" />
  <h2><?php _e('Bulk Upload/Mass Upload'); ?></h2>
  <?php if($_REQUEST['msg']=='exist'){?>
  <div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);" >
    <p><?php _e('Updated successully.'); ?></p>
  </div>
  <?php }?>
  <table width="75%" cellpadding="3" cellspacing="3" class="widefat post fixed" >
    <tr>
      <td width="20%"><?php _e('Select CSV file'); ?></td>
      <td width="80%">:
        <input type="file" name="bulk_upload_csv" id="bulk_upload_csv1"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    <td><input type="submit" name="submit" value="<?php _e('Submit'); ?>" onClick="return check_upload_frm();" class="button-secondary action" >    </tr>
    <tr>
      <td>&nbsp;</td>
    <td>    </tr>
	<tr>
      <td colspan="2"><p style="color:#FF0000"><u><?php _e('Note');?></u>:- <?php _e('Please make sure "post_title" column should included in the CSV file otherwise system will never accept the data. New data will insert every time as per the file you are uploading. <b>Backup the database before doing any process.</b> Backup will helpful in the case of any mistake or accident.');?></p></td>
    </tr>
	 <tr>
      <td colspan="2"><b><a href="<?php echo WP_PLUGIN_URL; ?>/wp_export/sample.csv"><?php _e('Click to Download Sample CSV file');?></a></b></td>
    </tr>
  </table>
</form>
<script>
function check_upload_frm()
{
	if(document.getElementById('bulk_upload_csv1').value == '')
	{
		alert("<?php _e('Please select csv file to Insert Data');?>");
		return false;
	}
	return true;
}
</script>