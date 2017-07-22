<?php
/****

 * Google map plotter - 
 *
 * File:  gmapPlotter.php,v $
 * Created on: 2008-12-15
 *
 * Copyright (c) 2008 - Rahul Sinha <rahul@zimaudio.com>
 *
 * This Class is a free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; 
 *
 * This Class is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this library in the file LICENSE.LGPL; if not, write to the
 * Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307 USA
 * gmapPlotter.php is a simple script to plot Google Map using GMAp API. It can be executed from
 * a browser or from the command line.
 * 
 * @copyright 2008 Rahul Sinha
 * @author Rahul Sinha <rahul@zimaudio.com>
 * @package gmapPlotter.tar
 * @version 0.0.1
 * 
****/


class googleMapGeocoder
{

    var $address;
    var $KEY;
    var $MAPS_HOST;
    var $delay;
    var $base_url;
    var $geocode_pending;
    
   public function __construct()
   {
        
        $this->KEY = "Your Key Here";
        $this->MAPS_HOST = "maps.google.com";
        $this->delay = 0;
        $this->base_url = "http://" . $this->MAPS_HOST . "/maps/geo?output=xml" . "&key=" . $this->KEY;
        $this->geocode_pending = true;
       
   }

   public function geocodeAddress($pAddress)
   {
        $this->address = $pAddress;
        $latlonArr = array();
        while($this->geocode_pending)
        {
            $request_url = $this->base_url . "&q=" . urlencode($this->address);
            $xml = simplexml_load_file($request_url) or die("url not loading");
            $status = $xml->Response->Status->code;
            if (strcmp($status, "200") == 0) {
              // Successful geocode
              $this->geocode_pending = false;
              $coordinates = $xml->Response->Placemark->Point->coordinates;
              $coordinatesSplit = split(",", $coordinates);
              // Format: Longitude, Latitude, Altitude
              $lat = $coordinatesSplit[1];
              $lng = $coordinatesSplit[0];
              $latlonArr['lat'] = $lat;
              $latlonArr['lon'] = $lng;
              
            } else if (strcmp($status, "620") == 0) {
              // sent geocodes too fast
              $delay += 100000;
            } else {
              // failure to geocode
              $this->geocode_pending = false;
              
            }
            usleep($delay);
            break;
        }
     return $latlonArr;  
   }
 
   public function plotgoogleMap($pArr)
   {
            #print_r($pArr);
            
            $str =  '<script>
                        var map = new GMap2(document.getElementById("map"));
                        map.addControl(new GLargeMapControl());
                        map.addControl(new GMapTypeControl());
                        map.addControl(new GScaleControl());
                        map.setCenter(new GLatLng('.$pArr[lat].', '.$pArr[lon].'), 6);
                                                    
                        // Creates a default marker whose info window displays the given number
                        function createMarker(point, number)
                        {
                                var marker = new GMarker(point);
                                // Show this markers index in the info window when it is clicked
                                var html = number;
                                GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(html);});
                                return marker;
                        };';
            $str .= "var point = new GLatLng(" . $pArr['lat'] . "," . $pArr['lon'] . ");\n";
            $str .= "var marker = createMarker(point, '" . addslashes($this->address)."');\n";
            $str .= "map.addOverlay(marker);\n";
            $str .= "</script>\n";
        
    return $str;
   }
}
?>