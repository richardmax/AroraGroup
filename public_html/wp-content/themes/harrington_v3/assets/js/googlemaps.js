(function() {
	window.onload = function() {

	var options = {
		zoom:15,
		center: new google.maps.LatLng(51.4958898, -0.1832814),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	var map = new google.maps.Map(document.getElementById('map-canvas'), options);

	var places = [];
	var descriptions = [];



	$.each($('.addresses'), function () {
		descriptions.push($(this).html());
		var coordinates = $(this).next().html();
		var coordinates_array = [];
		coordinates_array = coordinates.split(",");
		places.push(new google.maps.LatLng(parseFloat(coordinates_array[0]),parseFloat(coordinates_array[1])));
	})


	var infowindow;

	for (var i = 0; i < places.length; i++) {

	var marker = new google.maps.Marker({
		position: places[i],
		map: map,
		title: 'Click to show contact info!'
	});

	(function(i, marker) {

	google.maps.event.addListener(marker, 'click', function() {
		if (!infowindow) {
			infowindow = new google.maps.InfoWindow();
		}

		infowindow.setContent(descriptions[i]);

		infowindow.open(map, marker);
	});
	})(i, marker);
	}
	};
	})();
