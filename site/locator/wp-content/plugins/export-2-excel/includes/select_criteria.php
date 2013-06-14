<?php
  ini_set('memory_limit', '256M');
  ini_set('max_execution_time', 300); //300 seconds = 5 minutes
  $extensions = array('xls' => '.xls', 'xlsx' => '.xlsx');
  $args = array (
      'public'   => true
  );
  $output = 'objects';
  $post_types = get_post_types($args, $output);


if ( isset($_POST['Submit']) ) {
	if (empty($_POST) || !check_admin_referer('e2e_export_data' )  ) {
    wp_die('Sorry, your nonce did not verify.');
    } elseif (!isset($_POST['ext']) || !array_key_exists($_POST['ext'], $extensions)  ) {
    wp_die('Please select a valid extension.');
    }  elseif (!isset($_POST['e2e_post_type']) || ( !array_key_exists($_POST['e2e_post_type'], $post_types ) && $_POST['e2e_post_type'] != 'signed_emails'  && $_POST['e2e_post_type'] != 'searches' && $_POST['e2e_post_type'] != 'attachment') ) {
     wp_die('Please select a post type.');
    }  elseif ($_POST['e2e_post_type'] == 'searches' && ( empty($_POST['date_from']) || empty($_POST['date_to']) ) ) {
	wp_die('Please select a correct date.');
	}	else {
      	$post_type = $_POST['e2e_post_type'];
      	$ext = $_POST['ext'];
      	$str = '';
      		if ( is_multisite() && $network_admin ) {

        	$blog_info = get_blog_list(0, 'all');
        		foreach ($blog_info as $blog) {
          		switch_to_blog($blog['blog_id']);
          		include('loop.php');
          		restore_current_blog();
        		}
      		} else {
        	include('loop.php');
      		}
      		
			switch($_POST['e2e_post_type']){
			case 'post':
      		$filename = 'propel-physician'.date('Ymd').'.'.$ext;
			break;
			
			case 'signed_emails':
			$filename = 'Email_Notification_Subscribers'.date('Ymd').'.'.$ext;
			break;
			
			case 'searches':
			$filename = 'searches-'.date('Ymd',strtotime($_POST['date_from'])).'-'.date('Ymd',strtotime($_POST['date_to'])).'.'.$ext;
			break;
			
			}
		
      		if ( $ext == 'xls' ) {
        	header("Content-type: application/vnd.ms-excel;");
      		} elseif( $ext == 'xlsx') {
        	header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, charset=utf-8;");
      		}
      	header("Content-Disposition: attachment; filename=" . $filename);
       	print $str;//$str variable is used in loop.php
      	exit();
    }
  } else { ?>
    <form name="export" action="<?php echo $form_action; ?>" method="post" onsubmit="return validate_form();">
      <div class="selection_criteria" >
        <div class="popupmain" style="float:left;">
          <p class="req_head"><?php echo 'Choose your criteria';?></p>
          <div class="formfield">
            <p class="row1">
              <label><?php echo 'Selection Criteria:';?></label>
              <em>

              <?php
              $count = 0;
			  $countP = 0;
              foreach ($post_types  as $key => $post_type ) {
			  $countP++;
              $divisor = 3;
              	if ($count % $divisor == 0) { ?>
                <kbd> <?php
                }
                
				if ( $post_type->name != 'attachment' && $post_type->name != 'page'  ) { ?>
                <i>
                <input type="radio" class="post_type" name="e2e_post_type" value="<?php echo $post_type->name; ?>" onclick="dates.style.display='none';"  />
                </i>
                <small>
				PHYSICIANS
                <?php //echo $post_type->label; ?>
                </small> <?php
				$count++;
                }
				  
				   if( $countP == count($post_types) ){ $count++; ?>
                     <i>
                      <input type="radio" class="post_type" name="e2e_post_type" value="searches" onclick="dates.style.display='';"  />			   
                     </i>
                     <small>
                      Searches
					 <br /><span id="dates" <? if ($_POST['post_type']<>'searches'){ ?> style="display:none" <? } ?>> 
					 From: <input type="text" class="post_type" name="date_from"  value="<? if ($_POST['date_from']) echo $_POST['date_from']; else echo date('Y-m-d',strtotime('-7 DAY'))?>" holder="YYYY-MM-DD" />&nbsp;&nbsp;
					 To: <input type="text" class="post_type" name="date_to"  value="<? if ($_POST['date_to']) echo $_POST['date_to']; else echo date('Y-m-d')?>" holder="YYYY-MM-DD"/>
					</span>
                     </small>
					
					 
					 <i>
                      <input type="radio" class="post_type" name="e2e_post_type" value="signed_emails" onclick="dates.style.display='none';" />
                     </i>
                     <small>
                       Email Notification Subscribers
                     </small>
					 
					 
                  <?php
                  }
				  
                  
                  /*if( $count == count($post_types) ){?>
                     <i>
                      <input type="radio" class="post_type" name="e2e_post_type" value="comment_authors"  />
                     </i>
                     <small>
                       Comments Authors
                     </small>
                  <?php
                  }*/
                  if ($count % $divisor == 0 ) { ?>
                    </kbd> <?php
                  }
                } ?>
              </em>
            </p>
            <p class="row1">
              <label><?php echo 'Select extension:'; ?></label>
              <em> <?php
                e2e_display_radio_buttons($extensions, 'ext'); ?>
              </em>
            </p>
            <?php wp_nonce_field('e2e_export_data'); ?>

            <p class="row1">
              <label>&nbsp;</label>
              <em>
                <input type="submit" class="button-primary" name="Submit" value="Submit" />
              </em>
            </p>
          </div>
        </div>
      </div>
    </form> <?php
  } ?>
  