<?php 
//included php files
require("credentials.php");
require("geocoder.php");
require("connection.php");
require("function-library.php");

//compose sql query string
$sql = "select uni_id, PostalName, AddressLine1, AddressLine2, AddressLine3, AddressLine4, City, County, PostCode, lat, lng from AnB_Live.DeliveryAddress";

//query the database
$result = mysql_query($sql, $link);

//first create an object of the geocoder class
$geocoder = new geocoder();

//loop through results of the sql query results
while ( $row = mysql_fetch_array($result) )
{
	//compose address string for each account for geocoding
	$addressString = "";
	if ($row['AddressLine1'] != "") {
		$addressString .= $row['AddressLine1'] . ", ";
	}
	if ($row['AddressLine2'] != "") {
		$addressString .= $row['AddressLine2'] . ", ";
	}
	if ($row['AddressLine3'] != "") {
		$addressString .= $row['AddressLine3'] . ", ";
	}
	if ($row['AddressLine4'] != "") {
		$addressString .= $row['AddressLine4'] . ", ";
	}
	if ($row['City'] != "") {
		$addressString .= $row['City'] . ", ";
	}
	if ($row['County'] != "") {
		$addressString .= $row['County'] . ", ";
	}
	if ($row['PostCode'] != "") {
		$addressString .= $row['PostCode'];
	}
	
	//now we have the formatted address, send for geocoding
	//use the previously created geocoder object to do so
	
	//but we only want to geocode if it's needed so if statement ahoy
	
	//if the lat entry for this record is 0 attempt to geocode
	if ($row['lat'] == 0) {
		//true so
		//call the getLocationLat of the geocoder class and store result in variable
		$getGeocodeLat = $geocoder->getLocationLat($addressString);
		$hasAttemptedLat = true;
	} else {
		//false so
		//set the variable holding the geocode result to false and record that the attempt was not made (false)
		$getGeocodeLat = false;
		$hasAttemptedLat = false;		
	}
	
	//if the lng entry for this record is 0 attempt to geocode
	if ($row['lng'] == 0) {
		//true so
		//call the getLocationLat of the geocoder class and store result in variable
		$getGeocodeLng = $geocoder->getLocationLng($addressString);
		$hasAttemptedLng = true;
	} else {
		//false so
		//set the variable holding the geocode result to false and record that the attempt was not made (false)
		$getGeocodeLng = false;
		$hasAttemptedLng = false;
	}
	
	//test if attempts were made
	if ($hasAttemptedLat && $hasAttemptedLng) {
		//both attempts have been made, so comment on success or not
		
		//test if both geocode requests were successfull
		if ( $getGeocodeLat && $getGeocodeLng ) {
			//true - so update the database
			
			//compose the update query
			$updateSql = "update AnB_Live.DeliveryAddress set lat = " . $getGeocodeLat . ", lng = " . $getGeocodeLng . "where uni_id = " . $row['uni_id'];
			//send query to database
			$aResult = mysql_query($updateSql, $link);
			
			//check if update was successful
			if ($aResult) {
				//true - so print out that it was.
				echo "Geocoding for " . $row['PostalName'] . " was complete and the database was updated successfully." . "<br />";
			} else {
				//false - so print out that it failed (but that the geocoding was OK.
				echo "Although the Geocoding for " . $row['PostalName'] . " was successfull, something went wrong and the database update failed." . "<br />";
			}
					
		} else {
			//false - so print the name of the address that failed
			echo "Geocoding failed, please update manually - PostalName: " . $row['PostalName'] . " Post Code: " . $row['PostCode']. "<br />";		
		}
	} else {
		//either one or both geocoding attempts have not been made, so comment on that
		echo "Geocoding was not attempted. Possible that record for " . $row['PostalName'] . " is up to date.<br />";
		echo "Further details:<br />Lat Attempt: " . $hasAttemptedLat . "<br />Lng Attempt: " . $hasAttemptedLng . "<br />";	
	}
	sleep(1);
} //end of while loop
mysql_close($link);

?>