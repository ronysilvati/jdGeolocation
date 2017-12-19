<?php
//**************************************************************************//
//    JdGeolocation -- Classe auxiliar para a manipulação de geolocalização //
//**************************************************************************//
##############################################################################
## Jetdata Sistemas, outubro de 2016
## Desenvolvedor: Rony Silva (ronysilvati@live.com)
##############################################################################
//////////////////////////////////////////////////////////////////////////////


class JdGeolocation{
    private $keyGoogleApi   = '';
    private $urlGeocode     = 'https://maps.googleapis.com/maps/api/geocode/json?region=br';
    public function __constructor($keyApi){
        if($keyApi){
            $this->keyGoogleApi = $keyApi;
        }
        else{
            throw new Exception("A google api key é necessária");
        }
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
}