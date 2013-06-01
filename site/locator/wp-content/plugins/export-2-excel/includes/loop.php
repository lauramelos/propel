<?php
  if($post_type == 'post') {
    //query_posts(array('posts_per_page' => -1, 'order'=>'DESC', 'post_type' => $post_type));//-1 is for all posts
	$args = array(
				'posts_per_page' => -1,
				'post_type' => $post,//$post_type,
   				'meta_key' => 'zip',
                'orderby' => 'meta_value_num meta_value',
                'order'=>'ASC'
       			);
	$query = new WP_Query($args);
	$str .= '<table>';
              /*<tr>
                <td colspan=7>' . get_bloginfo('name').'
              </tr>;*/
    //if (have_posts()) {
	if ($query->post_count > 0) {
      $str .= '<tr>
                <th>csv_post_title</th>
                <th>csv_post_post</th>
                <th>csv_post_excerpt</th>
                <th>first_name_1</th>
                <th>last_name_1</th>
				<th>designation_1</th>
				<th>first_name_2</th>
				<th>last_name_2</th>
				<th>designation_2</th>
				<th>first_name_3</th>
				<th>last_name_3</th>
				<th>designation_3</th>
				<th>first_name_4</th>
				<th>last_name_4</th>
				<th>designation_4</th>
				<th>first_name_5</th>
				<th>last_name_5</th>
				<th>designation_5</th>
				<th>practice_name</th>
				<th>address</th>
				<th>suite</th>
				<th>city</th>
				<th>state</th>
				<th>zip</th>
				<th>phone_number</th>
				<th>email_address</th>
				<th>website</th>
              </tr>';
     // while (have_posts()) {
	 while ($query->have_posts()) : $query->the_post();
	 
        $str.= '
          <tr>
            <td>' . mb_convert_encoding(get_the_title(), 'HTML-ENTITIES', 'UTF-8') . '</td>
            <td>post</td>
            <td>excerpt</td>';
				for ($i=1; $i<6; $i++){ 
				if (get_field('first_name_'.$i) <> '') {
            	$str.='<td>' .get_field('first_name_'.$i) .'</td>
            	<td>' . get_field('last_name_'.$i) .'</td>
            	<td>' . get_field('designation_'.$i) . '</td>';
				}else $str.='<td></td><td></td><td></td>';
				
				
				}
            $str.='<td>' . get_field('practice_name') . '</td>
			<td>' . get_field('address') . '</td>
			<td>' . get_field('suite') . '</td>
			<td>' . get_field('city') . '</td>
			<td>' . get_field('state') . '</td>
			<td>' . get_field('zip') . '</td>
			<td>' . get_field('phone_number') . '</td>
			<td>' . get_field('email_address') . '</td>
			<td>' . get_field('website') . '</td>
          </tr>'; 
      //}
	  endwhile;
	  
      //wp_reset_query();
    } else {
      $str .= '<tr colspan="27"><td>No post found.</td></tr>';
    }
    $str.= '</table><br/></br>';
	
  } 
  
  if ($post_type == 'signed_emails') {
   global $wpdb;
	
    $str = '<table><tr>
             <th>Name</th><th>Email</th><th>Zip</th><th>Distance</th><th>Satus</th>
            </tr>';
   
   
$query_link = "SELECT * FROM datauserword order by id desc limit 100";
$res_emails = $wpdb->get_results( $query_link );
      

    foreach ($res_emails as $row)
    {
      $str.= '<tr>
              <td>'.$row->Name.'</td>';
      $str.= '<td>'.$row->Email."</td>";
      $str.= '<td>'.$row->Zip."</td>";
      $str.= '<td>'.$row->Distance."</td>";
      $str.= '<td>'; 
	  if ($row->enviado == 0) $str.= '<span style="color:#990000">Needs Physician Results</span>'; 
	  else $str.= '<span style="color:#006633">Mail Sent</span>';
	  $str .= '</td></tr>';
    }
  }

	if ($post_type == 'searches') {
   global $wpdb;
	
    $str = '<table><tr>
             <th>Date</th><th>Zipcode</th><th>Distance</th><th>Ocurrences</th>
            </tr>';
   
   
$query_link = "select distinct date_s, zipcode, distance, count(id) as ocurrence from searching where date_s >= '".date('Y-m-d', strtotime($_POST['date_from']))."' and date_s <= '".date('Y-m-d', strtotime($_POST['date_to']))."' group by date_s, zipcode, distance order by date_s desc, zipcode, distance";
$res_emails = $wpdb->get_results( $query_link );
      

    foreach ($res_emails as $row)
    {
      $str.= '<tr>
              <td>'.date('Y-m-d', strtotime($row->date_s)).'</td>';
      $str.= '<td>'.$row->zipcode."</td>";
      $str.= '<td>'.$row->distance."</td>";
      $str.= '<td>'.$row->ocurrence.'</td></tr>';
    }
  }
