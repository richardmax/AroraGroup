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
		var s_beds, s_stay;
		var i = 0;
		var j = 0;
		var k = 0;
		$.each(buttons, function () {
			if (i == 0) {
				s_beds = $(this).attr("title");
				beds = s_beds.split(',');
				for (j=0;j<beds.length;j++) {
					beds[j] = beds[j].trim();
				}
			} else {
				s_stay = $(this).attr("title");
				stay = s_stay.split(',');
				for (k=0;k<stay.length;k++) {
					stay[k] = stay[k].trim();
		
				}
			}
			
			i++;
			
		})
		$.each(props, function () {
			if ( (beds.length === 1 && beds[0] === 'None selected') || $.inArray($(this).data("beds"),beds) >-1 )  {
				if ( (stay.length === 1 && stay[0] === 'None selected')|| $.inArray($(this).data("stay"),stay)> -1 ) { 
					if ( ! $(this).is(':visible')) {
						$(this).show();
						} 
					}
					else {
						$(this).hide();
					}
				} else {
				if ($(this).is(':visible')) {
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
