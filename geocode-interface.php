<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Geocoder Interface</title>
<link rel="stylesheet" type="text/css" href="css/interface.css" />
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
</head>

<body>

<div id="header">
	<img src="images/AB_Logo_2011_800.png" width="412" height="100" />
</div>

<div id="main">
	<?php 
		require("credentials.php");
		require("geocoder.php");
		require("connection.php");
		require("function-library.php");	
	?>
	<h1>Geocoder</h1>
	<div class="form-container">
        <form name="geocoderForm" action="#" method="post">
            <select name="addressList" id="addressList" class="form-field">
            	<option selected="selected">Please Select a Store</option>
                <?php 
                
                    //$sql = 'select PostalName, AddressLine1, AddressLine2, AddressLine3, City, County, PostCode from AnB_Test.CustDeliveryAddress';
					$sql = "select uni_id, PostalName from AnB_Live.DeliveryAddress";
                    $result = mysql_query($sql, $link);
                    
                    while ( $row = mysql_fetch_array($result) )
                    {
                        /*//$addressString = $row['AddressLine1'] . ", " . $row['AddressLine2'] . ", " . $row['AddressLine3'] . ", " . $row['City'] . ", " . $row['County'] . ", " . $row['PostCode'];
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
						if ($row['City'] != "") {
							$addressString .= $row['City'] . ", ";
						}
						if ($row['County'] != "") {
							$addressString .= $row['County'] . ", ";
						}
						if ($row['PostCode'] != "") {
							$addressString .= $row['PostCode'];
						}*/
                        echo "<option value='" . $row['uni_id'] . "'>" . $row['PostalName'] . "</option>";
                    }
					mysql_close($link);			
                ?>
            </select>
               
        </form>
        <div id="returnedGeocodes">
        	<form name="information" action="#" method="post">
            	<input name="id" id="id" class="form-field" hidden="true" value="Unique ID" />
            	<input name="postalName" id="postalName" class="form-field" value="Postal Name" />
                <input name="addressLine1" id="addressLine1" class="form-field" value="Address Line 1" />
                <input name="addressLine2" id="addressLine2" class="form-field" value="Address Line 2" />
                <input name="addressLine3" id="addressLine3" class="form-field" value="Address Line 3" />
                <input name="addressLine4" id="addressLine4" class="form-field" value="Address Line 4" />
                <input name="city" id="city" class="form-field" value="City" />
                <input name="county" id="county" class="form-field" value="County" />
                <input name="postCode" id="postCode" class="form-field" value="Post Code" />
                <div id="geocodeMsg" class=""></div>
                <input name="lattitude" id="lattitude" class="form-field" value="Lattitude" />
                <input name="longitude" id="longitude" class="form-field" value="Longitude" />
            </form>
        </div>
        
        <div class="submit-container">
            <input id="submitBtn" type="submit" value="Attempt Geocoding" class="submit-button" />
            <input id="updateBtn" type="submit" value="Update Database" class="update-button" />
        </div>
    </div>
    
    
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_-wCX5DPMdqILkU-evq5dRn5J8vxE6mo"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="js/geocode.js"></script>
</body>
</html>