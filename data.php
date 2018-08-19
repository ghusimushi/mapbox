<?php
session_start();
require_once 'database.php';

//if (!isset($_SESSION['login']) || !$_SESSION['login']) {
//    header("Location: login.php");
//}

$query = "SELECT * FROM markers";
$result = mysqli_query($con,$query);

$data = array(
    "type" => "FeatureCollection",
    "features" => array()
);
while($row=mysqli_fetch_assoc($result)) {
    if (!empty($row['img'])) { $description = "<p><img src=\"uploads/{$row['img']}\" height='100px' width='150px' /></p>"; }
    $description .= "<strong>{$row['name']}</strong>";
    $description .= "<p>{$row['address']}</p>";
//    $description = htmlentities($description);
    $data["features"][] = array(
        "type" => "Feature",
        "properties" =>
            [
                "description" => $description,
                "icon" => $row['type'],
                "icon-size" => 1.25
            ],
        "geometry" =>
            [
                "type" => "Point",
                "coordinates" => array($row['lng'], $row['lat'])
            ]
    );
}

header('Content-type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

//$data = json_encode($data);
//echo $data;


?>