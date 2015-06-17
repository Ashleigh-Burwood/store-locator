<?php 

//handle the population of customer details in page form
if(isset($_GET['uni_id'])) {
	$uni_id = $_GET['uni_id'];
	
	include_once('credentials.php');
	include_once('connection.php');
	
	$sql = "select uni_id, PostalName, AddressLine1, AddressLine2, AddressLine3, AddressLine4 City, County, PostCode, lat, lng from AnB_Test.DeliveryAddress where uni_id = " . $uni_id;
    $aResult = mysql_query($sql, $link);
	
	$aRow = mysql_fetch_array($aResult, MYSQL_ASSOC);
	
	$encode = json_encode($aRow);
	
	mysql_close($link);
	
	echo $encode;
}

?>