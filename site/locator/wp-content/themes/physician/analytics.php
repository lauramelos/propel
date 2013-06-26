<?php
/*
Template Name: analitycs
*/
?>
<?php get_header(); ?>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/_js/jQRangeSlider-5.1.1/css/classic.css" type="text/css" />
<script src="<?php bloginfo('template_directory'); ?>/_js/jQRangeSlider-5.1.1/lib/jquery-1.7.1.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/_js/jQRangeSlider-5.1.1/lib/jquery-ui-1.8.16.custom.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/_js/jQRangeSlider-5.1.1/jQDateRangeSlider-min.js"></script>
<script src='http://maps.googleapis.com/maps/api/js?sensor=false' type='text/javascript'></script>
<script type="text/javascript">
  /* BARRA DE TIEMPO */
  jQuery(document).ready(function($) {
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
    $("#sliderdate").dateRangeSlider({
      bounds: {min: new Date(<?=date('Y')?>, 0, 1), max: new Date(<?=date('Y')?>,11,31)},
      defaultValues: {min: new Date(<? if (isset($_GET['date_from'])) echo date('Y,m-1,d',strtotime($_GET['date_from'])); else echo date('Y,m-1,d',strtotime('-7 DAY'));?>), max: new Date(<? if (isset($_GET['date_to'])) echo date('Y,m-1,d',strtotime($_GET['date_to'])); ?>)},
      scales: [{
        first: function(value){  return value; },
        end: function(value) {   return value; },
        next: function(value){
          var next = new Date(value);
          return new Date(next.setMonth(value.getMonth() + 1));
        },
        label: function(value){
          return months[value.getMonth()];
        }
      },
      {
        first: function(vale){  return vale; },
        end: function(vale) {   return vale; },
        next: function(vale){
          var next = new Date(vale);
          if (vale.getMonth == 1) 
            return new Date(next.setDate(vale.getDate() + 4));
          else
          return new Date(next.setDate(vale.getDate() + 6));
        },
        label: function(vale){
          return null;
        }
      }]
    });
    $("#sliderdate").bind("valuesChanged", function(e, data){
      var f= new Date(data.values.min);
      f = new Date(f.setMonth(f.getMonth() + 1));
      var t= new Date(data.values.max);
      t = new Date(t.setMonth(t.getMonth() + 1));
      dfrom=f.getFullYear() + '-' + f.getMonth() + '-' + f.getDate();
      dto=t.getFullYear() + '-' + t.getMonth() + '-' + t.getDate();
      $("#date_from").val(dfrom);
      $("#date_to").val(dto);
    });
  });
  /* GENERA MAPA */
  var markers= new Array();
  var markersP= new Array();
  function get_map_mult(){
    var latlng = new google.maps.LatLng(37.06, -95.67);
    var myOptions = {
        zoom: 4,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false
      };
    map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
    var infowindow = new google.maps.InfoWindow(), marker, i;
    for (i = 0; i < markers.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(markers[i][1], markers[i][2]),
        map: map
      });
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(markers[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  }

  function locateByAddress( address, ide, pname){
    console.log('ide', ide);
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address':address},function(results,status){
      if(status == google.maps.GeocoderStatus.OK){
        var coordina=[pname,results[0].geometry.location.lat(),results[0].geometry.location.lng()];	
        markersP[ide]=coordina;
      } else if(status == "OVER_QUERY_LIMIT"){
        var sto = setTimeout(function(){
          locateByAddress( address, ide, pname );
        },100);
      };
    });
  }
    
  function marca_phy(){
    var infowindow = new google.maps.InfoWindow(), marker, i;
    for (i = 0; i < markersP.length; i++) {  
      if (markersP[i]) {
        window["marker" + i ] = new google.maps.Marker({
          position: new google.maps.LatLng(markersP[i][1], markersP[i][2]),
          icon:'http://67.222.18.91/~propel/_img/content/map-marker2.png',
          map: map
        });
        google.maps.event.addListener(window["marker" + i ] , 'click', (function(marker, i) {
          return function() {
            infowindow.setContent(markersP[i][0]);
            infowindow.open(map, marker);
          }
        })(window["marker" + i ] , i));
      }
    }
  }

  function show_me() {
    if ($('#showPhy').html()=='Show Physicians'){
      $('#showPhy').html('Hide Physicians');
      $('#showPhy').css("background-color","#ffad00");
      marca_phy();
    } else {
      $('#showPhy').html('Show Physicians');
      $('#showPhy').css("background-color","#578155");
      for (i = 0; i < markersP.length; i++) {
        if (markersP[i]) window["marker" + i ].setMap(null);
      }
    }
  }
</script>
 <div id="inner-content">
   <div id="column-content">
      <div id="ctotal" class="resultsearch"></div> 
       <div class="selectordate">
          <div id="sliderdate"></div> 
          <form action="" method="get">
            <input type="hidden" id="date_from" name="date_from" value="<?=date('Y-m-d', strtotime('-7 DAY'))?>" /><input type="hidden" id="date_to" name="date_to" value="<?=date('Y-m-d')?>" />
            <input type="submit" name="submitsz" value=" " id="submitsz" class="searchanalityc">
          </form>
        </div>
        <div id="map_canvas" style="width: 882px; height: 500px; "></div>
        <?php if ( !empty($_GET['submitsz']) ) { ?>
        <div id="showPhy" onclick="show_me()" style="padding-left:7px;
padding-right:7px; padding-top:3px; padding-bottom:3px;
border:1px solid #8c8c8c;font-size:11px;
text-transform:uppercase;background-color:#578155;
color:#FFF; position:absolute; right:50px; top:356px; cursor:pointer;">Show Physicians</div>  
      <?
      $url= get_permalink().'?date_from='.$_GET['date_from'].'&date_to='.$_GET['date_to'].'&submitsz=+';
      $registros = 20; // Número de ítems por página.
                    
      // MEDICOS EXISTENTES
      $query= null;
      $query = new WP_Query(array('posts_per_page' => -1));
      $phy_i=0;
        while ($query->have_posts()) : $query->the_post(); 
        $cname='';
        for ($i=1;$i<6;$i++) { 
          if (trim(get_field('first_name_'.$i)) <> '') 
          $cname.=get_field('first_name_'.$i)." ".get_field('last_name_'.$i)." ".get_field('designation_'.$i)."<br>"; 
        } 
        
        $location = get_field('address_line'  );
        
          if ($location['coordinates']<>'' && strlen($location['coordinates'])>1){
          $temp = explode(','  , $location['coordinates' ]);
          $lat = (float) $temp[0];
          $lng = (float) $temp[1];
          }
          
          if (strlen($location['address'])>1)
          $direc=$location['address' ];
          else
          $direc=$location;
        
          if ($location['coordinates']<>'' && strlen($location['coordinates'])>1){
          ?>
          <script>
          var coordina=['<?=$cname?>',<?=$lat?>,<?=$lng?>];	
          markersP[<?= $phy_i ?>]=coordina;
          </script>
          <? 
          }else{ 
            ?><script>
           jQuery(function($){
             $(document).ready(function() {
               locateByAddress('<?= $direc; ?>',<?= $phy_i ?>,'<?= $cname ?>');
             });
           });
          </script><?
          }
        $phy_i=$phy_i+1;
        endwhile;
      
      //FIN MEDICOS EXISTENTES
      $html = null;
      $paginador = null;
      
      if (!$page) { 
          $inicio = 0; 
          $page = 1; 
      } else	$inicio = ($page - 1) * $registros;
      
      global $wpdb;
      $columns = " date_s, zipcode, distance, Lat, Lon, count(id) as ocurrence ";
      $where = " date_s >= '".date('Y-m-d', strtotime($_GET['date_from']))."' and date_s <= '".date('Y-m-d', strtotime($_GET['date_to']))."' ";
      $limit = ' LIMIT '. $inicio .' , '. $registros;
      $query1 = "select distinct ".$columns."  from searching where ".$where." group by date_s, zipcode, distance order by date_s desc, zipcode, distance";
      $query_limit = $query1.$limit;
      $coordes= $wpdb->get_results($query1);
      $total_registros = count ($coordes);
        foreach ($coordes as $pindex=>$pines){ ?>
          <script type="text/javascript">
          var coordina=['<?=$pines->zipcode?>',<?=$pines->Lat?>,<?=$pines->Lon?>];
          markers[<?=$pindex?>]=coordina;
          </script>
        <? }
      $coordes_pag = $wpdb->get_results($query_limit);
      $total_paginas = ceil($total_registros / $registros);
        //if (count($coordes) > 0 ){					
                  if ($total_registros > 0 ){	?>		
                 <div class="divtable">
                  <div class="toptable">
                     <div class="datediv">
                     <p class="dateinfo">Date <span><?=date('d M Y', strtotime($_GET['date_from']))?></span> <span>/</span> <span><?=date('d M Y', strtotime($_GET['date_to']))?></span></p>
                     </div>
           <div class="pagetable">
                     <?php
                      $paginador ='<ul class="paginator">';
          if(($page - 1) >= 2)
                  $paginador .='<li class="next" ><a  href="'. $url .'&page='. ($page - 1) .'"><<</a></li>' . "\n";
              elseif(($page - 1) == 1)
                  $paginador .='<li class="next" ><a  href="'. $url .'">&laquo; <<</a></li>' . "\n";
          for ($i = 1; $i <= $total_paginas; $i++) { 
                  if ($i == $page)
                      $paginador .='<li class="act">'. $page .'</li>' . "\n"; 
                  elseif($i == 1)
                      $paginador .='<li><a href="'. $url .'" title="Go to page  1">1</a></li>' . "\n";
                  else
                      $paginador .='<li><a href="'. $url .'&page='. $i .'" title="Go to page  '. $i .'">'. $i .'</a></li>' . "\n"; 
              }
              if( ($page + 1) <= $total_paginas )
                   $paginador .='<li class="next" ><a  href="'. $url .'&page='. ($page + 1) .'">>></a></li>' . "\n";
          echo $paginador .='</ul>';
              ?>
                     </div>
                  </div>
        <!--table-->
                  <div class="content-table">
                  <table class="tableresult" border="0" style="border-collapse:collapse;">
                   <tr class="headertable">
                    <td>Date</td><td>Zipcode</td><td>Distance</td><td>Ocurrences</td>
                   </tr>
                  <?
          foreach ($coordes_pag as $index=>$scorde){ 
            //echo count($scorde);
            ?>
                  <tr class="data" id="search_<?=$index?>">
                   <td><?= $scorde->date_s?></td><td><?= $scorde->zipcode?></td><td><?= $scorde->distance?></td><td><?= $scorde->ocurrence?></td>
                  </tr>
          <? } ?>
        <script>$('#ctotal').html('<p class="searchr">There where <span><?=count($coordes)?> searches</span> between the selected dates.</p>');</script><?
        } else { ?>
        <script>$('#ctotal').html('<p class="searchr">There where not searches between the selected dates.</p>');</script><?
        } ?>
      <p>&nbsp;</p>
      <? } ?>
                  </table>
        <script>get_map_mult();</script>
                  </div>
                  </div>
      <!-- Column Content END -->
    </div>
    <!-- inner-content END -->
  </div>
  <div id="content-bottom"></div>
  <!-- content END -->
<?php get_footer(); ?>
