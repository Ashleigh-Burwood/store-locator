<?php 

require("credentials.php");
require("connection.php");

if(isset($_GET['requiredLat']) && isset($_GET['requiredLng']) ) {
	$searchedLat = $_GET['requiredLat'];
	$searchedLng = $_GET['requiredLng'];
	
	$query = sprintf("SELECT CustomerAccountNumber, CustomerAccountName, lat, lng, ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM AnB_Live.DeliveryAddress HAVING distance < 100 ORDER BY distance LIMIT 0 , 20",
	mysql_real_escape_string($searchedLat),
	mysql_real_escape_string($searchedLng),
	mysql_real_escape_string($searchedLat));
	$result = mysql_query($query);
	
	if (!$result) {
	  die("Invalid query: " . mysql_error());
	}
	
	// Put them in array
	for($i = 0; $array[$i] = mysql_fetch_assoc($result); $i++) ;
		
	// Delete last empty one
	array_pop($array);
	
	$encode = json_encode($array);
	
	//$aRow = mysql_fetch_array($result, MYSQL_ASSOC);
	
	//$encode = json_encode($aRow);
	
	
	mysql_close($link);
	
	echo $encode;
}

?>