$(document).ready(function(){
	$(function(){//<![CDATA[
	// This example displays an address form, using the autocomplete2 feature
	// of the Google Places API to help users fill in the information.

	$("#company_address").on('focus', function () {
		geolocate2();
	});

	var placeSearch2, autocomplete2;
	var componentForm2 = {
		street_number2: 'short_name',
		route2: 'long_name',
		locality2: 'long_name',
		company_state: 'short_name',
		company_zipcode: 'short_name'
	};

	function initialize2() {
		// Create the autocomplete2 object, restricting the search
		// to geographical location types.
		autocomplete2 = new google.maps.places.Autocomplete(
		/** @type {HTMLInputElement} */ (document.getElementById('company_address')), {
			types: ['geocode']
		});
		// When the user selects an address from the dropdown,
		// populate the address fields in the form.
		google.maps.event.addListener(autocomplete2, 'place_changed', function () {
			fillInAddress2();
		});
	}

	// [START region_fillform]
	function fillInAddress2() {
		// Get the place details from the autocomplete2 object.
		var place = autocomplete2.getPlace();

		document.getElementById("latitude2").value = place.geometry.location.lat();
		document.getElementById("longitude2").value = place.geometry.location.lng();

		for (var component in componentForm2) {
			document.getElementById(component).value = '';
			document.getElementById(component).disabled = false;
		}

		// Get each component of the address from the place details
		// and fill the corresponding field on the form.
		for (var i = 0; i < place.address_components.length; i++) {
			var addressType = place.address_components[i].types[0];
			if (componentForm2[addressType]) {
				var val = place.address_components[i][componentForm2[addressType]];
				document.getElementById(addressType).value = val;
			}
		}
	}
	// [END region_fillform]

	// [START region_geolocation]
	// Bias the autocomplete2 object to the user's geographical location,
	// as supplied by the browser's 'navigator.geolocation' object.
	function geolocate2() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (position) {
				var geolocation = new google.maps.LatLng(
				position.coords.latitude2, position.coords.longitude2);

				var latitude2 = position.coords.latitude2;
				var longitude2 = position.coords.longitude2;
				document.getElementById("latitude2").value = latitude2;
				document.getElementById("longitude2").value = longitude2;

				autocomplete2.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));
			});
		}

	}

	initialize2();
	// [END region_geolocation]
	});//]]> 
});
