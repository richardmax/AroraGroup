//archive				 
				 jQuery(document).ready(function ($) {
				  var url = window.location.href;
				  url = url.match(/#.+/);
				  url = url[0];
				  var figcaptions= $('#gal>li>a').children('figcaption');
				  var l = 0;
				  $.each(figcaptions, function(){
				  if (l == 0) {
					$(this).parent().addClass('gotmap');
				  }
				  element = $(this).html();
				  console.log('elem:'+element);
					fixed = element.replace(/ /g,'-');
					console.log(fixed);
					if (url.toLowerCase().search(fixed.toLowerCase()) > -1) { 
						$(this).parent().addClass('autofill');
						$(this).parents('li').addClass('active');
					} 
					else {
						console.log('url:'+url.toLowerCase()+' fixed:'+fixed.toLowerCase());
					}
					l++;
				  })
				  var autocontent= $('.autofill').next().find(".imagesToClone").first().clone().prependTo('.contentarea');
					var description = $('.autofill').next().find(".description").clone().appendTo('.contentarea #desc');
					var specifications = $('.autofill').next().find(".specifications").clone().appendTo('.contentarea #spec');
					var map = $('.autofill').next().find(".map").clone().appendTo('.contentarea #map');

					
					var activeimage = $('ul #gal').find('li .active');
					if ( ! activeimage.find('a.thumbnail').hasClass('gotmap')) {
					
					if ($('#mapbutton').length > 0) {
						if ( ! $('#gal').find('li').first().hasClass('active') )
							$('#mapbutton').remove();
							console.log('d');
							$('#myTab a:last').tab('show');
						}
					
					}
					else {
						console.log('e');
					}

					$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

								if( $(e.relatedTarget).html() == 'Description') {
									tab_switch = 'Description';
									console.log('tab_switchdesc:'+tab_switch);
								}
								if( $(e.relatedTarget).html() == 'Specifications')  {
									tab_switch = 'Specification';
									console.log('tab_switchspec:'+tab_switch);
								}
								if( $(e.relatedTarget).html() == 'Map')  {
									tab_switch = 'Map';
									console.log('tab_switchmap:'+tab_switch);
								}
								if( typeof($(e.relatedTarget).html()) == 'undefined')  {
									tab_switch = 'Specification';
									console.log('tab_switchundef:'+tab_switch+':relatedtargethtml:'+$(e.relatedTarget).html());
								}
								
							
								//switch_tabs();
							});
				  $('a.thumbnail').not('.noselect').on("click",function(){
				  
				  $('a.thumbnail').parent().removeClass("active");
				  $(this).parent().addClass("active");
				  $('.contentarea').children().not(".noRemove, .noRemove *").remove();
				  $('.contentarea #desc').children().remove();
				  $('.contentarea #spec').children().remove();
				  $('.contentarea #map').children().remove();
					var mycontent = $(this).next().find(".imagesToClone").first().clone().prependTo('.contentarea');
					var description = $(this).next().find(".description").clone().appendTo('.contentarea #desc');
					var specifications = $(this).next().find(".specifications").clone().appendTo('.contentarea #spec');
					var map = $(this).next().find(".map").clone().appendTo('.contentarea #map');
					
					if ( $(this).hasClass('gotmap') ) {
						if ( $('#mapbutton').length > 0) {
						console.log('a');
						
						}
						else {
							$('#myTab').append('<li id=\"mapbutton"><a href="#map" data-toggle="tab">Map</a></li>');
							console.log('b');
						}
					} else {
						if ($('#mapbutton').length > 0) {
							$('#mapbutton').remove();
							console.log('c');
							
							$('#myTab a:first').trigger('show.bs.tab');
							
							if (tab_switch == 'Undefined' ) {
								$('#myTab a:last').tab('show');
							}
							if (tab_switch == 'Map' ) {
								$('#myTab a:last').tab('show');
							}
							if (tab_switch == 'Specification' ) {
								$('#myTab a:last').tab('show');
							}
							if (tab_switch == 'Description' ) {
								$('#myTab a:first').tab('show');
								console.log('desc');
							}
							
							console.log('end');

						}
					}
					easy_fancybox_handler();
				  })
					$('#myTab a').eq(0).tab('show');
					$('.avail').parent().parent().addClass('available');
					$('.imgs a').addClass('thumbnail noselect ngg-fancybox fancybox');
				  });	
				  //archive
				  
				  //properties
				   	jQuery(document).ready(function ($) {
	var random;
	var myaccordion = $('.accordion').children().first();
	$.each(myaccordion, function(){
		random = Math.floor((Math.random()*1000)+1);
		$(this).on('click', function (e) {
			e.preventDefault();
		})
		$(this).attr('data-toggle','collapse');
		$(this).attr('href','#acc'+random);
		$(this).next().attr('id','acc'+random);
		$(this).next().addClass('collapse');
	});
	$('.collapse').collapse();
	
    $('.multiselect').multiselect({
      includeSelectAllOption: true,
	  numberDisplayed: 1
    });
	$('.prop-filter').css('display','block');
	var props = $('.props');
	var buttons = $('button');
	var beds = [];
	var stay = [];
	$('.multiselect-container input[type=checkbox]').on('click', function () {
	console.log('clicked');
		/*$('.building-filter').children().remove();
		$('.multiselect').clone().appendTo('.building-filter');*/
		var s_beds, s_stay;
		var i = 0;
		var j = 0;
		var k = 0;
		$.each(buttons, function () {
		console.log('********************************************');
			if (i == 0) {
				s_beds = $(this).attr("title");
				console.log('s_beds:'+s_beds);
				beds = s_beds.split(',');
				for (j=0;j<beds.length;j++) {
					beds[j] = beds[j].trim();
				}
			} else {
				s_stay = $(this).attr("title");
				console.log('s_stay:'+s_stay);
				stay = s_stay.split(',');
				for (k=0;k<stay.length;k++) {
					stay[k] = stay[k].trim();
		
				}
				console.log('stay:'+stay);
			}
			
			i++;
			
		})
		$.each(props, function () {
		console.log('------------------------------------------------')
			if ( (beds.length === 1 && beds[0] === 'None selected') || $.inArray($(this).data("beds"),beds) >-1 )  {
					console.log('property_beds:'+$(this).data("beds")+' /selected_beds:'+beds);
					console.log('property_stay:'+$(this).data("stay")+' /selected_stays:'+stay);
				if ( (stay.length === 1 && stay[0] === 'None selected')|| $.inArray($(this).data("stay"),stay)> -1 ) { 
					if ( ! $(this).is(':visible')) {
					console.log('shown!');
						$(this).show();
						} 
					}
					else {
						$(this).hide();
					}
				} else {
				if ($(this).is(':visible')) {
				console.log($(this).data("beds"));
				console.log(beds);
				console.log($(this).data("stay"));
				console.log('hidden!');
					$(this).hide();
					}
			}
		});
	}) 
	/*var container = document.querySelector('#container');
		var msnry = new Masonry( container, {
	  // options
	  columnWidth: 200,
	  itemSelector: '.item'
	});*/
  });

				  //properties
				  
				  //header
				  jQuery(document).ready( function($) {
			$( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd',minDate: 0});
			$( "#datepicker2" ).datepicker({dateFormat: 'yy-mm-dd',minDate: 1});
			$('form').on('submit',function() {
				var arrival = $('#datepicker').val();
				var departure = $('#datepicker2').val();
				//var numberOfNights = $('#numberofnights').val();
				var numberOfGuests = $('#numberofguests').val();
				var day= arrival.slice(8,10);
				var month = arrival.slice(5,7);
				var year = arrival.slice(0,4); 
				var day2= departure.slice(8,10);
				var month2 = departure.slice(5,7);
				var year2 = departure.slice(0,4); 
				var link = "http://www.theharrington.com/v4/availability/?loadOBMApplication.action?siteId=FINEXHARR3&request_locale=en&chainAction=newAvailabilitySearch&arrival="+day+"%2F"+month+"%2F"+year+"&numberOfPersons="
						+numberOfGuests+
						"&departure="+day2+"%2F"+month2+"%2F"+year2;
						window.location.href=link;
				return false;
			})
		});
				  
				  //header
				  
				  //availablity
				  	jQuery(document).ready(function ($){
	
	$(window).resize(function () {
			iframeresize();
		});
		(function iframeresize() {
			$('iframe').height(window.innerHeight-220);
		})();
		
	function getQueryStrings() { 
					  var assoc  = [];
					  var decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
					  var queryString = location.search.substring(1); 
					  var keyValues = queryString.split('&'); 

					  for(var i in keyValues) { 
						var key = keyValues[i].split('=');
						if (key.length > 1) {
						  assoc[decode(key[0])] = decode(key[1]);
						}
					  } 
					  return assoc; 
					} 
					var qs = getQueryStrings();
					if (qs["chainAction"] === 'loadSearchBooking' ) {
						//$('.contact-us').parent().parent().parent().attr("class","obm");					
						//$('#reserve').height(window.innerHeight-220);
						//$('.contact-us').parent().hide();		
								var link = "https://uk2.roomlynx.net/rezrooms2/loadOBMApplication.action?siteId=FINEXHARR1&chainAction=loadSearchBooking";
							$('iframe').src  = link;
							console.log('load');

					}
					else 
					{
					if (typeof(qs["arrival"]) !== 'undefined' ) {		
						//$('.contact-us').parent().parent().parent().attr("class","obm");					
						//$('#reserve').height(window.innerHeight-220);
						//$('.contact-us').parent().hide();						
						var link = "https://uk2.roomlynx.net/rezrooms2/loadOBMApplication.action?siteId=FINEXHARR1&request_locale=en&chainAction=newAvailabilitySearch&arrival="
						+qs["arrival"];
						
						if (typeof(qs["departure"]) !== 'undefined') {
							link= link + "&departure="+qs["departure"];
						}
						if (typeof(qs["offerCode"]) !== 'undefined') {
							link= link + "&offerCode="+qs["offerCode"];
						}
						if (typeof(qs["numberOfChildren"]) !== 'undefined') {
							link= link + "&numberOfChildren="+qs["numberOfChildren"];
						}
						if (typeof(qs["numberOfInfants"]) !== 'undefined') {
							link= link + "&numberOfInfants="+qs["numberOfInfants"];
						}
						if (typeof(qs["numberOfPersons"]) !== 'undefined') {
							link= link + "&numberOfPersons="+qs["numberOfPersons"];
						}
						if (typeof(qs["numberOfNights"])!== 'undefined') {
							link= link + "&numberOfNights="+qs["numberOfNights"];
						}
						$('iframe').attr('src',link);
						console.log(link);
						console.log('loaded');

					}
					}
	})
				  //availability