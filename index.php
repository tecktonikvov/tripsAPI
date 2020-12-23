<?php
require_once('controllers/managment/api/CoreApi.php');
require_once('controllers/managment/api/TripObjectModel.php');
//localhost/managment/api?user=koz&action=getTrips&token=ca1cecb73dc49d9cf54565c258b4a5bd
$action = $_GET['action'];
$id = $_GET['id'];
$apiObj = new CoreApi($newTripObject = new TripObjectModel($json));

switch ($action) {
    case "getTrips":
        $apiObj->getTrips();
        break;

    case "addTrip":
        $apiObj->addTrip();
        break;

    case "deleteTrips":
        $apiObj->deleteTrip($id);
        break;

    case "updateTrips":
        $apiObj->updateTrip();
        break;

    case "auth":
        $apiObj->authorization();
        break;
}
