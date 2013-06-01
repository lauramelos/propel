$(document).ready(function() {

	// preload css images
	$.preloadCssImages();

	// main nav
	$("#main-nav li").hover(
	  function () {
	  	$(this).children('a:first').addClass('over');
	    $(this).children('.subnav').show(0);
	    $(this).children('a.item').addClass('over');
	  }, 
	  function () {
			$(this).children('.subnav').hide(0);
			$(this).children('a.item').removeClass('over');
	  }
	);

	// home page carousel
	if ($("#home-lrg-carousel").length > 0) {
		$('#home-lrg-carousel').jcarousel({
			auto: 7,
	    scroll: 1,
	    animation: 500,
			wrap: 'circular'
		});

	}


	$("a[rel='colorbox']").colorbox({ 
		opacity: 0.5,
		scrolling: false,
		transition:'none',
		inline:true,
		arrowKey:false
	});

	$("a.cbox-close").bind('click', function(e) {
		e.preventDefault();
		$.colorbox.close();
	});
	
	$("a.cbox-close-orange").bind('click', function(e) {
		e.preventDefault();
		$.colorbox.close();
	});
	
	$("a.cbox-close-link").bind('click', function(e) {
		e.preventDefault();
		$.colorbox.close();
	});

	// video thumbnail overlay rollover
	$("a.thumb-video").hover(
	  function () {
	  	$(this).children('.overlay').addClass('overlay-on');
	  	$(this).children('.overlay').removeClass('overlay-off');
	  }, 
	  function () {
	  	$(this).children('.overlay').addClass('overlay-off');
	  	$(this).children('.overlay').removeClass('overlay-on');
	  }
	); 

	// image thumbnail overlay rollover
	$("a.thumb-img").hover(
	  function () {
	  	$(this).children('.overlay').addClass('overlay-on');
	  	$(this).children('.overlay').removeClass('overlay-off');
	  }, 
	  function () {
	  	$(this).children('.overlay').addClass('overlay-off');
	  	$(this).children('.overlay').removeClass('overlay-on');
	  }
	); 	

	// contact map
	if ($("#map-canvas").length > 0) {
		initMap();
	}

	// handle table borders
	$(".table-style td:first-child").addClass('first-child');
	$(".table-style td:last-child").addClass('last-child');
	$(".table-style tr:last-child td").addClass('bottom');

});


// Google Map
function initMap() {
	var myOptions = {
	  zoom: 14,
	  center: new google.maps.LatLng(37.433102,-122.103712),
	  mapTypeId: google.maps.MapTypeId.ROADMAP,
	  streetViewControl: false,
		mapTypeControl: false
		//zoomControl: false
	}
	var map = new google.maps.Map(document.getElementById("map-canvas"),
	                              myOptions);

	var image = '/_img/content/map-marker.png';
	var myLatLng = new google.maps.LatLng(37.432102,-122.103712);
	var marker = new google.maps.Marker({
	    position: myLatLng,
	    map: map,
	    icon: image
	});
  	
  	google.maps.event.addListener(marker, 'click', function() {
  	window.open('http://g.co/maps/hnaa');
  	});
  	
  	var myOptions2 = {
  	  zoom: 13,
	  center: new google.maps.LatLng(37.47898,-122.141038),
	  mapTypeId: google.maps.MapTypeId.ROADMAP,
	  streetViewControl: false,
		mapTypeControl: false
		//zoomControl: false
	}
	var map2 = new google.maps.Map(document.getElementById("map-canvas2"),
	                              myOptions2);

	var myLatLng2 = new google.maps.LatLng(37.47798,-122.141038);
	var marker2 = new google.maps.Marker({
	    position: myLatLng2,
	    map: map2,
	    icon: image
	});
  	
	google.maps.event.addListener(marker2, 'click', function() {
  	window.open('http://g.co/maps/7u72c');
	
	});
}
