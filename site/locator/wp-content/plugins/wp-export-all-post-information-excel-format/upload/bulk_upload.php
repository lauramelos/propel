<?php
$wp_upload_dir = wp_upload_dir();
$wp_upload_dir_sub_dir = substr($wp_upload_dir['subdir'],1,strlen($wp_upload_dir['subdir']));
if($_FILES['bulk_upload_csv']['name']!='' && $_FILES['bulk_upload_csv']['error']=='0')
{
	$row_insert_counter = 0;
	$row_update_counter = 0;
	$filename = $_FILES['bulk_upload_csv']['name'];
	$filenamearr = explode('.',$filename);
	$extensionarr = array('csv','CSV');
	if(in_array($filenamearr[count($filenamearr)-1],$extensionarr))
	{
		$upload_dir = wp_upload_dir();
		$basedir = $upload_dir['basedir'];
		$baseurl = $upload_dir['baseurl'];
		$destination_path = $basedir."/csv";
		if (!file_exists($destination_path))
		{
			mkdir($destination_path, 0777);				
		}
		$target_path = $destination_path .'/'. $filename;
		$csv_target_path = $target_path;
		if(@move_uploaded_file($_FILES['bulk_upload_csv']['tmp_name'], $target_path)) 
		{
			$fd = fopen ($target_path, "rt");
			$customKeyarray = array();
			$post_custom_arr = array();
			while (!feof ($fd))
			{
				$buffer = fgetcsv($fd, 4096);
				if($rowcount == 0)
				{
					$user_info = $wpdb->get_results("select ID,user_login from $wpdb->users");
					foreach($user_info as $user_info_obj)
					{
						$user_info_array[$user_info_obj->user_login]=$user_info_obj->ID;
					}
					$post_data_key = array();
					$post_image_arr = array();
					for($k=0;$k<count($buffer);$k++)
					{
						
						$customKeyarray[$k] = trim($buffer[$k]);
if($customKeyarray[$k]=='ID'){
$post_data_key[$k]='ID';
$ID = $k;
}else
if($customKeyarray[$k]=='post_title'){
$post_data_key[$k]='post_title';
$post_title=$k;
}else
if($customKeyarray[$k]=='post_content'){
$post_data_key[$k]='post_content';
}else
if($customKeyarray[$k]=='post_excerpt'){
$post_data_key[$k]='post_excerpt';
}else
if($customKeyarray[$k]=='category'){
$post_data_key[$k]='category';
}else
if($customKeyarray[$k]=='post_tag'){
$post_data_key[$k]='post_tag';
}else
if($customKeyarray[$k]=='post_author'){
$post_data_key[$k]='post_author';
}else
if($customKeyarray[$k]=='post_status'){
$post_data_key[$k]='post_status';
}else
if($customKeyarray[$k]=='comment_status'){
$post_data_key[$k]='comment_status';
}else
if($customKeyarray[$k]=='IMAGE'){
$post_image_arr[]=$k;
}else
{
	$post_data_key[$k]=$customKeyarray[$k];
	$post_custom_arr[$customKeyarray[$k]] = $k; 
}

if(trim($buffer[$ID])!='' || trim($buffer[$post_title])!=''){ 
}else
{
$form_url = site_url().'/wp-admin/admin.php';
echo '<form method="get" action="'.$form_url.'" name="error_form_redirect">';
echo '<input type="hidden" name="page" value="export" >';
echo '<input type="hidden" name="uemsg" value="id_title" >';
echo '</form>';
echo '<script>document.error_form_redirect.submit();</script>';
exit;
}
					}
				}else
				{
$my_post = array();
$id_val = array_keys($post_data_key,'ID');
$post_title_val = array_keys($post_data_key,'post_title');
$post_content_val = array_keys($post_data_key,'post_content');
$post_excerpt_val = array_keys($post_data_key,'post_excerpt');
$post_status_val = array_keys($post_data_key,'post_status');
$category_val = array_keys($post_data_key,'category');
$post_tag_val = array_keys($post_data_key,'post_tag');
$comment_status_val = array_keys($post_data_key,'comment_status');
$post_author_val = array_keys($post_data_key,'post_author');

$ID = trim($buffer[$id_val[0]]);
$post_title = trim($buffer[$post_title_val[0]]);
$post_content = trim($buffer[$post_content_val[0]]);
$post_excerpt = trim($buffer[$post_excerpt_val[0]]);
$category = trim($buffer[$category_val[0]]);
$post_tag = trim($buffer[$post_tag_val[0]]);
if($user_info_array)
{
	$post_author = $user_info_array[trim($buffer[$post_author_val[0]])];
}
$post_status = trim($buffer[$post_status_val[0]]);
$comment_status = trim($buffer[$comment_status_val[0]]);
if($ID!='' || $post_title!=''){
if($post_content){
	$my_post['post_content'] = $post_content;
}
if($post_excerpt){
	$my_post['post_excerpt'] = $post_excerpt;
}
if($category){
	$category_id=array();
	$category_arr =  explode(',',$category);
	for($ic=0;$ic<count($category_arr);$ic++)
	{
		if(trim($category_arr[$ic]))
		{
			$catname=trim($category_arr[$ic]);
			global $wpdb;
			$catid = $wpdb->get_var("select t.term_id from $wpdb->terms t join $wpdb->term_taxonomy tt on tt.term_id=t.term_id where t.name like \"$catname\" and tt.taxonomy='category'");
			if($catid){			
			}else
			{
				@wp_insert_term( $category_arr[$ic], 'category');
			}
			$category_id[] = get_cat_ID($catname);
		}
	}
$my_post['post_category'] = $category_id;
}
if($post_status){
$my_post['post_status'] = $post_status;
}
if($post_title){$my_post['post_title'] = $post_title;}
if($post_tag){
$my_post['tags_input'] =  explode(',',$post_tag);
}
if($comment_status){
$my_post['comment_status'] = $comment_status;
}
if($post_author){
$my_post['post_author'] = $post_author;
}

if($ID){
	$my_post['ID'] = $ID;
}elseif($post_title)
{
	$ptitle = $post_title;
	$pid = $wpdb->get_var("select ID from $wpdb->posts where post_title like \"$ptitle\" and post_status in ('publish','draft')");
	$my_post['ID'] = $pid;
}

$custom_meta = array();
foreach($post_custom_arr as $mkey=>$kval)
{
	if($buffer[$kval])
	{
		$custom_meta[$mkey] = $buffer[$kval];	
	}
}

$post_image = array();
if($post_image_arr)
{
	for($pm=0;$pm<count($post_image_arr);$pm++)
	{
		if(trim($buffer[$post_image_arr[$pm]]))
		{
			$post_image[] = trim($buffer[$post_image_arr[$pm]]);
		}
	}
}
	$row_update_counter++;
	$my_post['ID'] = '';
	$last_postid = @wp_insert_post($my_post);


if(!$my_post)
{
	$last_postid = $my_post['ID'];
}
if($custom_meta)
{
	foreach($custom_meta as $ckey=>$cval)
	{
		@update_post_meta($last_postid, $ckey, $cval);
	}
}
if($post_image){
	for($im=0;$im<count($post_image);$im++)
	{
		$menu_order = $im+1;
		$image_name_arr = explode('/',$post_image[$im]);
		$img_name = $image_name_arr[count($image_name_arr)-1];
		$img_name_arr = explode('.',$img_name);
		$post_img = array();
		$post_img['post_title'] = $img_name_arr[0];
		$post_img['post_status'] = 'attachment';
		$post_img['post_parent'] = $last_postid;
		$post_img['post_type'] = 'attachment';
		$post_img['post_mime_type'] = 'image/jpeg';
		$post_img['menu_order'] = $menu_order;
		$last_postimage_id = @wp_insert_post( $post_img );
		@update_post_meta($last_postimage_id, '_wp_attached_file', $wp_upload_dir_sub_dir.'/'.$post_image[$im]);
					
		$post_attach_arr = array(
							"width"	=>	580,
							"height" =>	480,
							"hwstring_small"=> "height='150' width='150'",
							"file"	=> $post_image[$m],
							);
							
		@wp_update_attachment_metadata( $last_postimage_id, $post_attach_arr );
	}
}
}
				}
			$rowcount++;
			}
			@unlink($csv_target_path);
			$form_url = site_url().'/wp-admin/admin.php';
			echo '<form method="get" action="'.$form_url.'" name="error_form_redirect">';
			echo '<input type="hidden" name="page" value="export" >';
			echo '<input type="hidden" name="msg" value="success" >';
			echo '<input type="hidden" name="rowcount" value="'.$row_update_counter.'" >';
			echo '</form>';
			echo '<script>document.error_form_redirect.submit();</script>';
			exit;
		}
		else
		{
			$form_url = site_url().'/wp-admin/admin.php';
			echo '<form method="get" action="'.$form_url.'" name="error_form_redirect">';
			echo '<input type="hidden" name="page" value="export" >';
			echo '<input type="hidden" name="uemsg" value="nomove" >';
			echo '</form>';
			echo '<script>document.error_form_redirect.submit();</script>';
			exit;
		}
	}else
	{
		$form_url = site_url().'/wp-admin/admin.php';
		echo '<form method="get" action="'.$form_url.'" name="error_form_redirect">';
		echo '<input type="hidden" name="page" value="export" >';
		echo '<input type="hidden" name="uemsg" value="csvonly" >';
		echo '</form>';
		echo '<script>document.error_form_redirect.submit();</script>';
		exit;
	}
}else
{
	$form_url = site_url().'/wp-admin/admin.php';
	echo '<form method="get" action="'.$form_url.'" name="error_form_redirect">';
	echo '<input type="hidden" name="page" value="export" >';
	echo '<input type="hidden" name="uemsg" value="wrongfile" >';
	echo '</form>';
	echo '<script>document.error_form_redirect.submit();</script>';
	exit;
}
?>