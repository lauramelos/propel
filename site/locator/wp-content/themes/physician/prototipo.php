<?php

/*Template Name: FIND-PROPEL*/

?>

<?php get_header(); ?>
<!-- inner-content START -->
  <div id="inner-content">
    <!-- header-strip START -->
    <!--<div id="header-strip" style="background-images:url(_img/content/find-a-propel-physician.jpg); background-repeat:no-repeat; width:938px; height:128px; display:block;">-->
    <div class="findapropel">
      <p>FIND A PROPEL PHYSICIAN</p>
    </div>
        <!-- Content Page Navigation END -->
        <!-- Column Content START -->
    <div id="column-content">
      <div class="post" id="post-<?php the_ID(); ?>">
        <a name="search" id="search"></a>
        <div style=" width:95%; background-color:#fcac00; padding-left:50px; padding-top:16px; padding-bottom:8px; font-family: Helvetica, Arial;">
          <h1 class="find-title">REFINE YOUR SEARCH</h1>
          <form name="locator_form" id="locator_form" method="get" action="#search" > 
            <div style="float:left;  width:191px;">
              <span class="moverlabeldos">
                <input type="text" name="zipcode" id="zipcode" class="campouno" placeholder="Zip code*" value="<?=$_GET['zipcode']?>">
              </span>
            </div>	  
            <div class="physician_field" id="distance_background" style="float:left;">
              <select name="distance" id="distance" class="chzn-select-no-single">
                <option value='' disabled selected style='display:none;'>Distance*</option>
                <option value="25" <?php if($_GET['distance']=='25'){ echo "selected"; } ?>>25 miles</option>
                <option value="50" <?php if($_GET['distance']=='50'){ echo "selected"; } ?>>50 miles</option>
                <option value="100" <?php if($_GET['distance']=='100'){ echo "selected"; } ?>>100 miles</option>
                <option value="250" <?php if($_GET['distance']=='250'){ echo "selected"; } ?>>250 miles</option>
              </select>
            </div>
            <input type="submit" name="submit" id="submit" class="searchbutton" value=" "> 
            <script src="http://67.222.18.91/~propel/locator/wp-content/themes/physician/chosen/chosen.jquery.js" type="text/javascript"></script>
            <script type="text/javascript"> 
              var config = {
                '.chzn-select'           : {},
                '.chzn-select-deselect'  : {allow_single_deselect:true},
                '	width:180px;
.chzn-select-no-single' : {disable_search_threshold:10},
                '.chzn-select-no-results': {no_results_text:'Oops, nothing found!'},
                '.chzn-select-width'     : {width:"95%"}
              }
              for (var selector in config) {
                $(selector).chosen(config[selector]);
              }
            </script>
          </form>
          <p style="color:#FFF; margin-top:10px; font-size:12px; font-style:italic; display:block; width:600px; clear:both; margin-top:42px; padding-left:4px;"> *Required Fields</p>
        </div>

        <?php
        // UNSUBSCRIBE
          if( isset($_GET['unsuscribe']) ) :
            global $wpdb;
            $yaenvio= $wpdb->update("datauserword", 
              array('enviado' => 1 ),
              array('Email' => $_GET['unsuscribe'])
            );
          ?>
          <div class="<?php post_class(); ?> id="post-<?php the_ID(); ?>">
            <div class="ctotal">
              <p class="hay">
              <?php if($yaenvio) { echo "You have been unsubscribed from our physician notification list."; }
                    else { echo "Your mail was not in our physician notification list."; }
              ?>
              </p>
            </div>  
          </div>
        <?php endif; ?>

        <?php //SUBSCRIBE
        if ( !empty ($_POST['submitnf'])) {  ?>
          <script>window.location.hash="submitnf_position";</script>
          <?php global $wpdb;
          $update = $wpdb->update("datauserword", 
            array(
               'Name' => $_POST['namenf'],
               'enviado' => 0,
               'Distance' => $_POST['distancenf'],
              ),
              array('Email' => $_POST['emailnf'],
                    'Zip' => $_POST['zipnf']
              )
            );
          if(!$update) {
            $user_count = $wpdb->get_var( "SELECT COUNT(*) FROM 'datauserword' WHERE Email ='".$_POST['emailnf']."' AND Zip = '".$_POST['zipnf'])."'";
            // if(!$user_count) $ban = $wpdb->insert( 'datauserword', array('Zip' => $_POST['zipnf'], 'Distance' => $_POST['distancenf'], 'Email' => $_POST['emailnf'], 'Name' => $_POST['namenf']), array('%s','%d', '%s','%s'));
            $ban = $wpdb->insert( 'datauserword', array('Zip' => $_POST['zipnf'], 'Distance' => $_POST['distancenf'], 'Email' => $_POST['emailnf'], 'Name' => $_POST['namenf']), array('%s','%d', '%s','%s'));
          }

        }?>

        <?php // BUSQUEDA DOCTORS
        if ( !empty($_GET['submit']) ) { ?>

          <a name="submitnf_position"></a>
          <div id="ctotal" ></div> 
            <?php global $wpdb;
            $radius=$_GET['distance'];
            $coordes= $wpdb->get_results("select Lon, Lat from zipcodes where Zipcode='{$_GET['zipcode']}'");
            if (count($coordes)) { 
              $bant=0;
              //GUARDA BUSQUEDA PARA INFORME POSTERIOR
              foreach ($coordes as $coords) 
                $bans = $wpdb->insert( 'searching', array('date_s' => date("Y-m-d"), 'zipcode' => 	$_GET['zipcode'], 'distance' => $_GET['distance'], 'Lat' => $coords->Lat, 'Lon' => $coords->Lon), array('%s','%s', '%d', '%s', '%s'));
              //CONTINUA BUSQUEDA
              $founds_zips = $wpdb->get_results("SELECT Zipcode, MIN( 3959 * acos( cos( radians( ".$coords->Lat." ) ) * cos( radians( Lat ) ) * cos( radians( Lon ) - radians( ".$coords->Lon." ) ) + sin( radians( ".$coords->Lat." ) ) * sin( radians( Lat ) ) ) ) AS distance FROM zipcodes GROUP BY Zipcode HAVING distance <= ".$radius." ORDER BY distance");
             ?>
             <script src='http://maps.googleapis.com/maps/api/js?sensor=false' type='text/javascript'></script>
             <script type="text/javascript">
             //<![CDATA[
               function get_map(lat, lng, ide,address){
                 document.getElementById("map_" + ide).innerHTML="<a href='http://maps.google.com/?ll=" + lat + "," + lng +  "&z=16&q=" + address + "' target='_blank'><img src='http://maps.google.com/maps/api/staticmap?center=" + address +  "&zoom=16&size=335x119&sensor=false&markers=icon:http://67.222.18.91/~propel/_img/content/map-marker.png|" + lat + "," + lng +  "' /></a>";	
               }
               function locateByAddress(address,ide){
                 var geocoder = new google.maps.Geocoder();
                 geocoder.geocode({'address':address},function(results,status){
                   if(status == google.maps.GeocoderStatus.OK){
                     coordinates = results[0].geometry.location.lat()+','+results[0].geometry.location.lng();
                     get_map(results[0].geometry.location.lat(),results[0].geometry.location.lng(),ide,address);
                   }
                 });
               }
             //]]>
             </script>
             <?php if (count($founds_zips)>0) {
               $encontrados=array();
               $compara=array();
               foreach ($founds_zips as $fzip) $compara[]=$fzip->Zipcode;
               $perpage=10;
               $paged = get_query_var('paged') ? get_query_var('paged') : 1;
               $args = array( 'meta_query' => array(array(
                    'key' => 'zip',
                    'value' => $compara,
                    'compare' => 'IN',
                    'type' => 'NUMERIC'
                   )
                 ),
                 'posts_per_page' => $perpage,
                 'paged' => $paged,
                 'meta_key' => 'zip',
                 'orderby' => 'meta_value_num meta_value',
                 'order'=>'ASC'
               );
               $query= null;
               $query = new WP_Query($args);
               if ($query->post_count > 0) {
                 $bant++;
                 while ($query->have_posts()) : $query->the_post(); ?>
                 <?php
                   if (!in_array(get_the_ID(), $encontrados)) {
                     $encontrados[]=get_the_ID();
                     $suite = get_field('suite');
                     $location = get_field('address_line_1'  );
                     if ($location['coordinates']<>'' && strlen($location['coordinates'])>1){
                       $temp = explode(','  , $location['coordinates' ]);
                       $lat = (float) $temp[0];
                       $lng = (float) $temp[1];
                     }
                     if (strlen($location['address'])>1)
                       $direc = $location['address' ];
                     else
                       $direc = $location;
                     ?>
                     <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                       <div class="entry">
                         <!--fichas de los medicos-->
                         <?php
                         $founds_d= $wpdb->get_results("SELECT ( 3959 * acos( cos( radians( ".$coords->Lat." ) ) * cos( radians( Lat ) ) * cos( radians( Lon ) - radians( ".$coords->Lon." ) ) + sin( radians( ".$coords->Lat." ) ) * sin( radians( Lat ) ) ) ) AS distance FROM zipcodes where zipcode=".get_field('zip'));
                         foreach ($founds_d as $fd) ?>
                           <div class="theoffice">
                             <div class="mapgoogle">
                               <div id="map_<?php the_ID(); ?>" class="mapagoogle"></div><!--$fzip->distance-->
                                 <p id="pdistance_<?php the_ID(); ?>">
                                   <strong>Distance: <?php echo round( $fd->distance * 100) / 100; ?> miles</strong>
                                   <a href="https://maps.google.com/maps?q=<?php echo $direc;?>&hl=en&t=h&mra=ls&z=16&layer=t" target="_blank" style="float:right;">Directions</a>
                                 </p>
                               </div>
                               <div class="names">
                                 <?php for ($i=1;$i<6;$i++) { 
                                 if (get_field('first_name_'.$i) <> '') { ?>
                                   <span><!--Physician:--><strong><?php the_field('first_name_'.$i); ?> <?php the_field('last_name_'.$i); ?> <?php the_field('designation_'.$i); ?></strong></span>
                                 <?php }
                                 } ?>
                               </div>
                               <?php $pn=get_field('practice_name'); 
                               if (!empty($pn)) { ?>
                                 <span><!--Practice Name:--> <?php the_field('practice_name'); ?></span>
                               <?php } ?>
                               <span><!--Address:--><?php the_field('address'); ?>
                                 <?php if(!empty($suite)) { echo '<br>' . $suite; } ?>
                                 <br><? the_field('city') ?>, <? the_field('state')?> <? the_field('zip')?>
                               </span>
                               <!--<iframe width="335" height="119" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.googleapis.com/maps/api/staticmap?center=<?php //echo the_field('address_line_1'); ?>"></iframe>-->
                               <div style="margin-top:20px; margin-bottom:18px;">
                                 <span>
                                   <?php $ph=get_field('phone_number');
                                   if (!empty($ph)) { ?>
                                     Tel: <?php the_field('phone_number'); ?><br />
                                   <?php } ?>
                                   <?php $ea=get_field('email_address');
                                     if (!empty($ea)) { ?>Email: <span style="display:inline; color:#fcac00"><a href="mailto:<?php the_field('email_address'); ?>"><?php the_field('email_address'); ?></a></span><br /><? } ?>
                                   <?php $ws=get_field('website'); 
                                     if (!empty($ws)) { if (stripos($ws, 'http://') === false) $lws='http://'.$ws; else $lws=$ws; ?>Website: <span style="display:inline; color:#fcac00"><a href="<?=$lws?>" target="_blank"><?php the_field('website'); ?></a></span> <? } ?>
                                 </span>
                               </div>
                               <?php if ($location['coordinates']<>'' && strlen($location['coordinates'])>1){ ?>
                               <script>get_map('<?php echo $lat; ?>','<?php echo $lng; ?>',<?php the_ID(); ?>,'<?php echo $direc; ?>');</script>
                               <?php } else { ?>
                               <script>locateByAddress('<?php echo $direc; ?>',<?php the_ID(); ?>);</script>
                               <?php } ?>
                             </div>
                           </div>
                         </div>
                  <?php } ?>
                <?php endwhile; ?>
                <?php if (count($encontrados)>0){ ?>
                   <script>$('#ctotal').html('<p class="hay">There are <span><?=$query->found_posts?></span> offices within <span><?=$_GET['distance']?> miles</span> from zip code <span><?=$_GET['zipcode']?></span>.</p>');</script>
                  <?php }
                 if ($query->found_posts > $perpage) {
                  if ( function_exists('wp_pagenavi') ) {
                    wp_pagenavi( array( 'query' => $query ));
                  } elseif ( get_next_posts_link() || get_previous_posts_link() ){ ?>
                    <div class="wp-navigation clearfix">
                      <div class="alignleft"><?php next_posts_link('&laquo; <<'); ?></div>
                      <div class="alignright"><?php previous_posts_link('>> &raquo;'); ?></div>
                    </div>
                <?php 
                } //if wp_pagenavi
              }
              
            $query= null;
            } 

 }
             } else { ?>
              <p><br />There aren't zipcodes on that range.</p>
      <?php
      }
  }
  else
  { ?>
    <input id="ZipValido" type="hidden" name="opcion" value="NO" />
<?php } ?>

<?php if ( (count($founds_zips)>0 && $bant == 0) ||  count($coordes)== 0) :?>

    <div style="width:770px; margin-right:auto; margin-left:auto;">

        <script>$('#ctotal').html('<p class="nohay">There are no results within <span><?=$_GET['distance']?> miles</span> from zip code <span><?=$_GET['zipcode']?></span>.</p>');</script>

        <p style="display:block; margin-top:10px; margin-bottom:40px; font-size:16px; line-height:24px;">
          Please widen your search distance, or fill out the form below to sign up for an email notification once there is a PROPEL physician in your area.
        </p>

          <div class="left-container">
            <div class="left-content-header">
              <h1>EMAIL NOTIFICATIONS</h1>
            </div>
          </div>

        <p style="margin-bottom:10px; margin-left:2px;">
          Fill out this form to receive an email notification once a PROPEL physician is in your area.
        </p>
        
        <div id="contentmail">
          <div id="contentmailleft" style="position:relative; float:left; width: 500px">
            <form method="post" action="" name="nf" id="nf">

              <p class="moverlabel"><input type="text" name="namenf" id="namenf" placeholder="Name"></p>

              <p class="moverlabel"><input type="text" name="emailnf" id="emailnf" placeholder="Email*"></p> 

              <p class="moverlabel"><input type="text" value="<?=$_GET['zipcode']?>" name="zipnf" id="zipnf"  placeholder="Zip Code*" ></p> 

              <div class="physician_field">
                <select name="distancenf" id="distancenf" class="chzn-select-no-single">
                  <option value="25" <?php if($_GET['distance']=='25' || $_POST['distancenf']=='25'){ echo "selected"; } ?>>25 miles</option>
                  <option value="50" <?php if($_GET['distance']=='50' || $_POST['distancenf']=='50'){ echo "selected"; } ?>>50 miles</option>
                  <option value="100" <?php if($_GET['distance']=='100' || $_POST['distancenf']=='100'){ echo "selected"; } ?>>100 miles</option>
                  <option value="250" <?php if($_GET['distance']=='250' || $_POST['distancenf']=='250'){ echo "selected"; } ?>>250 miles</option> 
                </select>
              </div>
              <p style="margin-top:10px; margin-left:2px;">
                <input type="checkbox" name="chagree" id="chagree" value="1" onchange="agree_submit()">
                <label for="chagree"></label>&nbsp;I agree to the <a href='#'>Terms and Conditions of Use</a>
              </p>
      
              <p id="sub_agree" style="margin-top:10px;">
                <input type="submit" name="submitnf" id="submitnf" class="signup_not" value=" " disabled >
              </p>
      
              <script src="http://67.222.18.91/~propel/locator/wp-content/themes/physician/chosen/chosen.jquery.js" type="text/javascript"></script>
              <script type="text/javascript"> 
              var config = {
                '.chzn-select'           : {},
                '.chzn-select-deselect'  : {allow_single_deselect:true},
                '.chzn-select-no-single' : {disable_search_threshold:10},
                '.chzn-select-no-results': {no_results_text:'Oops, nothing found!'},
                '.chzn-select-width'     : {width:"95%"}
              }
              for (var selector in config) {
                $(selector).chosen(config[selector]);
              }
              </script>
            
        </form>
  </div> <!--fin de contentmailleft --> 
  <div id="contentmailright" style="position:relative; float:right; width:30%"> 
  <?php if ( !empty($_POST['submitnf']) ) {  ?>
    <span><h1 style="font-size:18px;  margin-left:auto; margin-right:auto; line-height:24px">
      Thank you for signing up. We will send you an email notification when a physician is added in your area.
    </h1></span>

  <?php } ?>

  </div><!--fin de contentmailright --> 
  </div> <!-- fin de contentmail -><div style="clear:both"></div-->

</div>

<?php endif; ?>
        </div>
        <!-- Column Content END -->
      </div>
    <!-- inner-content END -->
    </div>
    <div id="content-bottom"></div>
    <!-- content END -->
<div style="display:none;">
<div id="physician_locator" class="inline-content" style="width:400px;padding:0px;">
  <div style="background-color:#faac23;height:70px;width:400px;color:#fff; padding-top:34px;">
        <a href="#" class="cbox-close-orange"></a>
        <p id="physicianHeader" class="find-title">FIND A PROPEL<br /> PHYSICIAN NEAR YOU</p>
      </div>
  <div style="background-color:#fff;height:320px;width:400px;color:#989794;font-family:Arial;font-size:15px;line-height:19px;">
  <p id="physicianInstructions" style="padding:30px 50px 17px 50px;">Please fill in your zip code and select a distance to find a physician nearest to you.</p>
  <div id="locatorWrapper" style="padding:0px 50px 45px 50px;">
    <form method="get" action="http://67.222.18.91/~propel/locator/find-propel-physician/" onsubmit="return validateForm()" id="locator">
      <div style="float:left;"> 
        <div class="physician_field" id="zipCodeDiv" style="margin-bottom:7px!important;">
          <input type="text" name="zipcode" id="zipcode" style="padding-left:10px; font-size:18px;" class="physician_field"  placeholder="Zip code*" onchange="validateTextZip()">
        </div>
        <div class="physician_field" id="distanceDiv">
          <select name="distance" id="distance" class="physician_field chzn-select-no-single" tabindex="5" onchange="validateComboDistance()">
            <option value="" disabled="" selected="" style="display:none;">Distance*</option>
            <option value="25">25 miles</option>
            <option value="50">50 miles</option>
            <option value="100">100 miles</option>
            <option value="250">250 miles</option>
          </select>
        </div>
      </div>
      <div style="clear:both;padding-top:5px;">
        <input id="physicianSubmit" name="submit" type="submit" style="display:block;width:180px;" /> 
        <span style="float:left; margin-top:45px; display:block; margin-bottom:20px;"><i>* Required fields</i></span>
      </div>
      <script src="http://67.222.18.91/~propel/chosen/chosen.jquery.js" type="text/javascript"></script>
      <script type="text/javascript"> 
        var config = {
          '.chzn-select'           : {},
          '.chzn-select-deselect'  : {allow_single_deselect:true},
          '.chzn-select-no-single' : {disable_search_threshold:10},
          '.chzn-select-no-results': {no_results_text:'Oops, nothing found!'},
          '.chzn-select-width'     : {width:"95%"},
        }
        for (var selector in config) 
        {
          $(selector).chosen(config[selector]);
        }
      </script>
    </form>
  </div>
  </div>
</div>
</div>

<script>
function agree_submit(){
  if($('#submitnf').attr('disabled') == 'disabled'){ 
  $('#submitnf').removeClass('signup_not'); 
  $('#submitnf').addClass('signup'); 
  $('#submitnf').removeAttr('disabled');
  }else{ 
  $('#submitnf').removeClass('signup'); 
  $('#submitnf').addClass('signup_not'); 
  $('#submitnf').attr('disabled', 'disabled');	
  }
}

</script>
<?php
/**
 * Initiate the script.
 * Calls the validation options on the comment form.
 */
function pbd_vc_init() { ?>
  <script type="text/javascript">
    function isValidUSZip(sZip) { return /^\d{5}(-\d{4})?$/.test(sZip); }
    $('.chzn-results li').click(function() { 
      document.getElementById('distance_chzn').children[0].style.backgroundColor = '#eeeeee';
      document.getElementById('distance_chzn').children[0].style.color = '#949590';
      document.getElementById('distance_background').style.backgroundColor = '#eeeeee';
    });
 
    $('#locator_form').submit(function() {
      var result = true;
      document.getElementById('distance_chzn').children[0].style.backgroundColor = '#eeeeee';
      document.getElementById('distance_chzn').children[0].style.color = '#949590';
      document.getElementById('distance_background').style.backgroundColor = '#eeeeee';
      if(document.getElementById('distance').value == '') {
        document.getElementById('distance_chzn').children[0].style.backgroundColor = 'red';
        document.getElementById('distance_chzn').children[0].style.color = 'white';
        document.getElementById('distance_background').style.backgroundColor = 'red';
        result = false;
      }
      return result;
    });

    jQuery(document).ready(function($) {
      $('#locator_form').validate({
        rules: {
          zipcode: {
            minlength: 5,
            required: true,
            number: true
          },
          distance: {
            required:true
          } 
        },
        messages: {
          zipcode: "Please enter a valid Zipcode.",
          distance: "Please select a Distance."
        }
      });
      $('#nf').validate({
        rules: {
          namenf: {
            required: true
          },
          emailnf: {
            required: true,
            email: true
          },
          zipnf: {
            required: true,
            minlength: 5
          }, 
          distancenf: {
            required: true
          },
          chagree: {
            required: true
          }
        },
        messages: {
          namenf: "Please enter your name.",
          emailnf: "Please enter a correct e-mail.",
          zipnf: "Please enter a valid Zipcode.",
          distancenf: "Please select a Distance.",
          chagree: "You have to agree to the Terms and Conditions of Use."
        }
      });
    });
  </script>
<?php }
add_action('wp_footer', 'pbd_vc_init', 999);
?>
<?php get_footer(); ?>
