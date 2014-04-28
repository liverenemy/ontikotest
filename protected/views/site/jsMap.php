<?php
/**
 * @var SiteController $this
 */
$this->menu = array(
    array('label' => 'Через сервер',    'url' => array('map')),
    array('label' => 'Только JS',       'url' => array('jsMap')),
);
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
<script src="/js/AngularJS/1.2.16/angular.min.js"></script>
<h1>JSON API без PHP</h1>
<div ng-app="map" ng-controller="GeocodeController" ng-init="initMap()">
    <p>Получение геокоординат объекта по передаваемому адресу с помощью Google Maps API.</p>
    <p>
        <input type="text" style="width: 400px;" placeholder="Введите адрес" ng-model="params.address">
        <input type="button" value="Найти" ng-click="geocodeAddress(params.address)">
    </p>
    <p ng-if="params.tooMuchResults">
        <span style="background: #ff0000; color: #fff;">
            Нашлось несколько объектов. Пожалуйста, укажите адрес более подробно. Например, введите название города.
        </span>
    </p>
    <div id="map-canvas" style="width: 90%; height: 400px;"></div>
</div>
<script>
    var ngMap = angular.module('map', []);
    var map;
    var singleMarker;
    var multipleMarkers = [];
    var geocoder;
    ngMap.factory('MapMarkerService', function($rootScope){
        var markers = [];

        var $this = {
            clearMarkers: function() {
                markers = [];
                $rootScope.$broadcast('Markers:updated');
            },
            getMarkers: function() {
                return markers;
            },
            setMarkers: function(newMarkers) {
                markers = newMarkers;
                $rootScope.$broadcast('Markers:updated');
            }
        };

        return $this;
    });
    function GeocodeController($scope)
    {
        $scope.geocoder = function() {
            if (geocoder == undefined)
            {
                geocoder = new google.maps.Geocoder();
            }
            return geocoder;
        };
        $scope.initMap = function() {
            mapOptions = {
                zoom: 8,
                center: new google.maps.LatLng(60, 105)
            };
            $scope.params = {
                address: '',
                tooMuchResults: false
            };
            map = new google.maps.Map(document.getElementById('map-canvas'),
                mapOptions);
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = new google.maps.LatLng(position.coords.latitude,
                        position.coords.longitude);

                    map.setCenter(pos);

                    $scope.geocoder().geocode(
                        {
                            'latLng': pos
                        },
                        function(results, status) {
                            $scope.$apply(function(){
                                if (status == google.maps.GeocoderStatus.OK) {
                                    if (results[0]) {
                                        $scope.params.address = results[0].formatted_address;
                                    } else {
                                        $scope.params.address = '';
                                    }
                                } else {
                                    alert('Geocoder failed due to: ' + status);
                                }
                            });
                        }
                    );
                }, function() {
                    $scope.handleNoGeolocation(true);
                });
            } else {
                // Browser doesn't support Geolocation
                $scope.handleNoGeolocation(false);
            }
        };


        $scope.handleNoGeolocation = function(errorFlag) {
            if (errorFlag) {
                var content = 'Error: The Geolocation service failed.';
            } else {
                var content = 'Error: Your browser doesn\'t support geolocation.';
            }

            var options = {
                map: map,
                position: new google.maps.LatLng(60, 105),
                content: content
            };

            var infowindow = new google.maps.InfoWindow(options);
            map.setCenter(options.position);
        };

        $scope.clearMarkers = function() {
            if (singleMarker)
            {
                singleMarker.setMap(null);
            }
            if (!multipleMarkers || multipleMarkers.length > 0)
            {
                for (var i = 0; i < multipleMarkers.length; i++)
                {
                    multipleMarkers[i].setMap(null);
                }
                multipleMarkers = [];
            }
        };

        $scope.geocodeAddress = function(address) {
            $scope.geocoder().geocode( { 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    $scope.clearMarkers();
                    if (results.length)
                    {
                        if (results.length == 1)
                        {
                            $scope.$apply(function(){
                                $scope.params.tooMuchResults = false;
                            });
                            map.setCenter(results[0].geometry.location);
                            singleMarker = new google.maps.Marker({
                                map: map,
                                position: results[0].geometry.location
                            });
                        }
                        else
                        {
                            $scope.$apply(function(){
                                $scope.params.tooMuchResults = true;
                            });
                            var minLat, maxLat, minLong, maxLong;
                            for (var i = 0; i < results.length; i++)
                            {
                                var marker = new google.maps.Marker({
                                    map: map,
                                    position: results[i].geometry.location
                                });
                                multipleMarkers.push(marker);
                            }
                        }

                    }
                } else {
                    $scope.params.tooMuchResults = false;
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        };
    }
</script>