<?php

class TripObjectModel{
    public $trip_name = '';
    public $odometer_start = null;
    public $odometer_start_img = '';
    public $odometer_end = null;
    public $odometer_end_img = '';
    public $direct_img = '';
    public $refull = null;
    public $fuel = null;
    public $fuel_capacity = '';
    public $cost = '';
    public $routs = '';
    public $start_point = null;
    public $date = '';
    public $time = '';
    public $persons = '';
    public $points = '';

    public function __construct($json){
        $decodedJSON = json_decode($json);
        $this->trip_name = $decodedJSON->trip_name;
        $this->odometer_start = $decodedJSON->odometer_start;
        $this->odometer_start_img = $decodedJSON->odometer_start_img;
        $this->odometer_end = $decodedJSON->odometer_end;
        $this->odometer_end_img = $decodedJSON->odometer_end_img;
        $this->direct_img = $decodedJSON->direct_img;
        $this->refull = $decodedJSON->refull;
        $this->fuel = $decodedJSON->fuel;
        $this->fuel_capacity = $decodedJSON->fuel_capacity;
        $this->cost = $decodedJSON->cost;
        $this->routs = $decodedJSON->routs;
        $this->start_point = $decodedJSON->start_point;
        $this->date = $decodedJSON->date;
        $this->time = $decodedJSON->time;
        $this->persons = $decodedJSON->persons;
        $this->points = $decodedJSON->points;
        //dumpex($inputData);
        return $decodedJSON;
    }
}