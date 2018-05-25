<?php
//**************************************************************************//
//    JdGeolocation -- Aux class to handle a geolocation                    //
//**************************************************************************//
##############################################################################
## Jetdata Sistemas, october of 2016
## Developer: Rony Silva (ronysilvati@live.com)
##############################################################################
//////////////////////////////////////////////////////////////////////////////

namespace GWSoft;

class JdGeolocation{
    private $keyGoogleApi           = '';
    private $urlGeocode             = 'https://maps.googleapis.com/maps/api/geocode/json?region=br';
    private $urlDirectionService    = 'https://maps.googleapis.com/maps/api/directions/json?language=br';

    public function __construct($keyApi){
        if($keyApi){
            $this->keyGoogleApi = $keyApi;
        }
        else{
            throw new \Exception("A google api key is required!");
        }
    }


    public function calcRoute($dsOrigin,$dsDestination,$dsWayPoints=[]){
        if($this->isValidPoint($dsOrigin) && $this->isValidPoint($dsDestination) && ((is_array($dsWayPoints) && (count($dsWayPoints) === 0)) || ($this->isValidWayPoints($dsWayPoints)))){
            $url = $this->getUrlDistanceMatrix()."&origin={$this->convertPointToString($dsOrigin)}";
            $url .= "&destination={$this->convertPointToString($dsDestination)}";

            if(count($dsWayPoints) > 0){
                $url .= "&waypoints={$this->convertWaypointsToString($dsWayPoints)}";
            }

            $url .= "&mode=driving";

            $resp_json = file_get_contents($url);
            $resp = json_decode($resp_json, true);

            return $this->tratedResponse($resp);
        }
        else{
            throw new \Exception("One or many points are invalid!");
        }
    }

    /**
     * @param $data
     * @return array
     * @throws \Exception
     * Return a array with distance and time between Origin, Destination and Waypoints (Case exist)
     */
    public function tratedResponse($data){
        $tratedData = [
            'status'    => 'BAD_DATA_RESPONSE_FROM_GOOGLE',
            'distance'  => 0,
            'time'      => 0
        ];

        try{
            if(is_array($data) && (array_key_exists('status',$data))){

                $tratedData['status']   = $data['status'];

                if(($data['status'] === 'OK') && (array_key_exists('routes',$data)) && (is_array($data['routes']))){
                    $routes = $data['routes'][0];

                    if(array_key_exists('legs',$routes) && is_array($routes['legs'])){
                        foreach($routes['legs'] as $key=>$leg){
                            $tratedData['distance'] += $leg['distance']['value'];
                            $tratedData['time'] += $leg['duration']['value'];
                        }
                    }
                }
            }
        }
        catch(\Exception $e){
            throw new \Exception("Bad data response from Google!");
        }


        return $tratedData;
    }

    /**
     * @param $point
     * @return string
     * @throws Exception
     * Convert a point to a string pattern to use in distanceService
     */
    public function convertPointToString($point){
        if($this->isValidPoint($point)){
            return $point['lat'].','.$point['lng'];
        }
        else{
            throw new \Exception("This is a invalid point!");
        }
    }

    /**
     * @param $waypoints
     * @return string
     * @throws Exception
     * Returna a string with all waypoints in distanceService pattern
     */
    public function convertWaypointsToString($waypoints){
        if($this->isValidWayPoints($waypoints)){
            $stringWaypoints = '';

            foreach($waypoints as $key=>$waypoint){
                if($key != 0){
                    $stringWaypoints .= '|';
                }

                $stringWaypoints .= $this->convertPointToString($waypoint);
            }

            return $stringWaypoints;
        }
        else{
            throw new \Exception("This is a invalid waypoint array!");
        }
    }

    /**
     * @param $point
     * @return bool
     * Return true if a point is valid to insert in a call to distanceService
     */
    public function isValidPoint($point){

        if(($point && is_array($point) && array_key_exists('lat',$point) && is_numeric($point['lat']))
            && (array_key_exists('lng',$point) && is_numeric($point['lng']))){
            return true;
        }

        return false;
    }

    /**
     * @param $waypoints
     * @return bool
     * Return true if a list of waypoints is valid to inser in a call to distanceService
     */
    public function isValidWayPoints($waypoints){
        if($waypoints && is_array($waypoints)){
            foreach($waypoints as $key=>$point){
                if(!$this->isValidPoint($point)){
                    return false;
                }
            }
        }
        else{
            return false;
        }

        return true;
    }

    public function getAddressByLatLnt($lat,$lng){
        if($lat && $lng){
            $url = $this->getUrlGeocode().'&latlng='.$lat.','.$lng;

            $resp_json = file_get_contents($url);

            $resp = json_decode($resp_json, true);

            if($resp['status']=='OK'){

                return $resp['results'][0];

            }
        }

        return null;
    }

    private function getUrlGeocode(){
        return $this->urlGeocode.'&key='.$this->keyGoogleApi;
    }

    private function getUrlDistanceMatrix(){
        return $this->urlDirectionService.'&key='.$this->keyGoogleApi;
    }
}