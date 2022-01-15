<?php
# author: Gichuhi Haron
# Email: hgichuhi@hykartech.com
# Company: HykarTech (www.hykartech.com)
# Date: Saturday, January 15, 2022
# Location: Kampala, Uganda
# Description: A simple api that returns json data after computing the distance
# between a user current location and nearby health facilities within a distance of 10km radius

#db connection - you can also PDO 
$conn = new mysqli('localhost','root','','markers');

#check for connection
if(!$conn){
    echo 'Failed to connect'.mysqli_error($conn);
}

# later we shall get this from either a mobile app or html web form
# http://localhost/vaccinelocator/dist_calc_api.php?lng=43.6980&lat=7.266
//$user_latitude = '7.266';
//$user_longitude = '43.6980';
$user_longitude = $_GET['lng'];
$user_latitude = $_GET['lat'];

$distance = 10;

$query = "SELECT ROUND(6371 * acos (cos ( radians($user_latitude) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($user_longitude) ) + sin ( radians($user_latitude) ) * sin( radians( latitude ) ))) AS distance,marker.* FROM marker HAVING distance >= $distance";

$result = mysqli_query($conn, $query);

# always check for null - then terminate the process or debug
if(!$result){
    echo 'no result';
}

$hospital = array();

while($row = mysqli_fetch_assoc($result)){
    $hospital[] = $row;
}

// convert into json_data
if($hospital){
    print("{'msg':'has results', 'info': '". json_encode($hospital) ."'}");
} else {
    print("{'msg': 'no results'}");
}

mysqli_free_result($result);
mysqli_close($conn);

?>
