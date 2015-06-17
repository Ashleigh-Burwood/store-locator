<?php
echo "Let's attempt connecting to the Sage Server Database";
echo "<br />";

require("credentials.php");
require("geocoder.php");

$link = mysql_connect($server, $user, $password);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';
echo '<br />';

$selectDb = mysql_select_db($database);
if (!$selectDb) {
	die('database selection problem: ' . mysql_error());
}

/*$sql = 'select PostalName, AddressLine1, AddressLine2, AddressLine3, City, County, PostCode from AnB_Test.CustDeliveryAddress';
$result = mysql_query($sql, $link);
echo '<table>';
while ( $row = mysql_fetch_array($result) )
{
	$addressString = $row['AddressLine1'];
	$addressString .= $row['AddressLine2'];
	$addressString .= $row['AddressLine3'];
	$addressString .= $row['City'];
	$addressString .= $row['County'];
	$addressString .= $row['PostCode'];
	
	$address = urlencode($addressString);
	echo "<tr><td>";
	echo $row['PostalName'];
	echo "</td><td>";
	echo $row['AddressLine1'];
	echo "</td><td>";
	echo $row['AddressLine2'];
	echo "</td><td>";
	echo $row['AddressLine3'];
	echo "</td><td>";
	echo $row['City'];
	echo "</td><td>";
	echo $row['County'];
	echo "</td><td>";
	echo $row['PostCode'];
	echo "</td><td>";
	
	$locLat = geocoder::getLocationLat($address);
	echo $locLat;
	echo "</td><td>";
	
	$locLng = geocoder::getLocationLng($address);
	echo $locLng;
	echo "</td></tr>";
	
	sleep(2);
}
echo "</table>"; */

$address = urlencode("328 - 334 Molesey Road, Walton - on - Thames, Surrey, KT12 3PD");
$loc = geocoder::getLocation($address);

print_r($loc);


mysql_close($link);
?>