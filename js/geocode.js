// JavaScript Document
$(document).ready(function() {
	positionForm();
	$('.form-container').draggable();
	
	var geocoder;
	function initialize() {
		geocoder = new google.maps.Geocoder();
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	
	function codeAddress() {
		//var selectedOption = $('#addressList').find(':selected').val();		
		//$('#returnedGeocodes').html(selectedOption);
		
		//var address = $('#addressList').find(':selected').val();
		var address = "";
		if ( $('input#addressLine1').val() != "" && $('input#addressLine1').val() != "Address Line 1" ) {
			address += $('input#addressLine1').val();
			address += ", ";
		}
		if ( $('input#addressLine2').val() != "" && $('input#addressLine2').val() != "Address Line 2" ) {
			address += $('input#addressLine2').val();
			address += ", ";
		}
		if ( $('input#addressLine3').val() != "" && $('input#addressLine3').val() != "Address Line 3" ) {
			address += $('input#addressLine3').val();
			address += ", ";
		}
		if ( $('input#addressLine4').val() != "" && $('input#addressLine4').val() != "Address Line 4" ) {
			address += $('input#addressLine4').val();
			address += ", ";
		}
		if ( $('input#city').val() != "" && $('input#city').val() != "City" ) {
			address += $('input#city').val();
			address += ", ";
		}
		if ( $('input#county').val() != "" && $('input#county').val() != "County" ) {
			address += $('input#county').val();
			address += ", ";
		}
		if ( $('input#postCode').val() != "" && $('input#postCode').val() != "Post Code" ) {
			address += $('input#postCode').val();
		}
		
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				//var myResults = results[0].geometry.location.lat();
				//alert(myResults);
				//var parseResults = JSON.parse(results);
				//alert(parseResults);
				var latResults = results[0].geometry.location.lat();
				//var latResults = parseResults.lat;
				var lngResults = results[0].geometry.location.lng();
				//var lngResults = parseResults.lng;
				$('#geocodeMsg').html('<p>Geocoding Successful...</p>').addClass("success");
				$('input#lattitude').val(latResults);
				$('input#longitude').val(lngResults);
			} else {
				$('#geocodeMsg').html('<p>Geocode was not successful for the following reason: ' + status + '</p>').addClass("failure");
			}
		});
	}
	
	$('#submitBtn').on('click', function(e) {
		e.preventDefault();
		$('#geocodeMsg').html("");
		if ($('#geocodeMsg').hasClass("success") ) {
			$('#geocodeMsg').removeClass("success");
		}
		if ($('#geocodeMsg').hasClass("failure") ) {
			$('#geocodeMsg').removeClass("failure");
		}
		/*var selectedOption = $('#addressList').find(':selected').val();		
		$('#returnedGeocodes').html(selectedOption);*/
		if ($('#addressList').find(':selected').val() == "Please Select a Store") {
			$('#geocodeMsg').html("Unable to Geocode without a selection...").addClass("failure");
		} else {
			codeAddress();
		}
		
	});
	
	$('#addressList').on('change', function() {
		if ($('#addressList').find(':selected').val() == "Please Select a Store") {
			$('input#id').val("Unique ID");
			$('input#postalName').val("Postal Name");
			$('input#addressLine1').val("Address Line 1");
			$('input#addressLine2').val("Address Line 2");
			$('input#addressLine3').val("Address Line 3");
			$('input#addressLine4').val("Address Line 4");
			$('input#city').val("City");
			$('input#county').val("County");
			$('input#postCode').val("Post Code");
			$('input#lattitude').val("Lattitude");
			$('input#longitude').val("Longitude");
		}
		$('#geocodeMsg').html("");
		//$('#returnedGeocodes').html("");
		aUniID = $('#addressList').find(':selected').val();
		
		$.ajax({ 	url: 'function-library.php',
					data: {uni_id: aUniID},
					type: 'GET',
					success: function(data) {
						//$('input#postalName').val(data);
						//alert(data);
						var parsed = JSON.parse(data);
						$('input#id').val(parsed.uni_id);
						$('input#postalName').val(parsed.PostalName);
						$('input#addressLine1').val(parsed.AddressLine1);
						$('input#addressLine2').val(parsed.AddressLine2);
						$('input#addressLine3').val(parsed.AddressLine3);
						$('input#addressLine4').val(parsed.AddressLine4);
						$('input#city').val(parsed.City);
						$('input#county').val(parsed.County);
						$('input#postCode').val(parsed.PostCode);
						$('input#lattitude').val(parsed.lat);
						$('input#longitude').val(parsed.lng);
					}
		}); //end of ajax
	});
	
	//the update button code
	$('#updateBtn').on('click', function(e) {
		e.preventDefault();
		
		var anID = $('input#id').val();
		var aLat = $('input#lattitude').val();
		var aLng = $('input#longitude').val();
		
		$.ajax({	url: 'function-library.php',
					data: {uni_id: anID, lat: aLat, lng: aLng},
					type: 'POST',
					success: function(data) {
						$('body').append("<div id='dialog'>" + data + "</div>");
						$('#dialog').dialog({
							buttons: [
							{
								text: "OK",
								icons: {
									primary: "ui-icon-check"
								},
								click: function() {
									$(this).dialog("close");
								}
							}
						]
					});
				}
		}); //end of ajax
	});
	
	
});

$(document).on('resize', positionForm());

//position form
function positionForm() {
	var viewPortWidth = screen.width;
	var viewPortHeight = screen.height;
	var formWidth = $('.form-container').width();
	var formHeight = $('.form-container').height();
	var calcHeight = (viewPortHeight - formHeight) / 2;
	var calcWidth = ((viewPortWidth - formWidth) / 2) - 20;
	
	$('.form-container').css({
		'top' : calcHeight,
		'left' : calcWidth
	});
}
