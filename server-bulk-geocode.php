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
	
	//but we only want to geocode if it's needed so if statements ahoy
	
	//changing the way the API call is made so that only 1 call is made per record
	if ($row['lat'] == 0 || $row['lng'] == 0) {
		//if either lat or lng of record are equal to 0 then make a call to API,
		//store result array in variable
		//and record that call has been attempted
		$getGeocodeLatLng = $geocoder->getLatLng($addressString);
		$hasAttemptedGeocode = true;
	} else {
		//the record was already geocoded
		//set the result array to false
		//and record that the call to the API was not made
		$getGeocodeLatLng = false;
		$hasAttemptedGeocode = false;
	}
	
	//check if geocoding attempt was made
	if ($hasAttemptedGeocode) {
		//attempt was made so,
		//check if the attempt was successfull
		if ($getGeocodeLatLng) {
			//attempt was successful so,
			//so update the database
			
			//compose the update query
			$updateSql = "update AnB_Live.DeliveryAddress set lat = " . $getGeocodeLatLng['lat'] . ", lng = " . $getGeocodeLatLng['lng'] . "where uni_id = " . $row['uni_id'];
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
			//attempt was made but was not successful so,
			echo "<strong>Geocoding failed</strong>, please update manually - PostalName: <strong>" . $row['PostalName'] . "</strong> Post Code: <strong>" . $row['PostCode']. "</strong><br />";			
		}
	//as an attempt to geocode was made, pause the execution of the code so that our request per second limit is not reached.
	sleep(1);	
	} else {
		//the attempt was not made so,
		//comment on that
		echo "Geocoding attempt was not made for account: <strong>" . $row['PostalName'] . "</strong><br />";
		echo "Record possibly up-to-date:<br />Lat: " . $row['lat'] . "<br />Lng: " . $row['lng'] . "<br />";
	}	
	
} //end of while loop
mysql_close($link);

?>