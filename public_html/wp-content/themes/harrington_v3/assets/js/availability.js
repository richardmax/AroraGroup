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
				

					}
					else {
						var today = new Date();
						var dd = today.getDate();
						var mm = today.getMonth()+1;

						var yyyy = today.getFullYear();
						if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} var today = dd+'/'+mm+'/'+yyyy;
						var date = dd+'%2F'+mm+'%2F'+yyyy;
						var link = 'https://uk2.roomlynx.net/rezrooms2/loadOBMApplication.action?siteId=FINEXHARR1&request_locale=en&chainAction=newAvailabilitySearch&arrival='+date+'&numberOfPersons=1&numberOfNights=1';
						$('iframe').attr('src',link);
					}
					}
	})