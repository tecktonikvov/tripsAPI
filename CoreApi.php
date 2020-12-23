<?php
//http://addr.com/managment/api?token=ca1cecb73dc49d9cf54565c258b4a5bd&action=getTrips&user=Koz
class CoreApi {
    public
        $token = "ca1cecb73dc49d9cf54565c258b4a5bd",
        $userToken = '',
        $userName = '',
        $pass = '',
        $newTripObject = [];

    function __construct($newTripObject){
        $this->userName = $_GET['user'];
        $this->userToken = $_GET['token'];
        $this->pass = $_GET['pass'];
        $this->newTripObject = $newTripObject;
    }

    public function addTrip(){

        if ($this->authCheck() === true) {
            $trip = ORM::for_table('trips')->create();
            $trip->set('trip_name', $this->newTripObject->trip_name);
            $trip->set('odometer_start', $this->newTripObject->odometer_start);
            $trip->set('odometer_start_img', $this->newTripObject->odometer_start_img);
            $trip->set('odometer_end', $this->newTripObject->odometer_end);
            $trip->set('odometer_end_img', $this->newTripObject->odometer_end_img);
            $trip->set('start_point', $this->newTripObject->start_point);
            $trip->set('direct_img', $this->newTripObject->direct_img);
            $trip->set('points', $this->newTripObject->points);
            $trip->set('persons', $this->newTripObject->persons);
            $trip->set('date', $this->newTripObject->date);
            $trip->set('refull',$this->newTripObject->refull);
            $trip->set('routs',$this->newTripObject->routs);
            $trip->set('fuel', $this->newTripObject->fuel);
            $trip->set('fuel_capacity', $this->newTripObject->fuel_capacity);
            $trip->set('cost', $this->newTripObject->cost);
            $trip->set('time', $this->newTripObject->time);
            $trip->save();
            echo json_encode(['Responce' => 'Success', 'id' => $trip->id()]);
        } else {
            echo $this->authCheck();
        }

    }
    public function getTrips(){

        if ($this->authCheck() === true) {
            $trips = ORM::forTable('trips')->find_array();

            foreach ($trips as $k => $v) {
                $trips[$k]['persons'] = json_decode($trips[$k]['persons']);
                $trips[$k]['points'] = json_decode($trips[$k]['points']);
                $trips[$k] += ['odometer_start_img_url' => "http://myfond.ml/data/trips/imgs/" . $trips[$k]['odometer_start_img']];
                $trips[$k] += ['odometer_end_img_url' => "http://myfond.ml/data/trips/imgs/" . $trips[$k]['odometer_end_img']];
                $trips[$k]['date'] = $this->converDate( $trips[$k]['date'], $trips[$k]['time']);
            }

            header('Content-Type: application/json');
            echo $this->safe_json_encode($trips);

        } else {
            echo $this->authCheck();
        }
    }

    public function deleteTrip($id){
        if ($this->authCheck() === true) {
            ORM::for_table('users')->find_one($id)->delete();
        }
    }

    public function updateTrip(){
        if ($this->authCheck() === true) {
            $trip = ORM::for_table('trips')->create();
            $trip->set('trip_name', $this->newTripObject->trip_name);
            $trip->set('odometer_start', $this->newTripObject->odometer_start);
            $trip->set('odometer_start_img', $this->newTripObject->odometer_start_img);
            $trip->set('odometer_end', $this->newTripObject->odometer_end);
            $trip->set('odometer_end_img', $this->newTripObject->odometer_end_img);
            $trip->set('start_point', $this->newTripObject->start_point);
            $trip->set('direct_img', $this->newTripObject->direct_img);
            $trip->set('points', $this->newTripObject->points);
            $trip->set('date', $this->newTripObject->date);
            $trip->set('refull',$this->newTripObject->refull);
            $trip->set('routs',$this->newTripObject->routs);
            $trip->set('fuel', $this->newTripObject->fuel);
            $trip->set('fuel_capacity', $this->newTripObject->fuel_capacity);
            $trip->set('cost', $this->newTripObject->cost);
            $trip->set('time', $this->newTripObject->time);
            $trip->save();
            echo json_encode(['Responce' => 'Success']);
        } else {
            echo $this->authCheck();
        }
    }



// Services ==============================================================================================================
    private function authCheck(){
        $user = ORM::forTable('users')->where('login', $this->userName)->find_array();
        if (isset($user[0]['active']) AND $user[0]['active'] == '1' AND $this->userToken == $this->token){
            return true;
        } else {
            header('Content-Type: application/json');
            return json_encode(['Responce code:' => '401']);
        }
    }

    public function authorization(){
        $user = ORM::forTable('users')->where('login', $this->userName)->find_array();
        if (isset($user[0]['active']) AND $user[0]['active'] == '1' AND $user[0]['password'] == $this->pass AND $this->userToken == $this->token){
            header('Content-Type: application/json');
            echo $this->safe_json_encode("OK");
        } else {
            echo $this->safe_json_encode("incorrect login or pass");
        }

    }

    private function safe_json_encode($value){
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            $encoded = json_encode($value, JSON_PRETTY_PRINT);
        } else {
            $encoded = json_encode($value);
        }
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $encoded;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_UTF8:
                $clean = $this->utf8ize($value);
                return safe_json_encode($clean);
            default:
                return 'Unknown error'; // or trigger_error() or throw new
                Exception();
        }
    }

    private function utf8ize($mixed) {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = utf8ize($value);
            }
        } else if (is_string ($mixed)) {
            return utf8_encode($mixed);
        }
        return $mixed;
    }

    private function converDate($date, $time) {
        $arr = explode('-', $date);
        $arr = array_reverse($arr);
        $arr[2] = substr($arr[2], 2, 2);
        $str = implode('.', $arr);
        //$str = $str . $time;
        return $str;
    }



}