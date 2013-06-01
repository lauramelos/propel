<?php
set_time_limit(0);global $wpdb;
if($_POST)
{
	if($_POST['act']=='upload')
	{
		require_once (WP_PLUGIN_DIR.'/wp_export/upload/bulk_upload.php');
	}elseif($_POST['act']=='update')
	{
		 require_once (WP_PLUGIN_DIR.'/wp_export/update/bulk_update.php');
	}	
}
?>
<div id="wrapper">
 
 <div class="titlebg">
    <span class="head i_mange_coupon"><h1><?php _e('WP Export');?></h1></span>  
    
 </div> <!-- sub heading -->
 <div id="page" >

<style>
h2 { color:#464646;font-family:Georgia,"Times New Roman","Bitstream Charter",Times,serif;
font-size:20px;
font-size-adjust:none;
font-stretch:normal;
font-style:italic;
font-variant:normal;
font-weight:normal;
line-height:35px;
margin:0;
padding:14px 15px 3px 0;
text-shadow:0 1px 0 #FFFFFF;  }
.emessage{
background-color:#F38D8D;
border:1px solid #FF0000;
padding:5px;
}
.widefat { width:80%;}
.errormsg{color:#FF0000; padding:7px 5px; border:solid 1px #D83848; font-weight:bold; background-color:#FCCFD7;}
</style>

<?php
if(file_exists(WP_PLUGIN_DIR.'/wp_export/messages.php'))
{
	include_once(WP_PLUGIN_DIR.'/wp_export/messages.php');
}
?>
<?php
if(file_exists(WP_PLUGIN_DIR.'/wp_export/upload/form.php'))
{
	include_once(WP_PLUGIN_DIR.'/wp_export/upload/form.php');
}

if(file_exists(WP_PLUGIN_DIR.'/wp_export/update/form.php'))
{
	include_once(WP_PLUGIN_DIR.'/wp_export/update/form.php');
}

if(file_exists(WP_PLUGIN_DIR.'/wp_export/export/form.php'))
{
	include_once(WP_PLUGIN_DIR.'/wp_export/export/form.php');
}
?>

</div> <!-- page #end -->
 </div>   <!-- wrapper #end -->