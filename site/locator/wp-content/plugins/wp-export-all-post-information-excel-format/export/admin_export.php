<?php
global $wpdb;
if($_REQUEST['backup']=='posts')
{
	$user_info = $wpdb->get_results("select ID,user_login from $wpdb->users");
	foreach($user_info as $user_info_obj)
	{
		$user_info_array[$user_info_obj->ID]=$user_info_obj->user_login;
	}
	$all_meta_keys = $wpdb->get_col("select distinct(meta_key) from $wpdb->postmeta where post_id in (select ID from $wpdb->posts where post_type='post' and post_status in ('draft','publish'))");
	
	$post_sql = "select * from $wpdb->posts where post_type='post' and post_status in ('draft','publish')";
	$post_res = $wpdb->get_results($post_sql);
	if($post_res){
	$content_arr = array();
	$title_key_arr = array();
	$post_info_arr_count = array();
	foreach($post_res as $post_res_obj)
	{
		$title_arr = array();
		$content_arr1=array();
		$title_arr[] = 'ID';
		$content_arr1[]=$pid=$post_res_obj->ID;
		$title_arr[] = 'post_title';
		$content_arr1[]=$post_res_obj->post_title;
		$title_arr[] = 'post_content';
		$content_arr1[]=htmlentities($post_res_obj->post_content);
		$title_arr[] = 'post_excerpt';
		$content_arr1['post_excerpt']= $post_res_obj->post_excerpt;
		
		$CATEGORY_ARR = wp_get_post_terms($pid,$taxonomy = 'category', array('fields' => 'names'));
		$CATEGORY = '';
		if($CATEGORY_ARR){
			$CATEGORY=implode(',',$CATEGORY_ARR);
		}
		$title_arr[] = 'category';
		$content_arr1[]=$CATEGORY;
		$tags_ids =wp_get_post_terms($pid,$taxonomy = 'post_tag', array('fields' => 'names'));
		$tags = '';
		if($tags_ids)
		{
			$tags=implode(',',$tags_ids);
		}
		$title_arr[] = 'post_tag';
		$content_arr1['post_tag']=$tags;
		$title_arr[] = 'post_author';
		if($user_info_array)
		{
			$content_arr1['post_author']=$user_info_array[$post_res_obj->post_author];
		}
		$title_arr[] = 'post_status';
		$content_arr1['post_status']= $post_res_obj->post_status;
		$title_arr[] = 'comment_status';
		$content_arr1['comment_status']= $post_res_obj->comment_status;
		
		for($k=0;$k<count($all_meta_keys);$k++)
		{
			$content_arr1[] = get_post_meta($pid,$all_meta_keys[$k],true);//post meta
		}
		
		$content_arr[] = '<tr><td>'.implode('</td><td>',$content_arr1).'</td></tr>';
	}
	if($content_arr)
	{
		$title_arr1 =array_merge($title_arr,$all_meta_keys);
		$title_contnet = '<tr><td>'.implode('</td><td>',$title_arr1).'</td></tr>';
		$content_arr = implode('',$content_arr);
		$content_final = '<table border="1">'.$title_contnet.$content_arr.'</table>';
		$upload_dir = wp_upload_dir();
		$basedir = $upload_dir['basedir'];
		$baseurl = $upload_dir['baseurl'];
		$filename = "products_".date('YmdHis').".xls";
		$fp = fopen($basedir.'/'.$filename,'wr');
		fwrite($fp,$content_final);
		$excell_file = $baseurl.'/'.$filename; 
 		wp_redirect($excell_file);
	}
	exit;	
	}else
	{
		$form_url = site_url().'/wp-admin/admin.php';
		echo '<form method="get" action="'.$form_url.'" name="error_form_redirect">';
		echo '<input type="hidden" name="page" value="export" >';
		echo '<input type="hidden" name="uemsg" value="nodata" >';
		echo '</form>';
		echo '<script>document.error_form_redirect.submit();</script>';
		exit;
	}
}
?>