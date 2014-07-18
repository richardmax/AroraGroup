// JavaScript Document
jQuery(document).ready(function($){
	
	alert('here!!!!!');
	/* THIS IS FOR BOOKING ---------------------------------------------------------------------------------------------------------- */
	
	
	$( "#datepicker" ).datepicker({dateFormat: 'dd-mm-yy',minDate: 0});
			$( "#datepicker2" ).datepicker({dateFormat: 'dd-mm-yy',minDate: 1});
			$('#obmform').on('submit',function() {
				var arrival = $('#datepicker').val();
				var departure = $('#datepicker2').val();
				//var numberOfNights = $('#numberofnights').val();
				var numberOfGuests = $('#numberofguests').val();
				var day= arrival.slice(0,2);
				var month = arrival.slice(3,5);
				var year = arrival.slice(6,10); 
				var day2= departure.slice(0,2);
				var month2 = departure.slice(3,5);
				var year2 = departure.slice(6,10); 
				var link = "http://www.theharrington.com/v4/book-now/?loadOBMApplication.action?siteId=FINEXHARR3&request_locale=en&chainAction=newAvailabilitySearch&arrival="+day+"%2F"+month+"%2F"+year+"&numberOfPersons="
						+numberOfGuests+
						"&departure="+day2+"%2F"+month2+"%2F"+year2;
						window.location.href=link;
				return false;
			})
	
	
	
	/* THIS IS FOR FILTERING ---------------------------------------------------------------------------------------------------------- */

	var nothingSelectedText = 'No options selected';
	var viewArray = [];
	var showOnly = [];
	var classarray = [];

    $('.filter-dropdown').multiselect({
		
		//buttonClass: 'btn-primary btn-lg span3',
		//includeSelectAllOption: true,
		selectAllValue: 'multiselect-all',
		//includeSelectAllDivider: true,
		includeSelectAllIfMoreThan: 4,
		selectAllText: 'All Options',
		//enableFiltering: true,
		//enableCaseInsensitiveFiltering: true,
		//filterPlaceholder: 'Type Search Term here',
		maxHeight: 500,
		buttonWidth: '188px',
		nonSelectedText: nothingSelectedText,
		numberDisplayed: 3,

      	onChange: function(element, checked) {
      		
			//alert(element.val());
			
			// Find and remove item from an array
			var i = viewArray.indexOf(element.val());
			if(!checked) {
				viewArray.splice(i, 1);
			}else{
				viewArray.push(element.val());
			}

			showOnly = viewArray.join();
			//alert("showonly: " + showOnly);
			
			$('.filter-class-view').each(function(){
				// only supports 20 simultaneoes selections currently!
				if (!$(this).hasClass(viewArray[0]) && 
					!$(this).hasClass(viewArray[1]) && 
					!$(this).hasClass(viewArray[2]) && 
					!$(this).hasClass(viewArray[3]) && 
					!$(this).hasClass(viewArray[4]) &&
					!$(this).hasClass(viewArray[5]) &&
					!$(this).hasClass(viewArray[6]) &&
					!$(this).hasClass(viewArray[7]) &&
					!$(this).hasClass(viewArray[8]) &&
					!$(this).hasClass(viewArray[9]) &&
					!$(this).hasClass(viewArray[10]) &&
					!$(this).hasClass(viewArray[11]) &&
					!$(this).hasClass(viewArray[12]) &&
					!$(this).hasClass(viewArray[13]) &&
					!$(this).hasClass(viewArray[14]) &&
					!$(this).hasClass(viewArray[15]) &&
					!$(this).hasClass(viewArray[16]) &&
					!$(this).hasClass(viewArray[17]) &&
					!$(this).hasClass(viewArray[18]) &&
					!$(this).hasClass(viewArray[19]) &&
					!$(this).hasClass(viewArray[20])) {
						$(this).hide().addClass('hidden');
					} else {
						$(this).show().removeClass('hidden');
					}
				});
			
      	}
    });

	$('.filter-dropdown').bind('change', function() {

		classarray = $(this).val();
		
		//alert("classarray: " + classarray); 

		if(classarray == undefined){
			
			//alert("I AM NULL");
			$(".dropdown-toggle").html(nothingSelectedText + ' <b class="caret"></b>');
			$(".dropdown-toggle").attr('title',nothingSelectedText);
			
			$('.filter-class-view').each(function(){
				$(this).show().removeClass('hidden');
			});

		}

	});
	
	
	/* THIS IS FOR FILTERING WITH SELECT ALL - ONLY WORKS IF X1 DROPDOWN ---------------------------------------------------------------------------------------------------------- */
	
	/*
	var nothingSelectedText = 'No options selected';
	var viewArray = [];
	var showOnly = [];
	var classarray = [];

    $('.filter-dropdown').multiselect({
		
		//buttonClass: 'btn-primary btn-lg span3',
		includeSelectAllOption: true,
		selectAllValue: 'multiselect-all',
		//includeSelectAllDivider: true,
		includeSelectAllIfMoreThan: 4,
		selectAllText: 'All Options',
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: 'Type Search Term here',
		maxHeight: 500,
		buttonWidth: '188px',
		nonSelectedText: nothingSelectedText,
		numberDisplayed: 3,

      	onChange: function(element, checked) {
      		
			//alert(element.val());
			
			// Find and remove item from an array
			var i = viewArray.indexOf(element.val());
			if(!checked) {
				viewArray.splice(i, 1);
			}else{
				viewArray.push(element.val());
			}

			showOnly = viewArray.join();
			//alert("showonly: " + showOnly);
		
      	}
    });

	$('.filter-dropdown').bind('change', function() {

		classarray = $(this).val();
		
		//alert("classarray: " + classarray); 

		if(classarray == undefined){
			
			//alert("I AM NULL");
			$(".dropdown-toggle").html(nothingSelectedText + ' <b class="caret"></b>');
			$(".dropdown-toggle").attr('title',nothingSelectedText);
			
			$('.filter-class-view').each(function(){
				$(this).show().removeClass('hidden');
			});

		}
		
		$('.filter-class-view').each(function(){
			// only supports 20 simultaneoes selections currently!
			if (!$(this).hasClass(classarray[0]) && 
				!$(this).hasClass(classarray[1]) && 
				!$(this).hasClass(classarray[2]) && 
				!$(this).hasClass(classarray[3]) && 
				!$(this).hasClass(classarray[4]) &&
				!$(this).hasClass(classarray[5]) &&
				!$(this).hasClass(classarray[6]) &&
				!$(this).hasClass(classarray[7]) &&
				!$(this).hasClass(classarray[8]) &&
				!$(this).hasClass(classarray[9]) &&
				!$(this).hasClass(classarray[10]) &&
				!$(this).hasClass(classarray[11]) &&
				!$(this).hasClass(classarray[12]) &&
				!$(this).hasClass(classarray[13]) &&
				!$(this).hasClass(classarray[14]) &&
				!$(this).hasClass(classarray[15]) &&
				!$(this).hasClass(classarray[16]) &&
				!$(this).hasClass(classarray[17]) &&
				!$(this).hasClass(classarray[18]) &&
				!$(this).hasClass(classarray[19]) &&
				!$(this).hasClass(classarray[20])) {
					$(this).hide().addClass('hidden');
				} else {
					$(this).show().removeClass('hidden');
				}
			});		

		});
		
		*/

});