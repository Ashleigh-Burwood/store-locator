<title>geocoder class</title>
<?php
class geocoder{
    static private $url = "https://maps.google.com/maps/api/geocode/json?sensor=false&key=AIzaSyChWoi575dJMgBKy-QlwRpkaSsTnxDtLkg&address=";

    static public function getLocationLat($address){
        $url = self::$url.urlencode($address);
        
        $resp_json = self::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);

        if($resp['status']='OK'){
			$resp_lat = $resp['results'][0]['geometry']['location']['lat'];
            return $resp_lat;
        }else{
            return false;
        }
    }
	
	static public function getLocationLng($address){
        $url = self::$url.urlencode($address);
        
        $resp_json = self::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);

        if($resp['status']='OK'){
			$resp_lng = $resp['results'][0]['geometry']['location']['lng'];
            return $resp_lng;
        }else{
            return false;
        }
    }
	
	static public function getLocation($address){
        $url = self::$url.urlencode($address);
        
        $resp_json = self::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);

        if($resp['status']='OK'){
            return $resp;
        }else{
            return false;
        }
    }


    static private function curl_file_get_contents($URL){
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) return $contents;
            else return FALSE;
    }
}
?>