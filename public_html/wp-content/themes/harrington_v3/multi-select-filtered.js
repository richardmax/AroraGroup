// JavaScript Document
jQuery(document).ready(function($){
	
		//alert('here');
	
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
      		
			alert(element.val());
			
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

});