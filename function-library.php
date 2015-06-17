<?php 

//function library

//geo code
function getGeocode($thisAddress) {
	$address = urlencode($thisAddress);
	$loc = geocoder::getLocation($address);

	return $loc;
}

//handle the population of customer details in page form
if(isset($_GET['uni_id'])) {
	$uni_id = $_GET['uni_id'];
	
	include_once('credentials.php');
	include_once('connection.php');
	
	$sql = "select uni_id, PostalName, AddressLine1, AddressLine2, AddressLine3, AddressLine4 City, County, PostCode, lat, lng from AnB_Live.DeliveryAddress where uni_id = " . $uni_id;
    $aResult = mysql_query($sql, $link);
	
	$aRow = mysql_fetch_array($aResult, MYSQL_ASSOC);
	
	$encode = json_encode($aRow);
	
	mysql_close($link);
	
	echo $encode;
}

//handle to update of customer details
if(isset($_POST['uni_id']) && isset($_POST['lat']) && isset($_POST['lng'])) {
	$uni_id = $_POST['uni_id'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	
	include_once('credentials.php');
	include_once('connection.php');
	
	$sql = "update AnB_Live.DeliveryAddress set lat = " . $lat . ", lng = " . $lng . "where uni_id = " . $uni_id;
    $aResult = mysql_query($sql, $link);
	
	if ($aResult) {
		echo "Update Successful!";
	} else {
		echo "Update Failed!";
	}
	mysql_close($link);
}

?>